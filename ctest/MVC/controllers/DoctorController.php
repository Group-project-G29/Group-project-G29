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
use app\models\EmployeeLoginForm;
use app\models\OpenedChanneling;

class DoctorController extends Controller{

    public function todayChannelings(){
        $this->setLayout('doctor',['select'=>'All Channelings']);
        $Channeling=new Channeling();
        $OpenedChanneling=new OpenedChanneling();
        $Channelings=$OpenedChanneling->customFetchAll("select * from opened_channeling where channeling_ID in (select channeling_ID from channeling where doctor=".Application::$app->session->get('userObject')->nic.")");
        return $this->render('doctor/today-channelings',[
           'channeling_model'=>$Channeling,
           'opened_channeling'=>$Channelings

        ]);
    }

    public function viewChanneling(Request $request){
        $Channeling=new Channeling();
        $OpenedChanneling=new OpenedChanneling();
        $Doctor=new Employee();
        $this->setLayout('doctor');
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

        return $this->render('doctor/channeling-start',[
            'openedchanneling'=>$OpenedChanneling,
            'channeling'=>$Channeling,
            'doctor'=>$Doctor,
            'nurse'=>$Nurses
            

        ]);
    }

    public function sessionAssistance(Request $request,Response $response){

        $this->setLayout("doctor-striped");
        $parameters=$request->getParameters();
        $OpenedChanneling=new OpenedChanneling();
        Application::$app->session->get('channeling');
        if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='start'){
           
            $id=$parameters[1]['id'];
            $OpenedChanneling->customFetchAll("update opened_channeling set status='started'");
            $appointment_detail=$OpenedChanneling->customFetchAll("SELECT * from appointment left join patient on patient.patient_ID=appointment.patient_ID left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where queue_no in (SELECT min(queue_no) from appointment where opened_channeling_id=".$id.")");
            if(!$appointment_detail){
                Application::$app->response->redirect('channeling-assistance?cmd=finish');
            }
            Application::$app->session->set('channeling',$id);
            return $this->render("doctor/channeling-assistance-patient",[
                'appointment'=>$appointment_detail,
                'trash'=>$OpenedChanneling->customFetchAll("select * from  patient right join appointment on patient.patient_ID=appointment.patient_ID left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where opened_channeling.opened_channeling_ID =".$id." order by appointment.queue_no")
            ]);

        }
        if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='next'){
            $id=$parameters[1]['id'];
            $channeling=Application::$app->session->get('channeling');
            $appointment_detail=[0=>$OpenedChanneling->getPatient($channeling,$id,'next')];
            if(!$appointment_detail){
                Application::$app->response->redirect('channeling-assistance?cmd=finish');
            }
            return $this->render("doctor/channeling-assistance-patient",[
                'appointment'=>$appointment_detail,
                
            ]);
        }
        else if(isSet($parameters[0]['cmd'])&& $parameters[0]['cmd']=='previous'){
            $id=$parameters[1]['id'];
            $channeling=Application::$app->session->get('channeling');
            $appointment_detail=[0=>$OpenedChanneling->getPatient($channeling,$id,'previous')];
            if(!$appointment_detail){
                Application::$app->response->redirect('channeling-assistance?cmd=finish');
            }
            return $this->render("doctor/channeling-assistance-patient",[
                'appointment'=>$appointment_detail
            ]);
        }
        else if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='finish'){
            return $this->render("doctor/channeling-finish",[
                
            ]);
        }
      

    }

    

   



}