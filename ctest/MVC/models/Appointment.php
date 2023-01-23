<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class Appointment extends DbModel{
    public string $opened_channeling_ID='';
    public string $patient_ID='';
    public int $queue_no=0;
    public string $payment_status='';
   
  

  
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
    public function setAppointment($ap){
        $this->opened_channeling_ID=$ap[0];
        $this->patient_ID=$ap[1];
        $this->queue_no=$ap[2];
        $this->payment_status=$ap[3];
        return parent::save();

    }
    public function cancelAppointment($id){ 
        $this->customFetchAll("delete from channeling where channeling_ID=459");
    }

   

    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'appointment';
    }
    public function primaryKey(): string
    {
        return 'appointment_ID';
    }
    public function tableRecords(): array{
        return ['appointment'=>['opened_channeling_ID','patient_ID','queue_no','payment_status']];
    }

    public function attributes(): array
    {
        return ['opened_channeling_ID','patient_ID','queue_no','payment_status'];
    }

    
}   



?>