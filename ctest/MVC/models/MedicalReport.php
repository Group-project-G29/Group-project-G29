<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\PDF;
use app\core\UserModel;

class MedicalReport extends DbModel{
    public string $type = '';
    public string $name = '';
    public string $label = '';
    public string $patient='';
    public string  $doctor=''; 
    
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
        return 'medical_report';
    }
    public function primaryKey(): string
    {
        return 'report_ID';
    }
    public function tableRecords(): array{
        return ['medical_report'=> ['type','patient','doctor','name','label']];
    }

    public function attributes(): array
    {
        return  ['type','name','label','patient','doctor'];
    }
}