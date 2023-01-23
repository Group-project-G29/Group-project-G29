<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class ConsultationReport extends DbModel{
    public string $consultation='';
    public string $examination='';
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
        return 'consultation_report';
    }
    public function primaryKey(): string
    {
        return 'report_ID';
    }
    public function tableRecords(): array{
        return ['medical_report'=> ['type','name','label'],'consultation_report'=>['consultation','examination']];
    }

    public function attributes(): array
    {
        return  ['type','name','label','consultation','examintation'];
    }

    public function getReports($patient,$doctor):array
    {
        return $this->customFetchAll(" SELECT * from medical_report left join consultation_report on consultation_report.report_ID=medical_report.report_ID left join Medical_history on Medical_history.report_ID=medical_report.report_ID where medical_report.doctor=".$doctor." and medical_report.patient=".$patient);
    }
}   



?>