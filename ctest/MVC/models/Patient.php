<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class Patient extends DbModel{
    public string $patient_ID='';
    public string $name='';
    public string $nic='';
    public  $age=0;
    public string $contact="";
    public string $email="";
    public string $address="";
    public string $gender='';
    public string $password="";
    public string $cpassword="";
    public string $type="";
    public string $verification='';
    public ?string $relation='';
    public string $guardian_name='';
  

    public function register(){
        $this->password=password_hash($this->password,PASSWORD_DEFAULT);
        $last_id=parent::save();
        $this->patient_ID=$last_id[0]['last_insert_id()'];
        Application::$app->login($this,'patient');
        return true; 
        
    }
    public function register_non_session(){
        $this->password=password_hash($this->password,PASSWORD_DEFAULT);
        $last_id=parent::save();
       
        return true; 
        
    }
    public function logout(){
        Application::$app->logout();
        return true;
    }
    public function rules(): array
    {
        return [
            'name'=>[self::RULE_REQUIRED],
            'nic'=>[/*self::RULE_REQUIRED,[self::RULE_MIN,'min'=>12],[self::RULE_MAX,'max'=>15],[self::RULE_UNIQUE,'attribute'=>'nic','tablename'=>'employee'],*/[self::RULE_CHARACTER_VALIDATION,'regex'=>"^([0-9]{9}[x|X|v|V]|[0-9]{12})$^",'attribute'=>'NIC number']],
            'age'=>[self::RULE_REQUIRED,self::RULE_NUMBERS,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>120]],
            'contact'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>10]],
            'email'=>[self::RULE_EMAIL],
            'address'=>[],       
          //  'relation'=>[self::RULE_REQUIRED],
            'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MATCH,'retype'=>($this->cpassword)]]
               

        ];
    }
  
    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'patient';
    }
    public function primaryKey(): string
    {
        return 'patient_ID';
    }
    public function tableRecords(): array{
        return ['patient'=>['name','guardian_name','nic','age','contact','email','gender','address','type','password','verification']];
    }

    public function attributes(): array
    {
        return ['name','guardian_name','nic','age','contact','email','gender','address','type','password','verification'];
    }

    
}   



?>