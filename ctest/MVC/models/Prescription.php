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
    public ?string $note='';
    public ?string $last_processed_timestamp=null;
    public ?string $cart_ID=null;
    public ?string $channeling_ID=null;
    public ?int $refills=1;
    
 
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

        return ['prescription'=> ['doctor','patient','order_ID','type','location','note','last_processed_timestamp','cart_ID','channeling_ID','refills']];
    }

    public function attributes(): array{

        return  ['doctor','patient','order_ID','type','location','note','last_processed_timestamp','cart_ID','channeling_ID','refills'];

    }
    
    public function getPrescriptionInOrder($orderID){

        return  $this->fetchAssocAll(['order_ID'=>$orderID]);
    }

    public function getPrescriptionByPatient($pair=[]){
        $patient=Application::$app->session->get('user');
        if($pair){
            return $this->customFetchAll("select * from prescription where patient=".$pair[0]." and doctor=".$pair[1]." order by uploaded_date");
        }
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
        $note=$_POST['note'];
        $refills=$_POST['refills'];
        $this->customFetchAll("update prescription set note='$note',refills=$refills where prescription_ID=".$prescription);
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
            $frequency=$_POST['frequency'];
        
        }
        else{
            return [$note,$refills];       
        }
        $med_ID=$medicineModel->getMedicineID($name,$strength);
        if($this->alreadyIn($prescription,$med_ID)){
            $this->customFetchAll("update prescription_medicine set med_amount=$amount,dispense_type=$dispense_type,dispense_count='$dispense_count',frequency='$frequency' where med_ID=$med_ID and prescription_ID=$prescription");
            return [$note,$refills];   
        }
        $this->customFetchAll("insert into prescription_medicine (med_ID,prescription_ID,med_amount,dispense_type,dispense_count,frequency) values('$med_ID','$prescription','$amount',$dispense_type,$dispense_count,'$frequency') ");
        return [$note,$refills];   

    }
    
    public function isInCart($pres){
        $cart=new Cart();
        $patient=Application::$app->session->get('user');
        $cart=$cart->getPatientCart($patient)[0]['cart_ID'];
        $result= $this->fetchAssocAllByName(['prescription_ID'=>$pres,'cart_ID'=>$cart],'prescription');
        if($result){
            return true;
        }
        else
        return false;

    }
    
    public function setPrices($prescription){
        $medicines=$this->getPrescriptionMedicine($prescription);
        var_dump($medicines);
        foreach($medicines as $medicine){
            $this->customFetchAll("update prescription_medicine set prescription_current_price=".$medicine['unit_price']." where med_ID=".$medicine['med_ID']." and prescription_ID=$prescription");
        }
        exit;
    }
    public function removeFromCart($prescription){
        $this->customFetchAll("update prescription set cart_ID=null  where prescription_ID=".$prescription);
    }
    public function addToOrder($orderID,$cartID){
        $prescriptions=$this->customFetchAll("select prescription_ID from prescription where cart_ID=$cartID and type='E-prescription'");
        foreach($prescriptions as $pres){
            $this->setPrices($pres);
        }
        $this->customFetchAll("update prescription set order_ID='$orderID' ,cart_ID=null where cart_ID='$cartID'");
        return true;
    }
    public function getPatientPrescription($patient){
        return array_reverse($this->fetchAssocAll(['patient'=>$patient]));
    }
    //get medicine without devices
    public function getPrescriptionMedicine($prescriptionID){
        return $this->customFetchAll("select * from prescription_medicine right join medical_products on medical_products.med_ID=prescription_medicine.med_ID where category<>'device' and prescription_medicine.prescription_ID=".$prescriptionID);
    }
    public function getPrescriptionDevice($prescriptionID){
        return $this->customFetchAll("select * from prescription_medicine left join medical_products on medical_products.med_ID=prescription_medicine.med_ID where category='device' and prescription_medicine.prescription_ID=".$prescriptionID);
    }

    public function getLackedInPrescription($presID){
        $medicineModel=new Medicine();
        $array=[];
        $medicines=$this->fetchAssocAllByName(['prescription_ID'=>$presID],'prescription_medicine');
        foreach($medicines as $medicine){
            if(!$medicineModel->checkStock($medicine['med_ID'])){
                array_push($array,$medicineModel->getMedicineByID($medicine['med_ID']));
            }
        }
    }
    //return total price of a E-prescription
    //example: getPrice(70) will return int(1234)
    public function getPrice($presID){
        $medicineModel=new Medicine();
        $total=0;
        $prescription=$this->fetchAssocAll(['prescription_ID'=>$presID]);
        if($prescription[0]['type']!='E-prescription'){
            return '';
        }
        $medicines=$this->fetchAssocAllByName(['prescription_ID'=>$presID],'prescription_medicine');
        foreach($medicines as $medicine){
            $frequency=$medicine['frequency'];
            $type=$medicine['dispense_type'];
            $freq=1;
            $days=1;
            switch($frequency){
                case 'frequency1':
                    $freq=1;
                    break;
                case 'Daily':
                    $freq=1;
                    break;
                case 'BID':
                    $freq=2;
                    break;
                case 'TID':
                    $freq=3;
                    break;
                case 'QID':
                    $freq=4;
                    break;
                case 'QHS':
                    $freq=1;
                    break;
            }
            switch($type){
                case 'days':
                    $days=1;
                    $qwk=1;
                    break;
                case 'week':
                    $days=7;
                    $qwk=$medicine['dispense_count'];
                    break;
                case 'months':
                    $days=30;
                    $qwk=4*$medicine['dispense_count'];
                    break;
            }
            $detmed=$medicineModel->fetchAssocAll(['med_ID'=>$medicine['med_ID']])[0];
            
            if($frequency=='QWK'){
                $total=$total+($medicine['med_amount']*$detmed['unit_price']*$qwk);
                var_dump("med-".$total);
            }
            else{
                $total=$total+($medicine['med_amount']*$detmed['unit_price']*$days*$freq);

            }
        }
        return $total;
        

    }
    //return total amount units of each medicine in a E-prescription
    //example : getAmount(70) will return array(['12'=>12,'23'=>24]) where ['med_ID'=>int(amount)]
    public function getAmount($presID){
        $medicineModel=new Medicine();
        $total=0;
        $prescription=$this->fetchAssocAll(['prescription_ID'=>$presID]);
        if($prescription[0]['type']!='E-prescription'){
            return '';
        }
        $medicines=$this->fetchAssocAllByName(['prescription_ID'=>$presID],'prescription_medicine');
        $array=[];
        foreach($medicines as $medicine){
            $frequency=$medicine['frequency'];
            $type=$medicine['dispense_type'];
            $freq=1;
            $days=1;
            switch($frequency){
                case 'frequency1':
                    $freq=1;
                    break;
                case 'Daily':
                    $freq=1;
                    break;
                case 'BID':
                    $freq=2;
                    break;
                case 'TID':
                    $freq=3;
                    break;
                case 'QID':
                    $freq=4;
                    break;
                case 'QHS':
                    $freq=1;
                    break;
            }
            switch($type){
                case 'days':
                    $days=1;
                    $qwk=1;
                    break;
                case 'week':
                    $days=7;
                    $qwk=$medicine['dispense_count'];
                    break;
                case 'months':
                    $days=30;
                    $qwk=4*$medicine['dispense_count'];
                    break;
            }
            $detmed=$medicineModel->fetchAssocAll(['med_ID'=>$medicine['med_ID']])[0];
            
            if($frequency=='QWK'){
                $total=$total+($medicine['med_amount']*$qwk);
               
            }
            else{
                $total=$total+($medicine['med_amount']*$days*$freq);

            }
            $array[$medicine['med_ID']]=$total;
        }
        return $total;
        

    }
    public function prescriptionToPDF($presID){
        $pdfModel=new PDF();
        $meds=$this->getPrescriptionMedicine($presID);
        $devices=$this->getPrescriptionDevice($presID);
        $header=$this->customFetchAll("select employee.name as doctor,prescription.uploaded_date,patient.name,prescription.last_processed_timestamp from prescription left join patient on prescription.patient=patient.patient_ID left join employee on employee.nic=prescription.doctor  where prescription_ID=".$presID)[0];
        $medrows='<tr><th>Medicine</th><th>Frequency</th><th>Amount per Dose</th><th>Dispense</th><th>Route</th></tr>';
        $devrows='<tr><th>Device</th><th>Amount</th></tr>';
        $head="<h3>Medical Devices</h3>";
        if($meds){
            foreach($meds as $med){
                $medrows.="<tr><td>".$med['name']."-".$med['strength']."</td><td>".$med['frequency']."</td><td>".$med['dispense_count']." ".$med['dispense_type']."</td></tr>";
            }
        }
        else{
            $medrows='';
        }
        if($devices){
            
            foreach($devices as $dev){
                $devrows.="<tr><td>".$devices['name']."</td><td>".$devices['amount']."</td></tr>";
            }
        }
        else{
            $head='';
            $devrows='';
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
                        <div>
                            Issued Doctor :".$header['doctor']."
                            Issued Day :".$header['uploaded_date']."
                        
                        </div>
                        <div>
                            Patient :".$header['name']."
                            </div>

                        
                            </section>
                            <section>
                            <table border='0'>
                        
                            ".$medrows."
                            </table>
                            ".$head."
                            <table border='0'>
                            "
                            
                            .$devrows."
                        </table>
                        </section>
                        </body>
                        <html>
        ";
        
        $pdfModel->createPDF($str,'Prescription-'.$header['uploaded_date']);


    }
    // =========CREATE NEW ORDER===============
    
    public function add_med_rec ($med_ID, $prescription_ID, $amount) {
       return $this->customFetchAll(" INSERT INTO prescription_medicine ( med_ID, prescription_ID, amount ) VALUES ( $med_ID, $prescription_ID, $amount ); ");
    }
    
    public function get_curr_orders($prescription_ID) {
       return $this->customFetchAll("SELECT *, prescription_medicine.amount AS order_amount, prescription_medicine.prescription_current_price AS current_price, medical_products.amount AS available_amount FROM prescription_medicine INNER JOIN prescription ON prescription_medicine.prescription_ID=prescription.prescription_ID INNER JOIN medical_products ON prescription_medicine.med_ID=medical_products.med_ID WHERE prescription_medicine.prescription_ID=$prescription_ID; ");
    }
    
    public function get_patient_details($prescription_ID) {
       return $this->customFetchAll("SELECT * FROM prescription INNER JOIN patient ON prescription.patient=patient.patient_ID WHERE prescription.prescription_ID=$prescription_ID;");
    }
    
    public function get_prescription_location( $order_ID ) {
       return $this->customFetchAll(" SELECT *, patient.name AS p_name FROM patient INNER JOIN _order ON patient.patient_ID=_order.patient_ID INNER JOIN prescription ON _order.order_ID=prescription.order_ID WHERE prescription.order_ID=$order_ID ");
    }
    

}   
