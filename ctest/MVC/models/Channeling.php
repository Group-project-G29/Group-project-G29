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
    public  $extra_patients=0;
    public  $max_free_appointments=0;
    public string $day="";
    public string $time="";
    public ?string $start_date="";
    public  $count=0;
    public string $type='';
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
            'total_patients'=>[self::RULE_REQUIRED,self::RULE_NUMBERS],
            'extra_patients'=>[self::RULE_REQUIRED,self::RULE_NUMBERS],
            'max_free_appointments'=>[self::RULE_NUMBERS],       
            'day'=>[self::RULE_REQUIRED],
            'time'=>[self::RULE_REQUIRED],
            'start_date'=>[self::RULE_REQUIRED,self::RULE_DATE_VALIDATION],
            'count'=>[self::RULE_REQUIRED,self::RULE_NUMBERS],
            'type'=>[self::RULE_REQUIRED],
            'percentage'=>[self::RULE_REQUIRED,self::RULE_NUMBERS]

        ];
    }

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
        return ['channeling'=>['doctor','fee','total_patients','extra_patients','max_free_appointments','day','time','count','speciality','type','percentage','room','start_date']];
    }

    public function attributes(): array
    {
        return ['doctor','fee','total_patients','extra_patients','max_free_appointments','day','time','count','type','percentage','speciality','room','start_date'];
    }

    
}   



?>