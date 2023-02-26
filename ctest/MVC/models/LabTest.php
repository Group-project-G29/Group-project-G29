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
    public function getAllTests(){
        $array=$this->customFetchAll("select * from lab_tests");
        $return_result=[];
        foreach($array as $el){
            $return_result[$el['name']]=$el['name'];
            
        }
        return $return_result;
    }
    
}   



?>