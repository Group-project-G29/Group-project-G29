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
    
    public function setter(){
        $this->speciality=$_POST['speciality'];
        $this->fee=$_POST['fee'];
        $this->room=$_POST['room'];
        $this->total_patients=$_POST['total_patient'];
        $this->percentage=$_POST['precentage'];

    }
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
        $channelings=$this->customFetchAll("select * from opened_channeling left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where channeling.doctor='".$this->doctor."' and opened_channeling.channeling_date='".$date."'");
        foreach($channelings as $key=>$channeling ){
            if($this->time){
                $ses_s='';
                if($channeling['session_duration']) $ses_s=$channeling['session_duration'];
                else $ses_s=$channeling['session_duration'];
                if($timeModel->isInRange($channeling['time'],$ses_s,$this->time)){
            
                    return [true,$channeling];
                    
                    
                }
            }
            else{
                return [false,'required'];
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
        $dates=$calendarModel->generateDays($this->start_date,date('l', strtotime($this->start_date)),$this->day,$duration,$frequency);
    
        $array=[];
        foreach($dates as $date){
            $result=$this->timeCheckOverlap($date);
            if(isset($result[1]) && !$result[0]){
                $this->customAddError('time',"Time field is empty");
                return ['required'];
            }
            if(isset($result[0]) && $result[0]){
            
                array_push($array,$result[1]);
            }
        }

        if($array){
            if($array[0]==$array[count($array)-1]){
                $this->customAddError('time',"Time overlap :".$array[0]['channeling_ID']." at ".$array[0]['time']);
                return $array;
            }
            else{
                $this->customAddError('time',"Time overlap :".$array[0]['channeling_ID']." at ".$array[0]['time']." and ".$array[1]['channeling_ID']." at ".$array[1]['time']);
                return $array;
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
   
   public function checkNurseOverlap($nurses,$channelingModel){
        
        $timeModel=new Time();
        $results=[];
        $calendarModel=new Calendar();
        $dates=$calendarModel->generateDays($channelingModel->start_date,date('l',strtotime($channelingModel->start_date)),$channelingModel->day,$channelingModel->schedule_for." ".$channelingModel->schedule_type,$channelingModel->frequency." ".$channelingModel->frequency_type);
        //nurses is an array of nusrse employee ids
        foreach($nurses as $nurse){
            //get all the channeling nurse is allocated to
            $channelings=$channelingModel->customFetchAll("select * from  nurse_channeling_allocataion left join channeling on channeling.channeling_ID=nurse_channeling_allocataion.channeling_ID left join employee on employee.emp_ID=nurse_channeling_allocataion.emp_ID where employee.emp_ID=".$nurse);
            foreach($channelings as $channeling){
                foreach($dates as $date){
                    $confilict_channelings=$this->customFetchAll("select * from  channeling right join opened_channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join nurse_channeling_allocataion on channeling.channeling_ID=nurse_channeling_allocataion.channeling_ID left join employee on employee.emp_ID=nurse_channeling_allocataion.emp_ID where nurse_channeling_allocataion.emp_ID=$nurse  and opened_channeling.channeling_date='".$date."' and (opened_channeling.status='Opened' or opened_channeling.status='started' ) and channeling.channeling_ID=".$channeling['channeling_ID'].(($channelingModel->channeling_ID!='')?(" and channeling.channeling_ID<>".$channelingModel->channeling_ID):''));
                    if($confilict_channelings){
                        foreach($confilict_channelings as $conflict){
                            $check_time=$channelingModel->time;
                            $start_time=$conflict['time']; 
                            $result=$timeModel->isInRange($start_time,substr($conflict['session_duration'],0,5),$check_time);
                            if($result) array_push($results,$conflict);
                        }
                    }
                }
            }

        }
        if($results) return $results;
        else return false;
   }
   public function checkRoomOverlap($room,$channelingModel){
        $timeModel=new Time();
        $results=[];
        $calendarModel=new Calendar();
        $dates=$calendarModel->generateDays($channelingModel->start_date,date('l',strtotime($channelingModel->start_date)),$channelingModel->day,$channelingModel->schedule_for." ".$channelingModel->schedule_type,$channelingModel->frequency." ".$channelingModel->frequency_type);
        $channelings=$channelingModel->customFetchAll("select * from  channeling right join opened_channeling on channeling.channeling_ID=opened_channeling.channeling_ID where  room='".$room."'");
            foreach($channelings as $channeling){
                foreach($dates as $date){
                    $confilict_channelings=$this->customFetchAll("select * from  channeling right join opened_channeling on channeling.channeling_ID=opened_channeling.channeling_ID where channeling.room='$room' and  (opened_channeling.status='Opened' or opened_channeling.status='started' ) and opened_channeling.channeling_date='".$date."' and channeling.channeling_ID=".$channeling['channeling_ID'].(($channelingModel->channeling_ID!='')?(" and channeling.channeling_ID<>".$channelingModel->channeling_ID):''));
                    exit;
                    if($confilict_channelings){
                        foreach($confilict_channelings as $conflict){
                            $check_time=$channelingModel->time;
                            $start_time=$conflict['time']; 
                            $result=$timeModel->isInRange($start_time,substr($conflict['session_duration'],0,5),$check_time);
                            if($result) array_push($results,$conflict);
                        }
                    }
                }
            }

            if($results) return $results;
            else return false;
   }

   

   
   

   public function setChannelingClose($channeling){
       //get information on channeling
        $channeling=$this->fetchAssocAll(['channeling_ID'=>$channeling]);
        $channelingModel=new Channeling();
        $calendarModel=new Calendar();
        //to send notification to patient
        $patientNotificationModel=new PatientNotification();   
        //get all the opened channeling between starting date and open before
        $starting_date=$channeling['start_date'];
        $end_date=$calendarModel->addDaysToDate($starting_date,$channeling['open_before']);
        $opened_channelings=$this->customFetchAll("select * from opened_channeling left join channeling channeling.channeling_ID=opened_channeling.channeling_ID where opened_channeling.channeling_date>=".$starting_date." and opened_channeling.channeling_date<=".$end_date);
        //use foreach loop and set status
        foreach($opened_channelings as $op){
            $channelingModel->customFetchAll("update opened_channeling set status='close' where opened_channeling=".$op['opened_channeling']);    
            $patientNotificationModel->channelingCancelNoti($op['opened_channeling_ID']);
        }
   }
   public function setAllChannelingClose($channeling){
        $this->customFetchAll("update opened_channeling set status='close' where channeling_ID=".$channeling);
   }
   public function cancelOpenedChanneling($openedChanneling){
        $patientNotificationModel=new PatientNotification();
        $channelingModel=new Channeling();
        $appointmentModel=new Appointment();
        $channelingModel->customFetchAll("update opened_channeling set status='cancelled' where opened_channeling_ID=".$openedChanneling);    
        $patientNotificationModel->channelingCancelNoti($openedChanneling);
        //delete all the appointment
        $appointmentModel->deleteRecord(['opened_channeling_ID'=>$openedChanneling]);
        
   }
    public function closeOpenedChanneling($openedChanneling){
        $channelingModel=new Channeling();
        $channelingModel->customFetchAll("update opened_channeling set status='closed' where opened_channeling_ID=".$openedChanneling);    
        
   }
    public function openOpenedChanneling($openedChanneling){
        $channelingModel=new Channeling();
        $channelingModel->customFetchAll("update opened_channeling set status='Opened' where opened_channeling_ID=".$openedChanneling);    
        
   }

   public function cancelChanneling($channeling){
        $openedChanneling=new OpenedChanneling();
        $channelings=$openedChanneling->fetchAssocAll(['channeling_ID'=>$channeling]);
        foreach($channelings as $channeling){
            $this->cancelChanneling($channeling['opened_channeling_ID']);    
        }

   }
   //get opened channeling session when the channeling is given
   public function getOpenedChannelings($channeling){
        $channelingModel=new Channeling();
        $channelings=$channelingModel->fetchAssocAll(['channeling_ID'=>$channeling])[0];
        $calendarModel=new Calendar();
        $end_date=$calendarModel->addDaysToDate(Date('Y-m-d'),$channelings['open_before']);
        return $this->customFetchAll("select * from opened_channeling where channeling_date<='$end_date' and channeling_ID=$channeling and channeling_date>='".Date('Y-m-d')."'");
        
   }

   public function updateChannelingRecord($channeling){
        $speciality=$_POST['speciality'];
        $fee=$_POST['fee'];
        $room=$_POST['room'];
        $total_patients=$_POST['total_patients'];
        $percentage=$_POST['percentage'];
        $this->customFetchAll("update channeling set speciality='$speciality',fee=$fee,room='$room',total_patients=$total_patients,percentage=$percentage where channeling_ID=".$channeling);
        return true;
   }

}   



?>

