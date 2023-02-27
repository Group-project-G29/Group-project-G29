<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class LabTest extends DbModel{
    public string $name='';
    public int $test_fee=0;
    public int $hospital_fee=0;

    public int $template_ID;
   

    public function addTest(){
        parent::save();
    }
 
    public function rules(): array
    {
        return [
            'name'=>[self::RULE_REQUIRED],
            'test_fee'=>[self::RULE_REQUIRED],
            'hospital_fee'=>[self::RULE_REQUIRED],
            'template_ID'=>[self::RULE_REQUIRED],



        ];
    }
    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'lab_tests';
    }
    public function primaryKey(): string
    {
        return 'name';
    }
    public function tableRecords(): array{
        return ['lab_tests'=> ['name','fee','test_fee','hospital_fee','template_ID']];
    }

    public function attributes(): array
    {
        return  ['name','fee','test_fee','hospital_fee','template_ID'];
    }

    
}   



?>