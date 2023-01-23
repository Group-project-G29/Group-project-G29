<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class Order extends DbModel{
    public string $id='';
    public string $date='';
    public string $time='10.00 am';
    public string $status='';
    public string $patient_id='A';
    public string $cart_id='';
    public string $img='';
   
    public function addOrder(){
        return parent::save();
    }
 
    public function rules(): array
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
        return ['img'=>"media/images/medicine/".$this->img];
    }
    public function tableName(): string
    {
        return '_order';
    }
    public function primaryKey(): string
    {
        return 'order_ID';
    }
    public function tableRecords(): array{
        return ['_order'=> ['order_ID','date','status','patient_ID','cart_ID']];
    }

    public function attributes(): array
    {
        return  ['order_ID','date','status','patient_ID','cart_ID'];
    }

    
}   



?>