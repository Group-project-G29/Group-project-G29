<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class Advertisement extends DbModel{
    public string $id='';
    public string $title='';
    public string $description='';
    public string $remark='';
    public string $img='';
    public string $type='';

   
  

    public function getAdvertisements($type){
        return $this->fetchAssocAll(['type'=>$type]);
    }
   
    public function tableName(): string
    {
        return 'advertisement';
    }
    public function primaryKey(): string
    {
        return 'ad_ID';
    }
    public function tableRecords(): array{
        return ['advertisement'=>['ad_ID','title','description','remark','img','type']];
    }

    public function attributes(): array
    {
        return ['ad_ID','title','description','remark','img','type'];
    }

 
    
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
        if($this->type='pharmacy'){
            return ['img'=>"media/images/advertisements/pharmacy/".$this->img];
        }
        return ['img'=>"media/images/advertisements/".$this->img];
    }
    public function deleteImage($imgName){
        $path = "media/images/advertisements/".$imgName['ad_ID'];
        unlink($path);
    }

 

  

 
    
}   



?>