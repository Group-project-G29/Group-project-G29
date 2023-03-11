<?php 
namespace app\models;

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
    public string $patient='';
     
   
    public function addReferral(){
        if(trim($this->history)||trim($this->reason) || trim($this->assessment)){
            $this->history="lkjk".trim($this->history)."lkl";
            $this->reason=trim($this->reason);
            $this->assessment=trim($this->assessment);
            return parent::save();
        }
    }
 
    public function rules(): array
    {
        return [
            


        ];
    }
    public function setter($doctor,$patient,$speciality,$text,$type,$refer_doctor){
        $this->doctor=$doctor;
        $this->speciality=$speciality;
        $this->note=$text;
        $this->type=$type;
        $this->issued_doctor=$refer_doctor;
        $this->patient=$patient;

    }
    public function getReferrals($patient,$doctor){
        $referrals = $this->customFetchAll("select distinct * from referrel  where patient=".$patient." and (doctor='".$doctor."' or issued_doctor='".$doctor."') order by date desc");
        return $referrals;
    }
    public function getReferralsByPatient($patient){
        $referrals = $this->customFetchAll("select distinct * from referrel  where patient=".$patient);
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
        return ['referrel'=> ['doctor','patient','speciality','name','note','type','issued_doctor','history','reason','assessment']];
    }

    public function attributes(): array
    {
        return  ['doctor','patient','speciality','name','note','type','issued_doctor','history','reason','assessment'];
    }
    //create referral pdf
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
                                        

                </style>
                </head>
                <body>
                <section>
                    <h3><center>Medical Referral Letter</center></h3>
                </section>
                    <section class='show'>
                        <div>Written to Doctor :".$stakeholdermain['doctor_name']."</div>
                        <div>Written to speciality:".$referral['speciality']."</div><br>
                        <div>Patient Name :".$stakeholdermain['patient_name']."</div>
                        <div>Patient Gender :".$stakeholdermain['gender']."</div><br>
                        <div>Issued Doctor :".$stakeholdersub['issued_doctor_name']."</div>"."
                        <div>Issued date :".$referral['date']."</div><br><br><br>
                        
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

// <div>Patient Medical History</div>
//                         <div>".$referral->history."</div>
//                         <div>Reason for Referral</div>
//                         <div>".$referral->reason."</div>
//                         <div>Medical Assessment</div>
//                         <div>".$referral->assessment."</div>
//                         <div>Note</div>
//                         <div>".$referral->note."</div>