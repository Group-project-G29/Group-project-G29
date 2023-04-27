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
    public string $firstname='';
    public string $lastname='';
    
  

    public function register(){
        if($this->age<=18){
            $this->type="pedatric"; 
            $this->name=$_POST['firstname']." ".$_POST['lastname'];
           
        }
        else{
            $this->type="adult";
        }
        $this->password=password_hash($this->password,PASSWORD_DEFAULT);
        $last_id=parent::save();
        $this->patient_ID=$last_id[0]['last_insert_id()'];
        Application::$app->login($this,'patient');
        return true; 
        
    }
    public function register_non_session(){
        $this->password=password_hash($this->nic,PASSWORD_DEFAULT);
        $last_id=parent::save();
       
        return true; 
        
    }
    public function logout(){
        Application::$app->logout();
        return true;
    }
    public function rules(): array
    {
        if($this->age<18){
            return [
                'firstname'=>[self::RULE_REQUIRED],
                'lastname'=>[self::RULE_REQUIRED],
                'age'=>[self::RULE_REQUIRED],
                'gender'=>[self::RULE_REQUIRED],
                'guardian_name'=>[self::RULE_REQUIRED],
                'nic'=>[self::RULE_REQUIRED],
                'contact'=>[self::RULE_REQUIRED],
                'email'=>[self::RULE_EMAIL],
               // 'password'=>[self::RULE_PASSWORD_VALIDATION]
                'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MATCH,'retype'=>($this->cpassword)],[self::RULE_PASSWORD_VALIDATION,'regex'=>"$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$",'attribute'=>"password"]]
            ];
        }
        return [
            'name'=>[self::RULE_REQUIRED],
            'nic'=>[/*self::RULE_REQUIRED,[self::RULE_MIN,'min'=>12],[self::RULE_MAX,'max'=>15],[self::RULE_UNIQUE,'attribute'=>'nic','tablename'=>'employee'],*/[self::RULE_CHARACTER_VALIDATION,'regex'=>"^([0-9]{9}[x|X|v|V]|[0-9]{12})$^",'attribute'=>'NIC number']],
            'age'=>[self::RULE_REQUIRED,self::RULE_NUMBERS,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>120]],
            'contact'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>10]],
            'email'=>[self::RULE_EMAIL],
            'address'=>[],       
            'gender'=>[self::RULE_REQUIRED],
            //'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MATCH,'retype'=>($this->cpassword)]]
                'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MATCH,'retype'=>($this->cpassword)],[self::RULE_PASSWORD_VALIDATION,'regex'=>"$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$",'attribute'=>"password"]]
               

        ];
    }
    // get recent patient 
    public function getRecentPatientDoctor(){
        //get the last patients in past channelin session
        //also get the patient where nurse got involoved in today channeling session
        $doctor=Application::$app->session->get('userObject')->nic;
        $patient= $this->customFetchAll("select * from past_channeling as p left join opened_channeling as o on p.opened_channeling_ID=o.opened_channeling_ID left join channeling as c on c.channeling_ID=o.channeling_ID left join appointment as a on a.opened_channeling_ID=o.opened_channeling_ID left join nurse_channeling_allocataion as n on n.channeling_ID=c.channeling_ID left join employee as e on e.emp_ID=n.emp_ID left join patient as pa on  pa.patient_ID=a.patient_ID  where c.doctor='".$doctor."' order by o.channeling_date") ;
        return $patient;

    }
    public function getRecentPatientNurse(){
        //get the last patients in past channelin session
        //also get the patient where nurse got involoved in today channeling session
        $nurse=Application::$app->session->get('userObject')->emp_ID;
        echo "select * from past_channeling as p left join opened_channeling as o on p.opened_channeling_ID=o.opened_channeling_ID left join channeling as c on c.channeling_ID=o.channeling_ID left join appointment as a on a.opened_channeling_ID=o.opened_channeling_ID left join nurse_channeling_allocataion as n on n.channeling_ID=c.channeling_ID  left join patient as pa on  pa.patient_ID=a.patient_ID  where n.emp_ID=".$nurse." order by o.channeling_date";
        $patient= $this->customFetchAll("select * from past_channeling as p left join opened_channeling as o on p.opened_channeling_ID=o.opened_channeling_ID left join channeling as c on c.channeling_ID=o.channeling_ID left join appointment as a on a.opened_channeling_ID=o.opened_channeling_ID left join nurse_channeling_allocataion as n on n.channeling_ID=c.channeling_ID left join employee as e on e.emp_ID=n.emp_ID left join patient as pa on  pa.patient_ID=a.patient_ID  where n.emp_ID=".$nurse." order by o.channeling_date") ;
        return $patient;

    }
    public function isDoctor($patient,$doctor){
        if(!$patient) return false;
        else{
            $result=$this->customFetchAll("select * from appointment left join opened_channeling on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where appointment.patient_ID=".$patient." and channeling.doctor='".$doctor."'");
        } 
        if($result) return true;
        else return false;   
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