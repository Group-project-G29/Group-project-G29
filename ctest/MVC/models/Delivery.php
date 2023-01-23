<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class Delivery extends DbModel{
    public string $delivery_id='';
    public string $order_id='';
    public string $rider_id='';
    public string $patient_id='';
    public string $contact='';
    public string $pin='';
    public string $postal_code='';
    public string $craeted_date='';
    public string $created_time='';
    public string $completed_date='';
    public string $completed_time='';
   
    public function addDelivery(){
        return parent::save();
    }
 
    public function rules(): array  //no need of this function here
    {
        return [
            'name'=>[self::RULE_REQUIRED],
            'brand'=>[self::RULE_REQUIRED],
            'strength'=>[self::RULE_REQUIRED],
            'category'=>[self::RULE_REQUIRED],
            'unit'=>[self::RULE_REQUIRED],
            'unit_price'=>[self::RULE_REQUIRED,self::RULE_NUMBERS],       
            'amount'=>[self::RULE_NUMBERS,self::RULE_NUMBERS],
        ];
    }

    public function fileDestination(): array    //no need of this function here
    {
        return ['img'=>"media/images/medicine/"];
    }
    public function tableName(): string
    {
        return 'delivery';
    }
    public function primaryKey(): string
    {
        return 'delivery_ID';
    }
    public function tableRecords(): array{
        return ['delivery'=> ['delivery_ID','contact','address','time_of_creation','delivery_rider', 'PIN', 'postal_code']];
    }

    public function attributes(): array
    {
        return  ['delivery_ID','contact','address','time_of_creation','delivery_rider', 'PIN', 'postal_code'];
    }

    
}   



?>