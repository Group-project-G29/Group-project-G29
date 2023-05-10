<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class LabContentAllocation extends DbModel{
    public int $report_ID;
    public int $content_ID;
    public ?string $location='';
    public ?string $int_value='';
    public ?string $text_value='';
    
   

    public function addContentResult(){
        parent::save();
    }
 
    public function rules(): array
    {
        return [
            'report_ID'=>[self::RULE_REQUIRED],
            'content_ID'=>[self::RULE_REQUIRED],
            'location'=>[],
            'int_value'=>[self::RULE_NUMBERS],
            'text_value'=>[]



        ];
    }
    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'lab_report_content_allocation';
    }
    public function primaryKey(): string
    {
        return 'report_ID';
    }
    public function tableRecords(): array{
        return ['lab_report_content_allocation'=> ['report_ID','content_ID','location','int_value','text_value']];
    }

    public function attributes(): array
    {
        return  ['report_ID','content_ID','location','int_value','text_value'];
    }

    public function get_types($content_ID)
    {
        return $this->customFetchAll("SELECT type from lab_report_content where content_ID=$content_ID");

        // if ( $type[0] === 'field' ){
        //     return 'field';
        // } elseif ( $type[0] === 'text' ){
        //     return 'text';
        // } elseif ( $type[0] === 'image' ){
        //     return 'image';
        // }
    }
    public function add_field_allocation($report_ID,$content_ID,$int_value){
        return $this->customFetchAll("INSERT INTO lab_report_content_allocation (report_ID, content_ID, location, int_value, text_value) VALUE ('$report_ID', '$content_ID', NULL, '$int_value', NULL)");

    }
    public function add_text_allocation($report_ID,$content_ID,$int_value){
        return $this->customFetchAll("INSERT INTO lab_report_content_allocation (report_ID, content_ID, location, int_value, text_value) VALUE ('$report_ID', '$content_ID', NULL, '$int_value',NULL)");

    }
    public function add_image_allocation($report_ID,$content_ID,$location){
        return $this->customFetchAll("INSERT INTO lab_report_content_allocation (report_ID, content_ID, location, int_value, text_value) VALUE ('$report_ID', '$content_ID', '$location', NULL, NULL)");

    }
    
    
}   



?>