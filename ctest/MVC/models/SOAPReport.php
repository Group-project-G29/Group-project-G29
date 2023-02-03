<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class SOAPReport extends DbModel{
    public string $subjective='';
    public string $objective='';
    public string $assessment = "";
    public string $plan = '';
    public string $name = 'e-report';
    public string $additional_note = '';
    public string $type="";
    public string $label="";
    
    public function setReport($type,$label,$name='e-report'){
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
        return 'soap_report';
    }
    public function primaryKey(): string
    {
        return 'report_ID';
    }
    public function tableRecords(): array{
        return ['medical_report'=> ['type','name','label'],'soap_report'=>['subjective','objective','assessment','plan','additional_note']];
    }

    public function attributes(): array
    {
        return  ['type','name','label','subjective','objective','assessment','plan','additional_note'];
    }

    
}   



?>