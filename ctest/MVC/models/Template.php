<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class Template extends DbModel{
   
    public string $title='';
    public ?string $subtitle=null;
    public string $created_date='';
  
    
   
    public function addTemplate(){
       return parent::savenofiles(); 
         
    }
 
    public function rules(): array
    {
        return [
           
            'title'=>[self::RULE_REQUIRED],
            'subtitle'=>[],
            'created_date'=>[],
            
        ];
    }
    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'lab_report_template';
    }
    public function primaryKey(): string
    {
        return 'template_ID';
    }
    public function tableRecords(): array{
        return ['lab_report_template'=> [ 'title', 'subtitle', 'created_date']];
    }

    public function attributes(): array
    {
        return  [ 'title', 'subtitle', 'created_date'];
    }

    
}   



?>