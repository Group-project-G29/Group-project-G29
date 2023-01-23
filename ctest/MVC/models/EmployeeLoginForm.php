<?php
    namespace app\models;

use app\core\Application;
use app\core\Model;

    class EmployeeLoginForm extends Model{
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
            $employee=new Employee();
            $user=$employee->findOne(['email'=>$this->username]);
            if(!$user){
                $this->customAddError('username','User does not exist with this email address');
                return false;
            }
            if(!password_verify($this->password,$user->password)){
                $this->customAddError('password','Password is incorrect');
                return false;
            }
           Application::$app->login($user,'Employee');
            return true;
        }
     
     
    }


?>