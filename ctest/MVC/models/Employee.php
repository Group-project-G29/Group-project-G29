<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;
use app\models\Patient;

class Employee extends DbModel{
    
    public string $name='';
    public string $nic='';
    public  $age=0;
    public string $contact="";
    public string $email="";
    public string $address="";
    public string $img="";
    public string $gender='';
    public string $password="";
    public string $cpassword="";
    public string $role="";
    public string $speciality='';
    public string $description='';
    public string $emp_ID='';
    // function isValidPassword($password) {
//     if (!preg_match_all(', $password))
//         return FALSE;
//     return TRUE;
// }

    public function register(){
       
        $this->password=password_hash($this->password,PASSWORD_DEFAULT);
        return parent::save(); //save data in the database
        
    }

    public function logout(){
        Application::$app->logout();
        return true;
    }
    public function rules(): array
    {
        return [
            'name'=>[self::RULE_REQUIRED,[self::RULE_CHARACTER_VALIDATION,'regex'=>"/^[a-z ,.'-]+$/i",'attribute'=>'name']],
            'nic'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>9],[self::RULE_MAX,'max'=>15],[self::RULE_UNIQUE,'attribute'=>'nic','tablename'=>'employee'],[self::RULE_CHARACTER_VALIDATION,'regex'=>"^([0-9]{9}[x|X|v|V]|[0-9]{12})$^",'attribute'=>"nic"]],
            'age'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>120],self::RULE_NUMBERS],
            'contact'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>10]],
            'email'=>[self::RULE_EMAIL.self::RULE_UNIQUE],
            'address'=>[],       
            'role'=>[self::RULE_REQUIRED],
            'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MATCH,'retype'=>($this->cpassword)],[self::RULE_PASSWORD_VALIDATION,'regex'=>"$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$",'attribute'=>"password"]]
               

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
    public function getAccounts($role=''):array{
        if($role==''){
            return $this->customFetchAll("SELECT * FROM employee where role<>'admin' order by role ");
        }
        if($role=='doctor'){
            return $this->customFetchAll("SELECT * FROM employee left join doctor on doctor.nic=employee.nic where employee.role='$role' order by speciality" );
        }

    }
    public function getChannelings($doctor):array{
        return $this->customFetchAll("SELECT * from opened_channeling LEFT JOIN channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join doctor on doctor.nic=channeling.doctor left join employee on employee.nic=doctor.nic WHERE employee.emp_ID=".$doctor);

    }

    
}   



?>