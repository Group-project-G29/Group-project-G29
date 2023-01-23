<?php
namespace app\models;

use app\core\Application;
use app\core\Model;
use app\models\Patient;

    class PatientLoginForm extends Model{
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
            var_dump($user);
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
     
     
    }


?>