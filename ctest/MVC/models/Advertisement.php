<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class Advertisement extends DbModel {
    public string $title='';
    public string $description='';
    public string $remark='';
    public string $type='main';
    public string $image='';
    
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
        return ['image'=>"media/images/advertisements/".$this->image];
    }
    public function deleteImage($imgName){
        $path = "media/images/advertisements/".$imgName['ad_ID'];
        unlink($path);
    }

    public function tableName(): string {
        return 'advertisement';
    }

    public function primaryKey(): string {
        return 'ad_ID';
    }

    public function tableRecords(): array {
        return ['advertisement'=> ['title','description','remark','type','image']];
    }

    public function attributes(): array {
        return  ['title','description','remark','type','image'];
    }

    
}   



?>