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