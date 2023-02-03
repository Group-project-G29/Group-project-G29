<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class LabTest extends DbModel{
    public string $name='';
    public int $fee=0;
   
    public function addTest(){
        parent::save();
    }
 
    public function rules(): array
    {
        return [
            'name'=>[self::RULE_REQUIRED],
            'fee'=>[self::RULE_REQUIRED]


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
        return ['lab_tests'=> ['name','fee']];
    }

    public function attributes(): array
    {
        return  ['name','fee'];
    }

    
}   



?>