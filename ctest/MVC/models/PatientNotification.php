<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class PatientNotification extends DbModel{
    public ?string $type='';
    public ?string $text='';
    public ?string $patient_ID="";
    public ?string $order_ID="";
    public int $is_read=0;
   
  

  
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
   
    public function createNotifications($patient,$content,$type,$order){
        $this->type=$type;
        $this->text=$content;
        $this->patient_ID=$patient;
        $this->order_ID=$order;
        $this->is_read=0;
        return $this->save();

    }
    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'patient_notification';
    }
    public function primaryKey(): string
    {
        return 'noti_ID';
    }
    public function tableRecords(): array{
        return ['patient_notification'=>['noti_ID','type','patient_ID','text','is_read','order_ID']];
    }

    public function attributes(): array
    {
        return ['noti_ID','type','patient_ID','text','is_read','order_ID'];
    }

    
}   



?>