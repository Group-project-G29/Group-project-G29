<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\Calendar;
use app\core\UserModel;
use app\core\Time;

class Channeling extends DbModel{
    public ?string $channeling_ID='';
    public ?string $doctor='';
    public ?string $speciality='';
    public  $fee=0;
    public ?string $room='';
    public  $total_patients=0;
    public ?string $day="";
    public ?string $time="";
    public ?string $start_date="";
    public  $schedule_for=0;
    public ?string $schedule_type='';
    public  $frequency=1;
    public ?string $frequency_type='weeks';
    public  $percentage=0;
    public ?int $open_before=14;
    public ?string $session_duration='';
    

    public function savedata(){
        return parent::save();
    }
    //set validation rule
    public function rules(): array
    {
        return [
            'doctor'=>[self::RULE_REQUIRED],
            'speciality'=>[self::RULE_REQUIRED],
            'fee'=>[self::RULE_REQUIRED,self::RULE_NUMBERS],
            'room'=>[self::RULE_REQUIRED],      
            'day'=>[self::RULE_REQUIRED],
            'time'=>[self::RULE_REQUIRED],
            'start_date'=>[self::RULE_REQUIRED,self::RULE_DATE_VALIDATION],
            'schedule_for'=>[self::RULE_REQUIRED,self::RULE_NUMBERS],
            'schedule_type'=>[self::RULE_REQUIRED],
            'frequency'=>[self::RULE_REQUIRED,self::RULE_NUMBERS],
            'frequency_type'=>[self::RULE_REQUIRED],
            'percentage'=>[self::RULE_REQUIRED,self::RULE_NUMBERS],
            'open_before'=>[self::RULE_REQUIRED,self::RULE_NUMBERS],
            'session_duration'=>[self::RULE_REQUIRED]

        ];
    }

    // public function remAppointment(){
    //     return $total_patients;
    // }

    public function timeCheckOverlap($date){
        $timeModel=new Time();
        
        $channelings=$this->customFetchAll("select * from opened_channeling left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where doctor='".$this->doctor."' and channeling_date='".$date."'");
    
        foreach($channelings as $key=>$channeling ){
            if($this->time){
                $ses_s='';
                if($channeling['session_duration']) $ses_s='06:00';
                else $ses_s=$channeling['session_duration'];
                if($timeModel->isInRange($this->time,$ses_s,$channeling['time'])){
                    return [true,$channeling['time']];
                }
            }
            else{
                return ['required'];
            }
        }
        return [false];
    }
    public function checkOverlap(){
        if(!$this->validate()){
            return ['validation'];
        }
        $openedchannelingModel=new OpenedChanneling();
        $calendarModel=new Calendar();
        $frequency=$this->frequency." ".$this->frequency_type;
        $duration=$this->schedule_for." ".$this->schedule_type;
        $dates=$calendarModel->generateDays($this->start_date,$this->day,date('l', strtotime($this->start_date)),$duration,$frequency);
        foreach($dates as $date){
            $result=$this->timeCheckOverlap($date);
            if(isset($result[0]) && $result[0]=='required'){
                $this->customAddError('time',"Time field is empty");
                return ['required'];
            }
            else if(isset($result[0]) && $result[0]){
                $this->customAddError('time',"Time overlap with ".$result[1]." channeling session"."<a href='#'>See Channeling Timetable</a>");
                return $result;
            }
        }
        
        return [false];
    }
    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'channeling';
    }
    public function primaryKey(): string
    {
        return 'channeling_ID';
    }
    
    public function tableRecords(): array{
        return ['channeling'=>['doctor','fee','total_patients','day','time','schedule_for','speciality','schedule_type','percentage','room','start_date', 'frequency', 'frequency_type','open_before','session_duration']];
    }

    public function attributes(): array
    {
        return ['doctor','fee','total_patients','day','time','schedule_for','schedule_type','percentage','speciality','room','start_date', 'frequency', 'frequency_type','open_before','session_duration'];
    }
    public function getSpecialities(){
        $specialities=$this->customFetchAll("Select  distinct speciality from channeling ");
        $speciality=['select'=>''];
        foreach($specialities as $row){
           $speciality[$row['speciality']]=$row['speciality'];
       }
       return $speciality;
       
   }
   public function getDocChannelings(){
        $doctor=Application::$app->session->get('userObject')->nic;
        return $this->fetchAssocAll(['doctor'=>$doctor]);

   }
    
}   



?>

