<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class PreChanneilngTestsValue extends DbModel{
    public string $appointment_ID="";
    public string $value="";
    public $test_ID=[];

    //set channeling test values
    public function addChannelingTestValues($values,$ids,$appointment_ID,){
        foreach($ids as $key=>$id):
            $test_ID = $id['test_ID'];
            $value = $values[$test_ID];
            $appointmentModel=new Appointment();
            $patient=$appointmentModel->fetchAssocAll(['appointment_ID'=>$appointment_ID])[0]['patient_ID'];
            $this->customFetchAll("INSERT INTO pre_channeling_tests_values (value,test_ID,appointment_ID,patient_ID) VALUES('$value','$test_ID','$appointment_ID',$patient)");
        endforeach;
        return 1;
    }
    
    public function getTestsByOp($channeling){
        return $this->customFetchAll("select DISTINCT t.name from pre_channeling_tests as t RIGHT join pre_channeilng_test_aloc as al on al.test_ID=t.test_ID left join channeling as c on c.channeling_ID=al.channeling_ID where c.channeling_ID=".$channeling);
    }

    //update channeling test values
    public function updateChannelingTestValues($values,$ids,$appointment_ID,){
        foreach($ids as $key=>$id):
            $test_ID = $id['test_ID'];
            $value = $values[$test_ID];
            $this->customFetchAll("UPDATE pre_channeling_tests_values SET value = $value WHERE appointment_ID=$appointment_ID AND test_ID=$test_ID;");
        endforeach;
        return 1;
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

