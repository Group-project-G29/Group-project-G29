<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\Date;
use app\core\UserModel;
use app\core\Time;


class OTP extends DbModel{
    public string $patient_ID='';
    public string $OTP='';
    public string $created_date='';
    public string $created_time="";
    public int $tries=0;
  
    public function rules():array{
        return [];
    }
    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'OTP';
    }
    public function primaryKey(): string
    {
        return 'otp_ID';
    }
    public function tableRecords(): array{
        return ['OTP'=>['patient_ID','OTP','created_date','created_time','tries']];
    }

    public function attributes(): array
    {
        return ['patient_ID','OTP','created_date','created_time','tries'];
    }
    public function createOTP(){
        return rand(10000,99999);
        
    }

    public function setOTP($patient_ID){
        $otpModel=new OTP();
        $this->patient_ID=$patient_ID;
        $this->tries=0;
        $this->OTP=$otpModel->createOTP();
        return parent::save();    
    }
    public function getPatientOTP($patient_ID){
        $timeModel=new Time();
        date_default_timezone_set("Asia/Colombo");
        $cdate=$this->customFetchAll("select * from OTP where created_time in (select max(created_time) from OTP where patient_ID=$patient_ID and created_date=".Date('Y-m-d').") and  created_date=".Date('Y-m-d'));
        $otp_date=$timeModel->isInRange(substr($cdate[0]['created_time'],0,5),'00:05',Date('H:i'));
        return $otp_date;
    }
    public function incrementTries($patient){
        date_default_timezone_set("Asia/Colombo");
        if($this->getPatientOTP($patient)){
            $cdate=$this->customFetchAll("select * from OTP where created_time in (select max(created_time) from OTP where patient_ID=$patient and created_date=".Date('Y-m-d').") and  created_date=".Date('Y-m-d'));
            $this->customFetchAll("update OTP set tries=".(($cdate[0]['tries'])+1)." where otp_ID=".$cdate[0]['otp_ID']);
        }
        
    }

    public function overTry($patient){
        date_default_timezone_set("Asia/Colombo");
        $cdate=$this->customFetchAll("select * from OTP where created_time in (select max(created_time) from OTP where patient_ID=$patient and created_date=".Date('Y-m-d').") and  created_date=".Date('Y-m-d'));    
        if($cdate[0]['tries']==3){
            return true;
        }
        return false;
    }

    public function canSend($patient){
        $time=new Time();
        date_default_timezone_set("Asia/Colombo");
        $cdate=$this->customFetchAll("select count(otp_ID) from OTP where patient_ID=$patient and created_time>=".Date("H:i:s")." and created_time<=".$time->addTime(Date("H:i:s"),'00:20'))[0]['count(otp_ID)'];   
        if($cdate==3){
            return false;
        }
        else{
            return true;
        }
    }
    public function checkOTP($patient){
        $otpModel=new OTP();
        $n1=$_POST['n1'];
        $n2=$_POST['n2'];
        $n3=$_POST['n3'];
        $n4=$_POST['n4'];
        $n5=$_POST['n5'];
        $otp=$n1.$n2.$n3.$n4.$n5;
        if($this->getPatientOTP(Application::$app->session->get('temp_user'))){
            date_default_timezone_set("Asia/Colombo");
            $cdate=$this->customFetchAll("select * from OTP where created_time in (select max(created_time) from OTP where patient_ID=$patient and created_date=".Date('Y-m-d').") and  created_date=".Date('Y-m-d'));
            if($otp==$cdate['OTP']){
                $otpModel->deleteRecord(['otp_ID'=>$cdate['otp_ID']]);
                return true;
            }
        }
        return false;
    }   
}   



?>