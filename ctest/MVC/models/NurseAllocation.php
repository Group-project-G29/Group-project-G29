<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class NurseAllocation extends DbModel{
    public $emp_ID=[];
    public string $channeling_ID="";

    public function savedata(){
        return parent::save();
    }
    //set validation rule
    public function rules(): array
    {
        return [
            // 'doctor'=>[self::RULE_REQUIRED],
            // 'fee'=>[self::RULE_REQUIRED],
            // 'room'=>[self::RULE_REQUIRED],
            // 'contact'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>10]],
            // 'email'=>[self::RULE_EMAIL],
            // 'address'=>[],       
            // 'role'=>[self::RULE_REQUIRED],
            // 'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MATCH,'retype'=>($this->cpassword)]]
           


        ];
    }
    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'nurse_channeling_allocataion';
    }
    public function primaryKey(): string
    {
        return 'channeling_ID';
    }
    public function tableRecords(): array{
        return ['nurse_channeling_allocataion'=>['emp_ID','channeling_ID']];
    }
    public function attributes(): array
    {
        return ['emp_ID','channeling_ID'];
    }

    
}   

