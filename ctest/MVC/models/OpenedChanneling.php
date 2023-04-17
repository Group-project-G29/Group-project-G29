<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\Calendar;
use app\core\Date;
use app\core\UserModel;
use app\core\Time;

class OpenedChanneling extends DbModel{
    public string $channeling_ID='';
    public int $remaining_appointments=0;
    public ?string $channeling_date="";
    public string $status="";
   

    public function saveData(){
        return parent::save();
    }
    public function setter($channeling_ID,$rem_app,$date,$status){
        $this->channeling_ID=$channeling_ID;
        $this->remaining_appointments=$rem_app;
        $this->channeling_date=$date;
        $this->status=$status;
        

    }
    //get all the upcoming channelings
    public function getOpenedChannelings($id){
        return $this->customFetchAll("Select * from opened_channeling where channeling_ID=$id and channeling_date>='".date('Y-m-d')."'");
    }
    //close a opened channeling session
    public function closeChanneling($id){
        $this->customFetchAll("update opened_channeling set status='closed' where opened_channeling_ID=".$id);
    }
    //cancel a opened channeling session
    public function cancelChanneling($id){
        $this->customFetchAll("update opened_channeling set status=cancel where opened_channeling_ID=".$id);
    }
    public function isShow($opened_channeling_ID){
        
    }
    //check overlap
    public function generateOpenedChannelings($start_date,$startday,$finday,$duration,$op,$hop='0 weeks'){
        $calendarModel=new Calendar();
        $dates=$calendarModel->generateDays($start_date,$startday,$finday,$duration,$hop);
        foreach($dates as $date){
            $op->channeling_date=$date;
            $op->saveData();
        }
        return true;
    }
    public function getPatient($channeling,$current_patient,$type,$queuetype):array{
        $patient_queue=$this->customFetchAll("select * from  patient right join appointment on patient.patient_ID=appointment.patient_ID left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where opened_channeling.opened_channeling_ID =".$channeling." and appointment.type='$queuetype' order by appointment.queue_no");
        $i=0;
        

        while(isSet($patient_queue[$i]['patient_ID']) && $patient_queue[$i]['patient_ID']!=$current_patient){
            $i+=1;
             
        }
        
        
        
        if($type=="this"){
            return $patient_queue[$i]??[];
        }
        if($type=='next'){
           
            return $patient_queue[$i+1]??[];
            
        }
        else{
            if($i==0){
                return [];
            }
            return $patient_queue[$i-1]??[];
        }
        
    }

    public function getAPatient($channeling,$patient){
        $patient_queue=$this->customFetchAll("select * from  patient right join appointment on patient.patient_ID=appointment.patient_ID left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where opened_channeling.opened_channeling_ID =".$channeling." and patient.patient_ID=".$patient);
        if($patient_queue) return $patient_queue[0];
        else return false;
    }

    public function getAllAppointments($id){
        return $this->customFetchAll("select * from appointment left join patient on patient.patient_ID=appointment.patient_ID where appointment.opened_channeling_ID='$id'");
    }


    public function rules(): array
    {
        return [
            // 'name'=>[self::RULE_REQUIRED],
            'nic'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>12],[self::RULE_MAX,'max'=>15],[self::RULE_UNIQUE,'attribute'=>'nic','tablename'=>'employee']],
            // 'age'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>120]],
            // 'contact'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>10]],
            // 'email'=>[self::RULE_EMAIL],
            // 'address'=>[],       
            // 'role'=>[self::RULE_REQUIRED],
            // 'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MATCH,'retype'=>($this->cpassword)]]
           


        ];
    }
    
    public function fixAppointmentNumbers($opened_channeling_ID){
        $channelings=$this->customFetchAll("Select * from appointment where opened_channeling_ID=".$opened_channeling_ID." order by queue_no asc");
        $i=1;
        foreach($channelings as $channeling){
            $this->customFetchAll("update appointment set queue_no=".$i." where opened_channeling_ID=".$channeling['opened_channeling_ID']);
            $i++;
        }


    }

    public function increasePatientNumber($opened_channeling_ID){
        $currrent=$this->customFetchAll("Select remaining_appointments from opened_channeling where opened_channeling_ID=".$opened_channeling_ID);
        $new=number_format($currrent[0]['remaining_appointments'])+1;
        $this->customFetchAll("update opened_channeling set remaining_appointments=".$new." where opened_channeling_ID= ".$opened_channeling_ID);

    }

    public function decreasePatientNumber($opened_channeling_ID){
        $currrent=$this->customFetchAll("Select remaining_appointments from opened_channeling where opened_channeling_ID=".$opened_channeling_ID);
        $new=number_format($currrent[0]['remaining_appointments'])-1;
        $this->customFetchAll("update opened_channeling set remaining_appointments=".$new." where opened_channeling_ID= ".$opened_channeling_ID);

    }
    public function isPatientIn($patient,$channeling){
        if($this->customFetchAll("select * from patient left join appointment on appointment.patient_ID=patient.patient_ID left join   opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID where patient.patient_ID=".$patient." and opened_channeling.opened_channeling_ID=".$channeling)){
            return true;
        }
        else{
            return false;
        }

    }
    public function getLastPatient($type,$channelingID){
        $patient=$this->customFetchAll("select patient_ID from appointment where opened_channeling_ID=$channelingID and  queue_no in (select max(queue_no) from appointment where opened_channeling_ID=$channelingID and type='$type' and status='used' ) and status='used' ");
        if(isSet($patient[0]['patient_ID'])){
            return $patient[0]['patient_ID'];
        }
        else{
            return $this->customFetchAll("select patient_ID from appointment where opened_channeling_ID=$channelingID and queue_no in (select min(queue_no) from appointment where opened_channeling_ID=$channelingID and type='$type') and type='$type'")[0]['patient_ID']??'';
        }
    }

    public function isOpened($id){
        //models
        $dateModel=new Date();
        $timeModel=new Time();
        //get channeling date
        $channeling_date=$this->fetchAssocAll(['opened_channeling'=>$id])[0]['channeling_date'];
        //check if today date<channeling_date"in"
        $today=date('Y-m-d');
        if($dateModel->greaterthan($today,$channeling_date)){
            return true;
        }
        //if it is equal check whether channeling session is closed before site settings
        else if($channeling_date==$today){
        //channeling session should be closed  
            // $current_time=Time('');
            // $expire_time=$timeModel->addTime();
            return true;
        }
        else{
            return false;
        }
        

        //check if the patient are full
    }

    public function channelingsForDate($date, $eID){
        return $this->customFetchAll("SELECT * FROM `opened_channeling` INNER JOIN `channeling` ON opened_channeling.channeling_ID = channeling.channeling_ID INNER JOIN `employee` ON channeling.doctor = employee.nic INNER JOIN `nurse_channeling_allocataion` ON channeling.channeling_ID = nurse_channeling_allocataion.channeling_ID WHERE channeling_date = '$date' AND nurse_channeling_allocataion.emp_ID = $eID;");
    }

    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'opened_channeling';
    }
    public function primaryKey(): string
    {
        return 'opened_channeling_ID';
    }
    public function tableRecords(): array{
        return ['opened_channeling'=>['remaining_appointments','channeling_date','status','channeling_ID']];
    }

    public function attributes(): array
    {
        return ['remaining_appointments','channeling_date','status','channeling_ID'];
    }
    public function getFee($channeling){
        return $this->customFetchAll("Select channeling.fee from opened_channeling left join channeling on channeling.channeling_ID where opened_channeling.opened_channeling_ID=".$channeling)[0]['fee'];
    }
    public function finish($channeling){
        $patient_count=0+$this->customFetchAll("select count(appointment_ID) from appointment where type='consultation' and payment_status='done' and opened_channeling_ID=".$channeling)[0]['count(appointment_ID)'];
        $income=$patient_count*($this->getFee(Application::$app->session->get('channeling')));
        $free_count=0+$this->customFetchAll("select count(appointment_ID) from appointment where type='labtest' and payment_status='done' and opened_channeling_ID=".$channeling)[0]['count(appointment_ID)'];
        $past=new PastChanneling();
        $past->opened_channeling_ID=$channeling;
        $past->no_of_patient=$patient_count;
        $past->total_income=$income;
        $past->free_appointments=$free_count;
        var_dump($past);
        return $past->saveData();

    }
   
    
}   



?>