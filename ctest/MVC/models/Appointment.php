<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\Calendar;
use app\core\Date;


class Appointment extends DbModel{
    public string $opened_channeling_ID='';
    public string $patient_ID='';
    public int $queue_no=0;
    public string $payment_status='';
    public ?string $type="";
    public ?string $status="unused";
  

  
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
        $this->status='unused';
        $result=parent::save();
        Application::$app->session->set('Appointment',$result[0]['last_insert_id()']);
        return $result;

    }

    public function cancelAppointment($id){ 
        $this->customFetchAll("delete from channeling where channeling_ID=459");
    }

    // public function completeAppointment($id){
    //     $this->customFetchAll("update appointment set status='completed' where appointment_ID=$id");
    // }

    
    public function labReportEligibility($patient,$doctor,$opened_channeling){
        $dateModel=new Date();
        $calendarModel=new Calendar();
        $channeling_date=$this->fetchAssocAllByName(['opened_channeling_ID'=>$opened_channeling],'opened_channeling')[0]['channeling_date'];
        //check all  the past channeling dates of the doctor where patient joined
        $last_appointment_date=$this->customFetchAll("select max(o.channeling_date)  from appointment as a right join  past_channeling as p on a.opened_channeling_ID=p.opened_channeling_ID left join opened_channeling as o on o.opened_channeling_ID=p.opened_channeling_ID left join channeling as c on c.channeling_ID=o.channeling_ID where c.doctor=$doctor and a.patient_ID=$patient")[0]['max(o.channeling_date)']??'';
        $l=$last_appointment_date;
        if($last_appointment_date=='') return false;
        //add two weeks
        $last_appointment_date=$calendarModel->addDaysToDate($last_appointment_date,14);
        //if channeling date<two week+patient last appointment reutrn true else false
        //if he already have labtest appointment return false
        $labtestAppointments=$this->customFetchAll("select *  from appointment as a right join  past_channeling as p on a.opened_channeling_ID=p.opened_channeling_ID left join opened_channeling as o on o.opened_channeling_ID=p.opened_channeling_ID left join channeling as c on c.channeling_ID=o.channeling_ID where a.type='labtest' and c.doctor=$doctor and a.patient_ID=$patient")[0]['max(o.channeling_date)']??'';
        if($labtestAppointments){
            return false;
        }
        
        else if($dateModel->greaterthan($last_appointment_date,$channeling_date)){
            return false;
        }
        else{
            return true;
        }

    }
    //check whether the appointment is valid
    public function isInPass($appointment){
        $result=$this->customFetchAll("Select * from opened_channeling left join appointment on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID where  (opened_channeling.status='Opened' or opened_channeling.status='started' ) and appointment.status='unused' and appointment.appointment_ID=".$appointment);
        if($result) return true;
        else return false;
    } 
   
    public function getAppointmentType($patientID,$channelingID){
        return $this->customFetchAll("select type from appointment where patient_ID=$patientID and opened_channeling_ID=$channelingID ");
    }

    public function getAppointment($patient,$opened_channeling_ID){
        return $this->fetchAssocAll(['patient_ID'=>$patient,'opened_channeling_ID'=>$opened_channeling_ID])[0]['appointment_ID'];
    }
    public function getAppointmentStatus($patient,$opened_channeling_ID){
        return $this->fetchAssocAll(['patient_ID'=>$patient,'opened_channeling_ID'=>$opened_channeling_ID])[0]['status'];

    }
    
    //check whether appointment is a used
    public function isUsed($id){
        $type=$this->customFetchAll("Select status from appoitment where appointment_ID=$id")[0]['status']??'';
        if($type=='used') return true;
        else return false;
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
        return ['appointment'=>['opened_channeling_ID','patient_ID','queue_no','payment_status','type','status']];
    }

    public function attributes(): array
    {
        return ['opened_channeling_ID','patient_ID','queue_no','payment_status','type','status'];
    }
     public function getTotoalPatient($channelingID){
        //take count in the database on appointment
        return $this->customFetchAll("select count(*) from appointment where opened_channeling_ID=".$channelingID)[0]['count(*)'];
    }
    public function getUsedPatient($channelingID){
        //take count in the database on appointment where status is not used
        return $this->customFetchAll("select count(*) from appointment where opened_channeling_ID=".$channelingID." and status='used'")[0]['count(*)'];

    }
    public function updateStatus($id,$status){
        $this->customFetchAll("update appointment set status='$status' where appointment_ID=".$id);
    }
    public function getAppointmentCount($opened_channeling){
        return $this->customFetchAll("select count(appointment_ID) from appointment where opened_channeling_ID= ".$opened_channeling)[0]['count(appointment_ID)'];
    }
    
}   



?>