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

   
  

  
    public function rules(): array
    {
        return [
            // 'name'=>[self::RULE_REQUIRED],
            //'nic'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>12],[self::RULE_MAX,'max'=>15],[self::RULE_UNIQUE,'attribute'=>'nic','tablename'=>'employee']],
            // 'age'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>120]],
            // 'contact'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>10]],
            // 'email'=>[self::RULE_EMAIL],
            // 'address'=>[],       
            // 'role'=>[self::RULE_REQUIRED],
            // 'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MATCH,'retype'=>($this->cpassword)]]
           


        ];
    }

    public function getAdvertisements($type){
        return $this->fetchAssocAll(['type'=>$type]);
    }
   

    public function fileDestination(): array
    {
        return [];
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

    
}   



?>