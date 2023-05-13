<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\Date;
use app\core\UserModel;
use app\models\Patient;
use JetBrains\PhpStorm\Internal\ReturnTypeContract;

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
    public string $career_speciality='';
    public string $description='';
    public string $emp_ID='';
    // function isValidPassword($password) {
//     if (!preg_match_all(', $password))
//         return FALSE;
//     return TRUE;
// }

    public function register(){
       
        $this->password=password_hash($this->password,PASSWORD_DEFAULT);
        $id=parent::save();
        if($this->role=='delivery'){
            $this->customFetchAll("insert into delivery_rider values(".$id[0]['last_insert_id()'].",'NA') ");
           $this->enqueue_rider($id[0]['last_insert_id()']);
        }
        return $id; //save data in the database
        
    }

    public function logout(){
        Application::$app->logout();
        return true;
    }
    

    public function rules(): array
    {
        //if update page is shown
        if($this->emp_ID!=''){
             return [
                'name'=>[self::RULE_REQUIRED,[self::RULE_CHARACTER_VALIDATION,'regex'=>"/^[a-z ,.'-]+$/i",'attribute'=>'name']],
                'nic'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>9],[self::RULE_MAX,'max'=>15,[self::RULE_UNIQUE,'attribute'=>'nic','tablename'=>'employee']],
                [self::RULE_CHARACTER_VALIDATION,'regex'=>"^([0-9]{9}[x|X|v|V]|[0-9]{12})$^",'attribute'=>"nic"]],
                'age'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>120],self::RULE_NUMBERS],
                'contact'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>10]],
                'email'=>[self::RULE_EMAIL,[self::RULE_UNIQUE,'attribute'=>'email','tablename'=>'employee']],
                'address'=>[],       
            ];    
        }
        if($this->role==='admin'){
            return [
                'name'=>[self::RULE_REQUIRED,[self::RULE_CHARACTER_VALIDATION,'regex'=>"/^[a-z ,.'-]+$/i",'attribute'=>'name']],
                'nic'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>9],[self::RULE_MAX,'max'=>15],[self::RULE_UNIQUE,'attribute'=>'nic','tablename'=>'employee'],
                [self::RULE_CHARACTER_VALIDATION,'regex'=>"^([0-9]{9}[x|X|v|V]|[0-9]{12})$^",'attribute'=>"nic"]],
                'age'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>120],self::RULE_NUMBERS],
                'contact'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>10]],
                'email'=>[self::RULE_EMAIL,[self::RULE_UNIQUE,'attribute'=>'email','tablename'=>'employee']],
                'address'=>[],       
                'role'=>[self::RULE_REQUIRED],
                'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MATCH,'retype'=>($this->cpassword)],[self::RULE_PASSWORD_VALIDATION,'regex'=>"$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$",'attribute'=>"password"]]
            ];
        } else {
            return [
                'name'=>[self::RULE_REQUIRED,[self::RULE_CHARACTER_VALIDATION,'regex'=>"/^[a-z ,.'-]+$/i",'attribute'=>'name']],
                'nic'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>9],[self::RULE_MAX,'max'=>15],
                [self::RULE_CHARACTER_VALIDATION,'regex'=>"^([0-9]{9}[x|X|v|V]|[0-9]{12})$^",'attribute'=>"nic"],[self::RULE_UNIQUE,'attribute'=>'nic','tablename'=>'employee']],
                'age'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>120],self::RULE_NUMBERS],
                'contact'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>10]],
                'email'=>[self::RULE_EMAIL,[self::RULE_UNIQUE,'attribute'=>'email','tablename'=>'employee']],
                'address'=>[],       
                'role'=>[self::RULE_REQUIRED],
                'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MATCH,'retype'=>($this->cpassword)],[self::RULE_PASSWORD_VALIDATION,'regex'=>"$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$",'attribute'=>"password"]]
            ];
        }
    }



    public function fileDestination(): array
    {
        return ['img'=>"media/images/emp-profile-pictures/".$this->img];
    }
    public function getDocName($id){
        return $this->fetchAssocAll(['nic'=>$id])[0]['name'];
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
        if($this->role=='doctor' && $this->emp_ID=='') return ['employee'=>['name','nic','age','contact','email','address','gender','role','password','img'],'doctor'=>['nic','career_speciality','description']];
        return ['employee'=>['name','nic','age','contact','email','gender','address','role','password','img']];
    }

    public function attributes(): array
    {
        if($this->role=='doctor') return ['name','nic','age','contact','email','address','gender','role','img','password','career_speciality','description'];
        return ['name','nic','age','contact','email','address','gender','role','img','password'];
    }
    
    public function getAccounts($role=''):array{
        if($role==''){
            return $this->customFetchAll("SELECT * FROM employee where role<>'admin' order by role ");
        }
        if($role=='doctor'){
            return $this->customFetchAll("SELECT * FROM employee left join doctor on doctor.nic=employee.nic where employee.role='$role' order by career_speciality" );
        }

    }
    public function getChannelings($doctor):array{
        return $this->customFetchAll("SELECT * from opened_channeling LEFT JOIN channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join doctor on doctor.nic=channeling.doctor left join employee on employee.nic=doctor.nic WHERE employee.emp_ID=".$doctor);

    }

    public function getDoctors(){
         $Doctors=$this->customFetchAll("Select  name,nic from employee where role='doctor' and employee_status='active' and nic<>'".Application::$app->session->get('user')."'");
         $Doctor=['select'=>''];
         foreach($Doctors as $row){
            if($row['nic']!=Application::$app->session->get('userObject')->nic){
                $Doctor[$row['name']]=$row['nic'];
            }
        }
        return $Doctor;
    }
    public function isNurse($nurse,$channeling){
        $result=$this->customFetchAll("select * from nurse_channeling_allocataion where channeling_ID=".$channeling." and emp_ID=".$nurse);
        if($result){
            return true;
        }
        else{
            return false;
        }
    }
    public function getAssignedNurses($channeling_ID){
        
    }
    public function getAssignedRooms($channeling_ID){
    }


    //doctor summary data generation
    public function getThisMonthPatients($doctor){
        return $this->customFetchAll("select count(appointment.appointment_ID) from appointment left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID right join patient on appointment.patient_ID=patient.patient_ID where channeling.doctor=".$doctor." and MONTH(opened_channeling.channeling_date)=MONTH('".Date('Y-m-d')."') and MONTH(opened_channeling.channeling_date)=MONTH('".Date('Y-m-d')."')")[0]['count(appointment.appointment_ID)']; 
    }
    public function  getThisMonthChannelings($doctor){
        return $this->customFetchAll("select * from opened_channeling left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where channeling.doctor=".$doctor." and MONTH(opened_channeling.channeling_date)=MONTH('".Date('Y-m-d')."') and YEAR(opened_channeling.channeling_date)=YEAR('".Date('Y-m-d')."')")[0];
    }
    public function calcuateThisMonthIncome($doctor){
        return $this->customFetchAll("select sum(total_income) from past_channeling where opened_channeling_ID in (select opened_channeling.opened_channeling_ID from  opened_channeling  left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID   where channeling.doctor=".$doctor.") and MONTH(created_date)=MONTH('".Date('Y-m-d')."') and YEAR(created_date)=YEAR('".Date('Y-m-d')."')")[0]['sum(total_income)'];
    }
    public function growthOfPatients($doctor){
        $months=['dummy','January','February','March','April','May','June','July','August','September','Octomber','November','December'];
        $dateModel=new Date();
        $today=Date('Y-m-d');
        $year=$dateModel->get($today,'year')-1;
        $month=$dateModel->get($today,'month');
        $day=$dateModel->get($today,'day');
        $lowdate=$dateModel->arrayToDate([$day,$month,$year]);
        $result=$this->customFetchAll("select MONTH(opened_channeling.channeling_date),sum(past_channeling.no_of_patient) from past_channeling  left join opened_channeling on opened_channeling.opened_channeling_ID=past_channeling.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID  where channeling.doctor='$doctor' and opened_channeling.channeling_date>='$lowdate' and opened_channeling.channeling_date<='$today' GROUP by MONTH(opened_channeling.channeling_date);");
        $label=[];
        $value=[];
        foreach($result as $row){
            array_push($label,$months[0+$row['MONTH(opened_channeling.channeling_date)']]);
            array_push($value,$row['sum(past_channeling.no_of_patient)']);
        }
        return ['labels'=>$label,'values'=>$value];

    }
    public function growthOfIncome($doctor){
        $dateModel=new Date();
        $months=['dummy','January','February','March','April','May','June','July','August','September','Octomber','November','December'];
        $today=Date('Y-m-d');
        $year=$dateModel->get($today,'year')-1;
        $month=$dateModel->get($today,'month');
        $day=$dateModel->get($today,'day');
        $lowdate=$dateModel->arrayToDate([$day,$month,$year]);
        $result=$this->customFetchAll("select MONTH(opened_channeling.channeling_date),sum(past_channeling.total_income) from past_channeling  left join opened_channeling on opened_channeling.opened_channeling_ID=past_channeling.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID  where channeling.doctor='200003483345' and opened_channeling.channeling_date>='$lowdate' and opened_channeling.channeling_date<='$today' GROUP by MONTH(opened_channeling.channeling_date);");
        $label=[];
        $value=[];
        foreach($result as $row){
            array_push($label,$months[0+$row['MONTH(opened_channeling.channeling_date)']]);
            array_push($value,$row['sum(past_channeling.total_income)']);
        }
        return ['labels'=>$label,'values'=>$value];
    }
    
    public function removeNurse($channeling){
        $this->customFetchAll("delete from nurse_channeling_allocataion where channeling_ID=".$channeling);
    }
    public function addNurse($post,$channeling){
        $nurses=$_POST[$post];
        foreach($nurses as $nurse){
            $this->customFetchAll("insert into nurse_channeling_allocataion values(".$nurse.",".$channeling.")");
        }
    }
    
    
     public function get_employee_details($emp_ID) {
        return $this->customFetchAll("SELECT * FROM employee WHERE emp_ID=$emp_ID");
    }

    // public function select_suitable_rider( $postal_code, $order_ID ) {
    //     //error in query -> no elements in array
    //     return $this->customFetchAll("SELECT * FROM delivery INNER JOIN delivery_rider ON delivery.delivery_rider = delivery_rider.emp_ID INNER JOIN _order ON delivery.delivery_ID = _order.delivery_ID WHERE _order.pickup_status='delivery' AND delivery_rider.availability='AV' AND _order.order_ID != $order_ID AND delivery.postal_code BETWEEN $postal_code-10 AND $postal_code+10");
    // }
    
    public function select_suitable_rider( $city, $order_ID ) {
            return $this->customFetchAll("SELECT * FROM delivery INNER JOIN delivery_rider ON delivery.delivery_rider = delivery_rider.emp_ID INNER JOIN _order ON delivery.delivery_ID = _order.delivery_ID WHERE _order.pickup_status='delivery' AND delivery_rider.availability='AV' AND _order.order_ID != $order_ID AND delivery.city=$city");
    }
    public function select_queue_rider() {
        return $this->customFetchAll("SELECT * FROM delivery_riders_queue");
    }

    public function dequeue_rider( $rider_ID ) {
        return $this->customFetchAll("DELETE FROM delivery_riders_queue WHERE delivery_rider_ID = $rider_ID");
    }

    public function enqueue_rider( $rider_ID ) {
        return $this->customFetchAll("INSERT INTO delivery_riders_queue (delivery_rider_ID) VALUES ($rider_ID);");
    }
    
    public function make_rider_offline( $rider_ID ) {
        return $this->customFetchAll("UPDATE delivery_rider SET availability = 'NA' WHERE emp_ID = $rider_ID;");
    }

    public function make_rider_online( $rider_ID ) {
        return $this->customFetchAll("UPDATE delivery_rider SET availability = 'AV' WHERE emp_ID = $rider_ID;");
    }

    public function get_rider_availability( $delivery_rider ) {
        return $this->customFetchAll("SELECT availability FROM delivery_rider WHERE emp_ID = $delivery_rider");
    }

     public function updateAccounts($id){
        if(Application::$app->session->get('userObject')->role=='admin'){
            $this->customFetchAll("update employee set name='".$_POST['name']."', nic='".$_POST['nic']."', age=".$_POST['age'].", contact='".$_POST['contact']."', email='".$_POST['email']."', address='".$_POST['address']."', gender='".$_POST['gender']."' where emp_ID=".$id);    
            return true;
        }
        $this->customFetchAll("update employee set name='".$_POST['name']."', nic='".Application::$app->session->get('userObject')->nic."', age=".$_POST['age'].", contact='".$_POST['contact']."', email='".$_POST['email']."', address='".$_POST['address']."', gender='".$_POST['gender']."' where emp_ID=".$id);    
        return true;
    }
     public function updateDoctorRecord($id){
      
        if($_FILES['img']['name']){
            $this->fileStore(); 
            $this->customFetchAll("update employee set name='".$_POST['name']."', nic='".Application::$app->session->get('userObject')->nic."', age=".$_POST['age'].", contact='".$_POST['contact']."', email='".$_POST['email']."', address='".$_POST['address']."', gender='".$_POST['gender']."',img='".$this->img."' where nic=".$id);
            $this->customFetchAll("update doctor set description='".$_POST['description']."' where nic=".$id);
            return true;
        }
        $this->customFetchAll("update employee set name='".$_POST['name']."', nic='".Application::$app->session->get('userObject')->nic."', age=".$_POST['age'].", contact='".$_POST['contact']."', email='".$_POST['email']."', address='".$_POST['address']."', gender='".$_POST['gender']."' where nic=".$id);
      
            $this->customFetchAll("update doctor set description='".$_POST['description']."' where nic=".$id);
        
        
        return true;
    }
    public function updateemployee($data){
        
        $this->customFetchAll("UPDATE `employee` SET
            name = '$data->name',
            nic = '$data->nic',
            gender = '$data->gender',
            contact = '$data->contact',
            email = '$data->email',
            address = '$data->address',
            img = '$data->img',
            age = $data->age
            WHERE emp_ID = $data->emp_ID
        ");

        return true;
    }

    public function get_employee_details_by_NIC($nic) {
        return $this->customFetchAll("SELECT * FROM employee WHERE nic=$nic");
    }

 

   

}   



?>