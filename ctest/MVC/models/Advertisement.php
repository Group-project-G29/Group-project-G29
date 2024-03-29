<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class Advertisement extends DbModel{
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
        return ['advertisement'=>['title','description','remark','img','type']];
    }

    public function attributes(): array
    {
        return ['title','description','remark','img','type'];
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
        if($this->type=='pharmacy'){
            return ['img'=>"media/images/advertisements/pharmacy/".$this->img];
        }
        if($this->type=='lab'){
            return ['img'=>"media/images/advertisements/lab/".$this->img];
        }

        return ['img'=>"media/images/advertisements/".$this->img];
    }

    public function deleteImage($imgName, $type){
        if($type=='pharmacy'){
            $path = "media/images/advertisements/pharmacy/".$imgName;
        }
        else if($type=='lab'){
            $path = "media/images/advertisements/lab/".$imgName;
        }
        else{
            $path = "media/images/advertisements/".$imgName;
        }
        unlink($path);
    }

 

  

 
    
}   



?>