<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\Calendar;
use app\core\Date;
use app\core\UserModel;

class Appointment extends DbModel{
    public string $opened_channeling_ID='';
    public string $patient_ID='';
    public int $queue_no=0;
    public string $payment_status='';
    public ?string $type="";
    public ?string $status="";
  

  
    public function rules(): array
    {
        return [
            // 'name'=>[self::RULE_REQUIRED],
            //'nic'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>12],[self::RULE_MAX,'max'=>15],[self::RULE_UNIQUE,'attribute'=>'nic','tablename'=>'employee']],
            // 'age'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>120]],
            // 'contact'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>10]],
            // 'email'=>[self::RULE_EMAIL],
            // 'address'=>[],       
            // 'role'=>[self::RULE_REQUIRED],
            // 'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MATCH,'retype'=>($this->cpassword)]]
           


        ];
    }
    public function setAppointment($ap){
        $this->opened_channeling_ID=$ap[0];
        $this->patient_ID=$ap[1];
        $this->queue_no=$ap[2];
        $this->payment_status=$ap[3];
        $this->type=$ap[4];
        $this->status='new';
        return parent::save();

    }

    public function cancelAppointment($id){ 
        $this->customFetchAll("delete from channeling where channeling_ID=459");
    }

    public function completeAppointment($id){
        $this->customFetchAll("update appointment set status='completed' where appointment_ID=$id");
    }

    
    public function labReportEligibility($patient,$doctor,$opened_channeling){
        $dateModel=new Date();
        $calendarModel=new Calendar();
        $channeling_date=$this->fetchAssocAllByName(['opened_channeling_ID'=>$opened_channeling],'opened_channeling')[0]['channeling_date'];
        //check all  the past channeling dates of the doctor where patient joined
        $last_appointment_date=$this->customFetchAll("select max(channeling_date) from past_channeling_patient where doctor=$doctor and patient_ID=$patient")[0]['max(channeling_date)']??'';
        $l=$last_appointment_date;
        if($last_appointment_date=='') return false;
        //add two weeks
        $last_appointment_date=$calendarModel->addDaysToDate($last_appointment_date,14);
        //if channeling date<two week+patient last appointment reutrn true else false
        //if he already have labtest appointment return false
        if($dateModel->greaterthan($last_appointment_date,$channeling_date)){
            return false;
        }
        else{
            return true;
        }

    }

    
    public function getAppointmentType($patientID,$channelingID){
        return $this->customFetchAll("select type from appointment where patient_ID=$patientID and opened_channeling_ID=$channelingID ");
    }
    public function fileDestination(): array{
        return [];
    }
    public function tableName(): string
    {
        return 'appointment';
    }
    public function primaryKey(): string
    {
        return 'appointment_ID';
    }
    public function tableRecords(): array{
        return ['appointment'=>['opened_channeling_ID','patient_ID','queue_no','payment_status','type']];
    }

    public function attributes(): array
    {
        return ['opened_channeling_ID','patient_ID','queue_no','payment_status','type'];
    }
    
    
}   



?>