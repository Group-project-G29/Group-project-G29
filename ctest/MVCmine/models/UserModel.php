<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;


class UserModel extends DbModel{
  
  

    public function register(){
        Application::$app->login($this,'Employee');
        $this->password=password_hash($this->password,PASSWORD_DEFAULT);
        return parent::save();
        
    }
    public function rules(): array
    {
        return [
            'name'=>[self::RULE_REQUIRED],
            'nic'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>12],[self::RULE_MAX,'max'=>15],[self::RULE_UNIQUE,'attribute'=>'nic','tablename'=>'employee'],[self::RULE_CHARACTER_VALIDATION,'regex'=>"^([0-9]{9}[x|X|v|V]|[0-9]{12})$^"]],
            'age'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>120]],
            'contact'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>10]],
            'email'=>[self::RULE_EMAIL],
            'address'=>[],       
            'role'=>[self::RULE_REQUIRED],
            'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MATCH,'retype'=>($this->cpassword)]]
               

        ];
    }
    public function fileDestination(): array
    {
        return ['img'=>"media/images/emp-profile-pictures/".$this->img];
    }
    public function tableName(): string
    {
        return 'employee';
    }
    public function primaryKey(): string
    {
        return 'email';
    }
    public function tableRecords(): array{
        if($this->role=='doctor') return ['employee'=>['name','nic','age','contact','email','address','gender','role','password','img'],'doctor'=>['nic','speciality','description']];
        return ['employee'=>['name','nic','age','contact','email','gender','address','role','password','img']];
    }

    public function attributes(): array
    {
        return ['name','nic','age','contact','email','address','gender','role','img','password','speciality','description'];
    }

    
}   



?>