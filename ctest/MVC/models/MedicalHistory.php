<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class MedicalHistory extends DbModel{
    public string $medication='';
    public string $allergies='';
    public string $note = "";
    public string $type = '';
    public string $name = '';
    public string $label = '';
    
    public function setReport($type,$name,$label){
        $this->type = $type;
        $this->name = $name;
        $this->label = $label;
    }
    public function addReport(){
        parent::save();
    }
 
    public function rules(): array
    {
        return [];
    }
    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'Medical_history';
    }
    public function primaryKey(): string
    {
        return 'report_ID';
    }
    public function tableRecords(): array{
        return ['medical_report'=> ['type','name','label'],'Medical_history'=>['allergies','medication','note']];
    }

    public function attributes(): array
    {
        return  ['type','name','label','allergies','medication','note'];
    }

    
}   



?>