<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class TemplateContent extends DbModel{
    public ?string $name=null;
    public ?string $reference_ranges=null;
    public ?string $position=null;
    public string $type='';
    public ?string $metric=null;
  

   
    public string $template_ID='';
    public function addContent(){
       return parent::save();  
    }
 
    public function rules(): array
    {
        return [
           
            'name'=>[],
            'reference_ranges'=>[],
            'position'=>[],
            'type'=>[],
            'metric'=>[],
            'template_ID'=>[],


        ];
    }
    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'lab_report_content';
    }
    public function primaryKey(): string
    {
        return 'content_ID';
    }
    public function tableRecords(): array{
        return ['lab_report_content'=> [ 'name', 'reference_ranges', 'position', 'type', 'metric', 'template_ID']];
    }

    public function attributes(): array
    {
        return  [ 'name', 'reference_ranges', 'position', 'type', 'metric', 'template_ID'];
    }

    public function select_last_ID() {
        return $this->customFetchAll(" SELECT * FROM lab_report_template ORDER BY template_ID DESC;");
    }

    public function add_text_type( $name, $template_ID ) {
         return $this->customFetchAll("INSERT INTO lab_report_content (name, reference_ranges, position, type, metric, template_ID) VALUES ( '$name', NULL, NULL, 'text', NULL, '$template_ID');");
    }

    public function add_image_type(  $name,$position, $template_ID ) {
        return $this->customFetchAll("INSERT INTO lab_report_content (name, reference_ranges, position, type, metric,  template_ID) VALUES ( '$name', NULL, '$position', 'image', NULL, '$template_ID');");

    }

    public function add_field_type ( $name, $reference_ranges, $metric, $template_ID  ) {
        return $this->customFetchAll("INSERT INTO lab_report_content (name, reference_ranges, position, type, metric, template_ID) VALUES ( '$name', '$reference_ranges', NULL, 'field', '$metric', '$template_ID');");

    }
    
}   



?>