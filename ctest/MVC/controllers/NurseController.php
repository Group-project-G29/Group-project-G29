<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\User;
use app\core\DbModel;
use app\core\Response;
use app\models\Channeling;
use app\models\Employee;
use app\models\Medicine;
use app\models\OpenedChanneling;

class NurseController extends Controller{
    
    //view user details
    public function viewUserDetails(){
        $this->setLayout("nurse",['select'=>'My Detail']);
        $userDetailModel=new Employee();
        $user=$userDetailModel->customFetchAll("SELECT * FROM employee WHERE email=".'"'.Application::$app->session->get('user').'"');
        return $this->render('nurse/my-details',[
            "user" => $user[0]
        ]);
      
    }

    //view all clinics
    public function viewAllClinics(){
        $this->setLayout("nurse",['select'=>'All Clinics']);
        $allChanneling=new Channeling();
        $clinics=$allChanneling->customFetchAll("SELECT * FROM `channeling` INNER JOIN `employee` ON channeling.doctor = employee.nic;");
        return $this->render('nurse/all-clinics',[
            "clinics" => $clinics
        ]);
    }

    //view all clinics
    public function todayClinics(){
        $this->setLayout("nurse",['select'=>'Todays Clinics']);
        $allChanneling=new Channeling();
        $clinics=$allChanneling->customFetchAll("SELECT * FROM `channeling` INNER JOIN `employee` ON channeling.doctor = employee.nic;");
        return $this->render('nurse/today-clinics',[
            "clinics" => $clinics
        ]);
    }

    public function viewChanneling(Request $request){
        $Channeling=new Channeling();
        $OpenedChanneling=new OpenedChanneling();
        $Doctor=new Employee();
        $this->setLayout('nurse');
        $parameters=[];
        
        if($request->getParameters()){
            $parameters=$request->getParameters();
            $OpenedChanneling=$OpenedChanneling->findOne(["opened_channeling_ID"=>481]);
            // $OpenedChanneling=$OpenedChanneling->findOne(["opened_channeling_ID"=>$parameters[0]['channeling']]);
            // $OpenedChanneling=customFetchAll("Select * from opened_chanelings where channeling_ID=".$OpenedChanneling->channeling_ID);
            // $OpenedChanneling=$Channeling->customFetchAll("Select * from opened_channeling where channeling_ID=481");
            // var_dump($OpenedChanneling);
            // exit();
            $Employee=new Employee();
            $Channeling=$Channeling->findOne(["Channeling_ID"=>$OpenedChanneling->channeling_ID]);
            $Doctor=$Doctor->customFetchAll("Select * from employee left join  doctor on doctor.nic=employee.nic where doctor.nic=".$Channeling->doctor);
            // var_dump($OpenedChanneling->channeling_ID); 
            $Nurses=$Employee->customFetchAll("Select * from employee right join nurse_channeling_allocataion on employee.emp_ID=nurse_channeling_allocataion.emp_ID  left join channeling on channeling.channeling_ID=nurse_channeling_allocataion.channeling_ID  where nurse_channeling_allocataion.channeling_ID=".$OpenedChanneling->channeling_ID);
            
     
        }

        return $this->render('doctor/channeling-start',[
            'openedchanneling'=>$OpenedChanneling,
            'channeling'=>$Channeling,
            'doctor'=>$Doctor,
            'nurse'=>$Nurses
            

        ]);
    }



    //view all clinics more
    public function viewAllClinicsMore(Request $request){
        
        $this->setLayout("normal");
        $channeling=new Channeling();

        if($request->getParameters()){
            $parameters=$request->getParameters();
            $id = $parameters[0]['id'];
            // echo($id);exit();
        }
        $clinic=$channeling->customFetchAll("SELECT * FROM `channeling` INNER JOIN `employee` ON channeling.doctor = employee.nic WHERE channeling_ID=$id;");
        // var_dump($clinic);exit();
        return $this->render('nurse/all-clinic-more',[
            "clinic" => $clinic
        ]);
    }

}