<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;
use app\core\Time;

class Channeling extends DbModel{
    public string $channeling_ID='';
    public string $doctor='';
    public string $speciality='';
    public  $fee=0;
    public string $room='';
    public  $total_patients=0;
    public string $day="";
    public string $time="";
    public ?string $start_date="";
    public  $schedule_for=0;
    public string $schedule_type='';
    public  $frequency=0;
    public string $frequency_type='';
    public  $percentage=0;
    

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
            'percentage'=>[self::RULE_REQUIRED,self::RULE_NUMBERS]

        ];
    }

    // public function remAppointment(){
    //     return $total_patients;
    // }

    public function checkOverlap(){
        $channelings=$this->customFetchAll("select * from channeling where doctor='".$this->doctor."' and day='".$this->day."'");
        $timeModel=new Time();
        foreach($channelings as $key=>$channeling ){
            if($timeModel->isInRange($this->time,"06:00",$channeling['time'])){
                return [true,$channeling['time']];
            }
            return [false];
        }
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
        return ['channeling'=>['doctor','fee','total_patients','day','time','schedule_for','speciality','schedule_type','percentage','room','start_date', 'frequency', 'frequency_type']];
    }

    public function attributes(): array
    {
        return ['doctor','fee','total_patients','day','time','schedule_for','schedule_type','percentage','speciality','room','start_date', 'frequency', 'frequency_type'];
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
        return $this->customFetchAll("select * from channeling where doctor=$doctor");

   }
    
}   



?>

