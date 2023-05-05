<?php 
namespace app\models;

use app\core\Application;
use app\core\DbModel;
use app\core\PDF;

class Referral extends DbModel{
    public string $doctor='';
    public string $speciality='';
    public string $name='';
    public ?string $note='';
    public string $type='';
    public string $issued_doctor='';
    public ?string $history='';
    public ?string $reason='';
    public ?string $assessment='';
    public ?string $patient='';
    public ?string $third_party='';
    public ?int $appointment_ID=0;
     
    public function stringchecker($str){
        if(!$str) return false;
        $cap_alphabet = range('A', 'Z');
        $sim_alphabet=range('a','z');
        foreach($cap_alphabet as $c){
            if(strpos($str,$c)){
                return true;
            }    
        }
        foreach($sim_alphabet as $c){
            if(strpos($str,$c)){
                return true;
            }    
        }
        return false;
    } 
    public function addReferral($appointment_ID=0){
        if($this->stringchecker(trim($this->history))||$this->stringchecker(trim($this->reason)) || $this->stringchecker(trim($this->assessment))){
            $this->history=trim($this->history);
            $this->reason=trim($this->reason);
            $this->assessment=trim($this->assessment);
            return parent::save();
        }
        $this->appointment_ID=$appointment_ID;
        if($this->type=='softcopy') return parent::save();
    }
 
    public function rules(): array
    {
        return [
            


        ];
    }
    public function setter($doctor,$patient,$speciality,$text,$type,$refer_doctor,$appointment_ID){
        $this->doctor=$doctor;
        $this->speciality=$speciality;
        $this->note=$text;
        $this->type=$type;
        $this->issued_doctor=$refer_doctor;
        $this->patient=$patient;
        $this->$appointment_ID=$appointment_ID;
       
        

    }
    public function getReferrals($patient,$doctor){
        $appointmentModel=new Appointment();
        $appointment=$appointmentModel->getAppointment($patient,Application::$app->session->get('channeling'));
        $referrals_written = $this->customFetchAll("select distinct * from referrel   where patient=".$patient." and (issued_doctor='".$doctor."') order by date desc");
        $referrals_sent = $this->customFetchAll("select distinct * from referrel  where patient=".$patient." and (doctor='".$doctor."') and appointment_ID=$appointment order by date desc");
        $ref['written']=$referrals_written;
        $ref['sent']=array_slice($referrals_sent,0,2);
        return $ref;
    }
    public function getReferralsByPatient($patient){
        $referrals = $this->customFetchAll("select distinct * from referrel  where patient=".$patient." order by date desc");
        return $referrals;
    } 
    public function fileDestination(): array
    {   if($this->name){
            return ['name'=>"media/patient/referrals/".$this->name];
        }
        else{
            return [];
        }
    }
    public function isIssued($referrel,$doctor){
        $referrels=$this->customFetchAll("select * from referrel where  issued_doctor='".$doctor."' and ref_ID='".$referrel."'");
        if($referrels){
            return true;
        }
        else{
            return false;
        }
    }



    public function tableName(): string
    {
        return 'referrel';
    }
    public function primaryKey(): string
    {
        return 'ref_ID';
    }
    public function tableRecords(): array{
        return ['referrel'=> ['doctor','patient','speciality','name','note','type','issued_doctor','history','reason','assessment','third_party','appointment_ID']];
    }

    public function attributes(): array
    {
    return  ['doctor','patient','speciality','name','note','type','issued_doctor','history','reason','assessment','third_party','appointment_ID'];
    }
    //create referral pdf
    public function setseen($id){
        $this->customFetchAll("update referrel set seen=1 where ref_ID=".$id);
    }
    public function referralToPDF($refID){
        $pdfModel=new PDF();

        $stakeholdermain=$this->customFetchAll("select patient.name as patient_name,patient.gender ,patient.age  from referrel left join patient on patient.patient_ID=referrel.patient where ref_ID=$refID")[0];
        $doctor=$this->customFetchAll("select employee.name as doctor_name from  referrel left join doctor on doctor.nic=referrel.doctor left join employee on employee.nic=doctor.nic where ref_ID=$refID")[0];
        $stakeholdersub=$this->customFetchAll("select employee.name as issued_doctor_name,doctor.description from referrel right join doctor on doctor.nic=referrel.issued_doctor left join employee on employee.nic=doctor.nic where ref_ID=$refID")[0];
        
        $referral=$this->fetchAssocAll(['ref_ID'=>$refID])[0];
        $addstr='';
        if($referral['history'] && $referral['history']!=''){
            $addstr.="<div>Patient Medical History</div>
                    <div>".$referral['history']."</div>";

        }
        if($this->stringchecker($referral['assessment']) && $referral['assessment']!=''){
            $addstr.="<div>Medical Assessment</div>
            <div>".$referral['assessment']."</div>";
        }
        if($this->stringchecker($referral['reason']) && $referral['reason']!=''){
            $addstr.="<div>Reason for referral</div>
            <div>".$referral['reason']."</div>";
        }
        
        if($this->stringchecker($referral['note']) && $referral['note']!=' '){
            $addstr.="<div>Note</div>
            <div>".$referral['note']."</div>";
        }
      
        if($this->stringchecker($doctor['doctor_name'])){
            $doctor_name="<div>Written to Doctor :".$doctor['doctor_name']."</div>";
        }
    
        else{
            $doctor_name='';
        }
        
        if($this->stringchecker($referral['speciality'])){
            $spec="<div>Written to speciality :".$referral['speciality']."</div><br>";
        }
    
        else{
            $spec='';
        }
        
        if($this->stringchecker($stakeholdersub['description'])){
            $desc=$stakeholdersub['description'];
        }
    
        else{
            $desc='';
        }
        $str="
            <html>
                <head>
                <style>
                                        

                </style>
                </head>
                <body>
                <section>
                    <h3><center>Medical Referral Letter</center></h3>
                </section>
                    <section class='show'>
                        ".$doctor_name."
                        ".$spec."
                        <div>Patient Name :".$stakeholdermain['patient_name']."</div>
                        <div>Patient Gender :".$stakeholdermain['gender']."</div><br>
                        <div>Issued Doctor :".$stakeholdersub['issued_doctor_name']."</div>"."
                        <div>Issued date :".$referral['date']."</div><br><br><br>
                        
                    </section>
                    <section>
                        ".$addstr."
                    </section>
                    <br>
                    <br>
                    <br>
                    <div>
                        <div>-------------------------------------</div>
                        <div>Dr.".$stakeholdersub['issued_doctor_name']."</div>
                        <div>".$desc."</div>
                    </div>
                </body>
            <html>
        ";
        $pdfModel->createPDF($str,'referral-'.$referral['date']);


    }

    
}   

// <div>Patient Medical History</div>
//                         <div>".$referral->history."</div>
//                         <div>Reason for Referral</div>
//                         <div>".$referral->reason."</div>
//                         <div>Medical Assessment</div>
//                         <div>".$referral->assessment."</div>
//                         <div>Note</div>
//                         <div>".$referral->note."</div>