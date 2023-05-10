<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class LabReporta extends DbModel{
    // public int $report_ID;
    public int $fee=0;
    // public $upload_date_time;
    public string $type='';
    public string $label='';
    public int $Template_ID;
    public string $location='';
    
    public int $request_ID;
   

    public function addReport(){
        parent::save();
    }
 
    public function rules(): array
    {
        return [
            'report_ID'=>[self::RULE_REQUIRED],
            'fee'=>[self::RULE_REQUIRED,self::RULE_NUMBERS,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>100000000000]],
            'type'=>[self::RULE_REQUIRED],
            'label'=>[self::RULE_REQUIRED],
            'upload_date_time'=>[],
            'Template_ID'=>[self::RULE_REQUIRED],
            'location'=>[self::RULE_REQUIRED]



        ];
    }
    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'lab_report';
    }
    public function primaryKey(): string
    {
        return 'report_ID';
    }
    public function tableRecords(): array{
        return ['lab_tests'=> ['report_ID','fee','type','label','upload_date_time','Template_ID','location', 'request_ID']];
    }

    public function attributes(): array
    {
        return  ['report_ID','fee','type','label','upload_date_time','Template_ID','location', 'request_ID'];
    }

    public function get_report_by_ID($request_ID){
        return $this->customFetchAll(" SELECT * FROM lab_report where request_ID=$request_ID");

    } 
    public function create_new_report($fee, $type, $label, $template_ID, $location, $request_ID){
        return $this->customFetchAll("INSERT INTO lab_report ( fee, upload_date_time, type,label,template_ID,location,request_ID) VALUES ( $fee,current_timestamp(), '$type', '$label', $template_ID, '$location', $request_ID); ");
    }
}   



?>