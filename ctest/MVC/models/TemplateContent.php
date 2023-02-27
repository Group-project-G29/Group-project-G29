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
  
    public ?string $description=null;
   
    public string $template_ID='';
    public function addContent(){
        parent::save();  
    }
 
    public function rules(): array
    {
        return [
           
            'name'=>[self::RULE_REQUIRED],
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
        return ['lab_report_template'=> ['content_ID', 'name', 'reference_ranges', 'position', 'type', 'metric','title', 'template_ID']];
    }

    public function attributes(): array
    {
        return  ['content_ID', 'name', 'reference_ranges', 'position', 'type', 'metric','title', 'template_ID'];
    }

    
}   



?>