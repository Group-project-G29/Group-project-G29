<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class TemplateContent extends DbModel{
    public string $content_ID='';
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
        return $this->customFetchAll(" SELECT * FROM lab_report_template group by title ORDER BY template_ID DESC;");
    }

    public function select_last_content_ID($template_ID) {
        return $this->customFetchAll(" SELECT * FROM lab_report_content WHERE template_ID=$template_ID ORDER BY position DESC;");
    }
    public function select_deleted_content($content_ID){
        return $this->customFetchAll(" SELECT * FROM lab_report_content WHERE content_ID=$content_ID ;");
    }
    public function select_updating_position($template_ID,$position){
        return $this->customFetchAll("SELECT * FROM lab_report_content WHERE template_ID=$template_ID  AND position>$position");
    }


    public function add_text_type( $name,$position, $template_ID ) {
         return $this->customFetchAll("INSERT INTO lab_report_content (name, reference_ranges, position, type, metric, template_ID) VALUES ( '$name', NULL, $position, 'text', NULL, '$template_ID');");
    }

    public function add_image_type(  $name,$position, $template_ID ) {
        return $this->customFetchAll("INSERT INTO lab_report_content (name, reference_ranges, position, type, metric,  template_ID) VALUES ( '$name', NULL, '$position', 'image', NULL, '$template_ID');");

    }

    public function add_field_type ( $name, $reference_ranges,$position, $metric, $template_ID  ) {
        return $this->customFetchAll("INSERT INTO lab_report_content (name, reference_ranges, position, type, metric, template_ID) VALUES ( '$name', '$reference_ranges',$position, 'field', '$metric', '$template_ID');");

    }

    public function set_new_position($content_ID,$content_pos){
        return $this->customFetchAll("UPDATE lab_report_content set position=$content_pos where content_ID=$content_ID");
    }


    
}
