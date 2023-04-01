<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\PDF;
use app\core\UserModel;

class Prescription extends DbModel{
    public ?string $doctor=null;
    public ?string $patient=null;
    public ?string $order_ID=null;
    public ?string $type=null;
    public ?string $location=null;
    public ?string $note=null;
    public ?string $last_processed_timestamp=null;
    public ?string $cart_ID=null;
    public ?string $channeling_ID=null;
   
  
 
    public function rules(): array{
        return [];
    }

    public function fileDestination(): array{

        return ['img'=>"media/images/patient/prescription/".$this->location];
    }

    public function tableName(): string{

        return 'prescription';
    }

    public function primaryKey(): string{

        return 'prescription_ID';
    }

    public function tableRecords(): array{

        return ['prescription'=> ['doctor','patient','order_ID','type','location','note','last_processed_timestamp','cart_ID','channeling_ID']];
    }

    public function attributes(): array{

        return  ['doctor','patient','order_ID','type','location','note','last_processed_timestamp','cart_ID','channeling_ID'];

    }
    
    public function getPrescriptionInOrder($orderID){

        return  $this->fetchAssocAll(['order_ID'=>$orderID]);
    }

    public function getPrescriptionByPatient(){

        $patient=Application::$app->session->get('user');
        return $this->customFetchAll("select * from prescription where patient=".$patient." order by uploaded_date");
    }

    public function addPrescriptionSoftCopy(){
        $cartModel=new Cart();
        //$_FILE=>array(1) { ["file"]=> array(6) { ["name"]=> array(2) { [0]=> string(8) "ref1.jpg" [1]=> string(8) "ref2.png" } ["full_path"]=> array(2) { [0]=> string(8) "ref1.jpg" [1]=> string(8) "ref2.png" } ["type"]=> array(2) { [0]=> string(10) "image/jpeg" [1]=> string(9) "image/png" } ["tmp_name"]=> array(2) { [0]=> string(25) "/opt/lampp/temp/phplH2iAl" [1]=> string(25) "/opt/lampp/temp/phpSwHtAo" } ["error"]=> array(2) { [0]=> int(0) [1]=> int(0) } ["size"]=> array(2) { [0]=> int(76085) [1]=> int(67004) } } } 
        // files in $_FILE array
        $filearray=$_FILES;
        if($filearray['prescription']['name'][0]==''){
            return true;
        }
        $counter=0;
        
        //save each file in the system hard disk
        foreach($filearray['prescription']['name'] as $file){
            $filename=uniqid().$file;
            $filePath="media/patient/prescriptions/".$filename;
            $fileTempPath=$filearray['prescription']['tmp_name'][$counter];
            move_uploaded_file($fileTempPath,$filePath);
            //save in database
            $prescritpion=new Prescription();
            $prescritpion->type="softcopy prescription";
            $prescritpion->location=$filename;
            $prescritpion->patient=Application::$app->session->get('user');
            $prescritpion->cart_ID=$cartModel->getPatientCart(Application::$app->session->get('user'))[0]['cart_ID'];
            $prescritpion->savenofiles();
            $counter++;



        }
    }

    //check whether there is a prescription with the patient in the channeling session
    public function isTherePrescription($patientID,$channelingID){
        $result=$this->fetchAssocAllByName(['patient'=>$patientID,'channeling_ID'=>$channelingID],'prescription');
        
        if(!$result)return false;
        else return $result[0]['prescription_ID'];
    }

    //create new prescription for a patient
    public function createNewPrescription($patientID){
        $prescritpion=new Prescription();
        $prescritpion->patient=$patientID;
        $prescritpion->doctor=Application::$app->session->get('userObject')->nic;
        $prescritpion->channeling_ID=Application::$app->session->get('channeling');
        $prescritpion->type='E-prescription';
        return $prescritpion->savenofiles()[0]['last_insert_id()'];
    }
    public function alreadyIn($prescriptionID,$medicineID){
        $prescriptionModel=new Prescription();
        $result=$prescriptionModel->fetchAssocAllByName(['prescription_ID'=>$prescriptionID,'med_ID'=>$medicineID],'prescription_medicine');
        if($result){
            return true;
        }
        else{
            return false;
        }
    }
    //med_ID 	prescription_ID 	amount 	route 	dispense 	frequency 	
    public function addPrescriptionMedicine($patientID,$channelingID){
        $medicineModel=new Medicine();
        $prescription=$this->isTherePrescription($patientID,$channelingID);
        if(!$prescription){
            $prescription=$this->createNewPrescription($patientID);
        }
        if(($_POST['amount']!='')  && ($_POST['frequency']!='') && ($_POST['name']!='')){
            $dispense=explode(' ',$_POST['dispense'])??[];
            $name=explode('-',$_POST['name'])[0]??[];
            $strength=explode('-',$_POST['name'])[1]??null;
            $dispense_type="'".$dispense[1]."'"??null;
            $dispense_count=$dispense[0]??0;
            if($dispense_count=='')$dispense_count=null;
            $amount=explode(' ',$_POST['amount'])[0];
            $route=$_POST['route'];
            $frequency=$_POST['frequency'];
        }
        else{
            return false;
        }
        $med_ID=$medicineModel->getMedicineID($name,$strength);
        if($this->alreadyIn($prescription,$med_ID)){
            $this->customFetchAll("update prescription_medicine set med_amount=$amount, route='$route',dispense_type=$dispense_type,dispense_count='$dispense_count',frequency='$frequency' where med_ID=$med_ID and prescription_ID=$prescription");
            return $prescription;   
        }
        $this->customFetchAll("insert into prescription_medicine (med_ID,prescription_ID,med_amount,route,dispense_type,dispense_count,frequency) values('$med_ID','$prescription','$amount','$route',$dispense_type,$dispense_count,'$frequency') ");
        return $prescription;
    }
    
    public function addToOrder($orderID,$cartID){
        $this->customFetchAll("update prescription set order_ID='$orderID' ,cart_ID=null where cart_ID='$cartID'");
        return true;
    }
    //get medicine without devices
    public function getPrescriptionMedicine($prescriptionID){
        return $this->customFetchAll("select * from prescription_medicine right join medical_products on medical_products.med_ID=prescription_medicine.med_ID where category<>'device' and prescription_medicine.prescription_ID=".$prescriptionID);
    }
    public function getPrescriptionDevice($prescriptionID){
        return $this->customFetchAll("select * from prescription_medicine left join medical_products on medical_products.med_ID=prescription_medicine.med_ID where category='device' and prescription_medicine.prescription_ID=".$prescriptionID);
    }

    public function referralToPDF($refID){
        $pdfModel=new PDF();

        $stakeholdermain=$this->customFetchAll("select patient.name as patient_name,patient.gender ,patient.age,employee.name as doctor_name  from referrel left join patient on patient.patient_ID=referrel.patient right join doctor on doctor.nic=referrel.doctor left join employee on employee.nic=doctor.nic where ref_ID=$refID")[0];
        $stakeholdersub=$this->customFetchAll("select employee.name as issued_doctor_name  from referrel right join doctor on doctor.nic=referrel.issued_doctor left join employee on employee.nic=doctor.nic where ref_ID=$refID")[0];
        $referral=$this->fetchAssocAll(['ref_ID'=>$refID])[0];
        $addstr='';
        if($referral['history'] && $referral['history']!=''){
            $addstr.="<div>Patient Medical History</div>
                    <div>".$referral['history']."</div>";

        }
        if(isset($referral['assessment']) && $referral['assessment']!=''){
            $addstr.="<div>Medical Assessment</div>
            <div>".$referral['assessment']."</div>";
        }
        if(isset($referral['reason']) && $referral['reason']!=''){
            $addstr.="<div>Reason for referral</div>
            <div>".$referral['reason']."</div>";
        }
        if(isset($referral['note']) && $referral['note']!=''){
            $addstr.="<div>Note</div>
            <div>".$referral['note']."</div>";
        }
        $str="
            <html>
                <head>
                <style>
                    .show{
    
                      background-color:red;
                      
                        

                    }
                </style>
                </head>
                <body>
                    <section class='show'>
                        <div>Written to Doctor :".$stakeholdermain['doctor_name']."</div>
                        <div>Written to speciality:".$referral['speciality']."</div><br>
                        <div>Patient Name :".$stakeholdermain['patient_name']."</div>
                        <div>Patient Gender :".$stakeholdermain['gender']."</div><br>
                        <div>Issued Doctor :".$stakeholdersub['issued_doctor_name']."</div>"."
                        <div>Issued date :".$referral['date']."</div>
                        
                    </section>
                    <section>
                        ".$addstr."
                    </section>
                </body>
            <html>
        ";
        $pdfModel->createPDF($str,'referral-'.$referral['date']);


    }
    

}   
