<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class PreChanneilngTestsValue extends DbModel{
    public string $appointment_ID="";
    public string $value="";
    public $test_ID=[];

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
        return 'pre_channeling_tests_values';
    }
    public function primaryKey(): string
    {
        return 'test_ID'+'appointment_ID';
    }
    public function tableRecords(): array{
        return ['pre_channeling_tests_values'=>['value','test_ID','appointment_ID']];
    }
    public function attributes(): array
    {
        return ['value','test_ID','appointment_ID'];
    }

    
}   

