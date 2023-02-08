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
        $this->setLayout("nurse",['select'=>'All Channelings']);
        $allChanneling=new Channeling();
        $clinics=$allChanneling->customFetchAll("SELECT * FROM `channeling` INNER JOIN `employee` ON channeling.doctor = employee.nic;");
        return $this->render('nurse/all-channelings',[
            "clinics" => $clinics
        ]);
    }

    //view all clinics
    public function todayClinics(){
        $this->setLayout("nurse",['select'=>'Today Channelings']);
        $OpenedChanneling=new OpenedChanneling();
        
        $date = date("Y-m-d");
        $openedChanneling=$OpenedChanneling->customFetchAll("SELECT * FROM `opened_channeling` INNER JOIN `channeling` ON opened_channeling.channeling_ID = channeling.channeling_ID INNER JOIN `employee` ON channeling.doctor = employee.nic WHERE channeling_date = '$date';");
        return $this->render('nurse/today-channelings',[
            "openedChanneling" => $openedChanneling
        ]);
    }


    public function viewChanneling(Request $request){
        $Channeling=new Channeling();
        $OpenedChanneling=new OpenedChanneling();
        $Doctor=new Employee();
        $this->setLayout('normal');
        $parameters=[];
        if($request->getParameters()){
            $parameters=$request->getParameters();
            $OpenedChanneling=$OpenedChanneling->findOne(["opened_channeling_ID"=>$parameters[0]['channeling']]);
            $Employee=new Employee();
            $Channeling=$Channeling->findOne(["Channeling_ID"=>$OpenedChanneling->channeling_ID]);
            $Doctor=$Doctor->customFetchAll("Select * from employee left join  doctor on doctor.nic=employee.nic where doctor.nic=".$Channeling->doctor);
            // var_dump($OpenedChanneling->channeling_ID);
            $Nurses=$Employee->customFetchAll("Select * from employee right join nurse_channeling_allocataion on employee.emp_ID=nurse_channeling_allocataion.emp_ID  left join channeling on channeling.channeling_ID=nurse_channeling_allocataion.channeling_ID  where nurse_channeling_allocataion.channeling_ID=".$OpenedChanneling->channeling_ID);
            
     
        }

        return $this->render('nurse/all-channeling-session',[
            'openedchanneling'=>$OpenedChanneling,
            'channeling'=>$Channeling,
            'doctor'=>$Doctor,
            'nurse'=>$Nurses
            

        ]);
    }



    //view all clinics more
    public function viewAllClinicsMore(Request $request){
        
        $this->setLayout("normal");
        $Channeling=new Channeling();
        $OpenedChanneling=new OpenedChanneling();
        $Doctor=new Employee();
        $Nurse=new Employee();

        if($request->getParameters()){
            $parameters=$request->getParameters();
            $id = $parameters[0]['id'];
            // echo($id);exit();
        }
        $channeling=$Channeling->customFetchAll("SELECT * FROM `channeling` INNER JOIN `employee` ON channeling.doctor = employee.nic WHERE channeling_ID=$id;");
        $openedChanneling=$OpenedChanneling->customFetchAll("SELECT * FROM `opened_channeling` WHERE channeling_ID=$id;");
        $docNIC = $channeling[0]['doctor'];
        $doctor = $Doctor->customFetchAll("SELECT * FROM `employee` WHERE nic=$docNIC;");
        $nurse = $Nurse->customFetchAll("SELECT * FROM `nurse_channeling_allocataion` INNER JOIN `employee` ON nurse_channeling_allocataion.emp_ID = employee.emp_ID WHERE channeling_ID=$id;");
        // var_dump($channeling[0]['doctor']);exit();
        // var_dump($nurse);exit();
        return $this->render('nurse/all-channeling-more',[
            "channeling" => $channeling,
            "openedchanneling" => $openedChanneling,
            "doctor" => $doctor,
            "nurse" => $nurse
        ]);
    }

}