<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class AdminNotification extends DbModel{
    public string $noti_ID='';
    public string $doctor='';
    public string $content='';
    public string $created_date_time='';

   
  

  
    public function rules(): array
    {
        return [
            // 'name'=>[self::RULE_REQUIRED],
            //'nic'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>12],[self::RULE_MAX,'max'=>15],[self::RULE_UNIQUE,'attribute'=>'nic','tablename'=>'employee']],
            // 'age'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>120]],
            // 'contact'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>10]],
            // 'email'=>[self::RULE_EMAIL],
            // 'address'=>[],       
            // 'role'=>[self::RULE_REQUIRED],
            // 'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MATCH,'retype'=>($this->cpassword)]]
           


        ];
    }

    public function getNotification($type){
        return $this->fetchAssocAll(['type'=>$type]);
    }
   

    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'admin_notification';
    }
    public function primaryKey(): string
    {
        return 'noti_ID,doctor';
    }
    public function tableRecords(): array{
        return ['admin_notification'=>['noti_ID','doctor','content','created_date_time']];
    }

    public function attributes(): array
    {
        return ['noti_ID','doctor','content','created_date_time'];
    }

    
}   



?>