<?php 
namespace app\models;

use app\core\DbModel;


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
        return parent::save();
    }
 
    public function rules(): array
    {
        return [
            'doctor'=>[self::RULE_REQUIRED],
            


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
        $referrals = $this->customFetchAll("select distinct * from referrel  where patient=".$patient." and (doctor='".$doctor."' or issued_doctor='".$doctor."')");
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
        $referrels=$this->customFetchAll("select * from referrel where issued_doctor='".$doctor."' and ref_ID='".$referrel."'");
        if(isset($referrels)){
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

    
}   