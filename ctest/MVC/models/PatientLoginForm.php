<?php
namespace app\models;

use app\core\Application;
use app\core\Model;
use app\models\Patient;

    class PatientLoginForm extends Model{
        public string $name='';
        public string $username='';
        public string $password='';
        public function rules(): array
        {
            return [
                'username'=>[self::RULE_REQUIRED],
                'password'=>[self::RULE_REQUIRED]   
            ];
        }
        public function login(){
            $patient=new Patient();
            $user=$patient->findOne(['nic'=>$this->username]);
            if(!$user){
                $this->customAddError('username','User does not exist with this email address');
                return false;
            }
            if(!password_verify($this->password,$user->password)){
                $this->customAddError('password','Password is incorrect');
                return false;
            }
           Application::$app->login($user,'patient');
            return true;
        }
        public function loginpediatric(){
            $patient=new Patient();
            $name=explode(" ",$_POST['name']);
            $user=$patient->findOne(['nic'=>$this->username,'name'=>$name[0]." ".$name[1]]);
            if(!$user){
                $this->customAddError('username','User does not exist with this  NIC and name');
                return false;
            }
            if(!password_verify($this->password,$user->password)){
                $this->customAddError('password','Password is incorrect');
                return false;
            }
           Application::$app->login($user,'patient');
            return true;
        }
     
     
    }


?>