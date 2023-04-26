<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class LabTest extends DbModel{
    public string $name='';
    public int $test_fee=0;
    public int $hospital_fee=0;

    public ?int $template_ID=null;
    
   

    public function addTest(){
        return parent::save();
    }
 
    public function rules(): array
    {
        return [
            'name'=>[self::RULE_REQUIRED,[self::RULE_UNIQUE,'attribute'=>'name','tablename'=>'lab_tests']],
            'test_fee'=>[self::RULE_REQUIRED,self::RULE_NUMBERS,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>100000000000]],
            'hospital_fee'=>[self::RULE_REQUIRED,self::RULE_NUMBERS,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>100000000000]],
            'template_ID'=>[],



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
        return ['lab_tests'=> ['name','test_fee','hospital_fee','template_ID']];
    }

    public function attributes(): array
    {
        return  ['name','test_fee','hospital_fee','template_ID'];
    }
    public function getAllTests(){
        $array=$this->customFetchAll("select * from lab_tests");
        $return_result=[];
        foreach($array as $el){
            $return_result[$el['name']]=$el['name'];
            
        }
        return $return_result;
    }

    public function get_lab_tests() {
        return $this->customFetchAll("SELECT * FROM lab_tests");
    }

    
    
 

    public function updateLabtest($prev_name){
        $name = $this->name;
        $test_fee = $this->test_fee;
        $hospital_fee = $this->hospital_fee;
        $template_ID = $this->template_ID;
        if(!$template_ID){
            $template_ID="NULL";
        }

        return [$this->customFetchAll("UPDATE lab_tests SET  hospital_fee =$hospital_fee, test_fee =$test_fee, template_ID =$template_ID WHERE name ='$prev_name'"),$this->customFetchAll("UPDATE `lab_tests` SET `name` ='$name'  WHERE `lab_tests`.`name` = '$prev_name';")];
    }

    public function update_temp_ID_on_test($test_name, $temp_ID){
        return $this->customFetchAll("UPDATE lab_tests SET template_ID=$temp_ID where name='$test_name'");
    }

    public function get_prev_temp_ID(){
        return $this->customFetchAll("SELECT * from lab_report_template group by title ORDER BY template_ID DESC");
    }

    // public function get_last_test_name(){

    // }

    
}
