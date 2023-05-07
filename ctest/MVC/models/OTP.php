<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\Date;
use app\core\UserModel;
use app\core\Time;


class OTP extends DbModel{
    public ?int $patient_ID=null;
    public string $OTP='';
    public int $tries=0;
    public string $expire_time='';
    public ?int $emp_ID=null;
  
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
        return ['OTP'=>['patient_ID','OTP','tries','expire_time','emp_ID']];
    }

    public function attributes(): array
    {
        return ['patient_ID','OTP','tries','expire_time','emp_ID'];
    }
    public function createOTP(){
        return rand(10000,99999);
        
    }

    public function setOTP($ent_ID,$type='patient'){
        $otpModel=new OTP();
        $timeModel=new Time();
        $otpModel->deleteRecord(['emp_ID'=>$ent_ID]);
        if($type=='patient'){
            $otpModel->deleteRecord(['patient_ID'=>$ent_ID]);
            $this->patient_ID=$ent_ID;
        }   
        else {
            $this->emp_ID=$ent_ID;
            $otpModel->deleteRecord(['emp_ID'=>$ent_ID]);
        }
            
        $this->tries=0;
        
        $this->OTP=$otpModel->createOTP();
        $this->expire_time=$timeModel->addTime(Date('H:i:s'),"00:10");
        var_dump($this);
        parent::save();
        return $this->OTP;    
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

    public function canSend($entity,$type='patient'){
        if($type=='employee'){
           $time=new Time();
            date_default_timezone_set("Asia/Colombo");
            $otp=$this->customFetchAll("select OTP from OTP where emp_ID=$entity and created_date='".Date('Y-m-d')."' and expire_time>='".Date("H:i:s")."'");   
            if($otp) return $otp;
            else return false; 
        }
        $time=new Time();
        date_default_timezone_set("Asia/Colombo");
        $otp=$this->customFetchAll("select OTP from OTP where patient_ID=$entity and created_date='".Date('Y-m-d')."' and expire_time>='".Date("H:i:s")."'");   
        if($otp) return $otp;
        else return false;
    }
    public function changePassword($response,$type){
        if($type=='patient'){
            $model=new Patient();
        }
        else{
            $model=new Employee();
        }
        $password=$_POST['password'];
        $cpassword=$_POST['cpassword'];
        $model->password=$_POST['password'];
        $model->cpassword=$_POST['cpassword'];
        $model->validate();
        if(!$_POST['password']){
            $model->customAddError('password',"This field is required");
            return $model;
        }
        elseif(!$_POST['cpassword']){
            $model->customAddError('cpassword',"This field is required");
            return $model;
        }
        else if($password!=$cpassword){
            $model->customAddError('cpassword',"Password Mismatch");
            return $model;
        }
        else{
            
            if($type=='patient'){
                $model=$model->findOne(['patient_ID'=>Application::$app->session->get('temp_user')]);
                $model->password=password_hash($_POST['password'],PASSWORD_DEFAULT);
                if($model->updateRecord(['patient_ID'=>Application::$app->session->get('temp_user')])){
                    Application::$app->session->setFlash('success',"Password changed successfully,Please login to continue");
                    $response->redirect("/ctest/");
                    exit;
                }

            }
            else{
                $model=$model->findOne(['emp_ID'=>Application::$app->session->get('temp_user')]);
                $model->password=password_hash($_POST['password'],PASSWORD_DEFAULT);
                if($model->save()){
                    Application::$app->session->setFlash('success',"Password changed successfully,Please login to continue");
                    $response->redirect("login");
                    exit;
                }

            }
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