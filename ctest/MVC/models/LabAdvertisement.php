<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class LabAdvertisement extends DbModel {
    public string $title='';
    public string $description='';
    public string $remark='';
    public string $type='Lab';
    public string $img='';
    
    public function addAdvertisement(){
        return parent::save();
    }
 
    public function rules(): array {
        return [
            'title'=>[self::RULE_REQUIRED],
            'description'=>[self::RULE_REQUIRED],
            'remark'=>[self::RULE_REQUIRED],
        ];
    }

    public function fileDestination(): array {
        return ['img'=>"media/images/advertisements/lab/".$this->img];
    }

    public function tableName(): string {
        return 'advertisement';
    }

    public function primaryKey(): string {
        return 'ad_ID';
    }

    public function tableRecords(): array {
        return ['advertisement'=> ['title','description','remark','type','img']];
    }

    public function attributes(): array {
        return  ['title','description','remark','type','img'];
    }

    
}   



?>