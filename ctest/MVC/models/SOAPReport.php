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
    public string $patient="";
    public string $doctor="";
    
    public function setReport($type,$label,$patient,$doctor,$name='e-report'){
        $this->type = $type;
        $this->name = $name;
        $this->label = $label;
        $this->patient=$patient;
        $this->doctor=$doctor;
     }
    public function addReport(){
        return parent::save();
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
        return ['medical_report'=> ['type','name','label','patient','doctor'],'soap_report'=>['subjective','objective','assessment','plan','additional_note']];
    }

    public function attributes(): array
    {
        return  ['type','name','label','subjective','objective','assessment','doctor','patient','plan','additional_note'];
    }

    public function getMedicalReports(){
        $patient=Application::$app->session->get('user');
        return $this->customFetchAll("Select * from medical_report where patient=".$patient);
    }   
}   



?>