<?php 
namespace app\models;

use app\core\DbModel;


class Referral extends DbModel{
    public string $doctor='';
    public string $appointment_ID='';
    public string $speciality='';
    public string $name='';
    public string $text='';
    public string $type='';
    public string $refer_doctor='';
   
    public function addReferral(){
        return parent::save();
    }
 
    public function rules(): array
    {
        return [



        ];
    }
    public function setter($doctor,$appointment_ID,$speciality,$text,$type,$refer_doctor){
        $this->doctor=$doctor;
        $this->appointment_ID=$appointment_ID;
        $this->speciality=$speciality;
        $this->text=$text;
        $this->type=$type;
        $this->refer_doctor=$refer_doctor;

    }
    public function fileDestination(): array
    {
        return ['name'=>"media/patient/referrals/".$this->name];
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
        return ['referrel'=> ['doctor','appointment_ID','speciality','name','text','type','refer_doctor']];
    }

    public function attributes(): array
    {
        return  ['doctor','appointment_ID','speciality','name','text','type','refer_doctor'];
    }

    
}   