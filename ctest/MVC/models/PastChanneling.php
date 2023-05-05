<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\Calendar;
use app\core\Date;
use app\core\UserModel;
use app\core\Time;

class PastChanneling extends DbModel{
    public string $opened_channeling_ID='';
    public int $no_of_patient=0;
    public int $total_income=0;
    public int $free_appointments=0;
    public int $doctor_income=0;
    public int $center_income=0;
    
   

    public function saveData(){
        return parent::save();
    }
    public function rules():array{
        return [];
    }
   

    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'past_channeling';
    }
    public function primaryKey(): string
    {
        return 'past_channeling_ID';
    }
    public function tableRecords(): array{
        return ['past_channeling'=>['free_appointments','opened_channeling_ID','no_of_patient','total_income','doctor_income','center_income']];
    }

    public function attributes(): array
    {
        return ['free_appointments','opened_channeling_ID','no_of_patient','total_income','doctor_income','center_income'];
    }

   
    
}   



?>