<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\ConsultationReport;
use app\models\User;
use app\core\DbModel;
use app\core\Response;
use app\models\Channeling;

use app\models\Employee;
use app\models\EmployeeLoginForm;
use app\models\MedicalHistory;
use app\models\OpenedChanneling;
use app\models\Referral;
use app\core\FileModel;
use app\core\ReportModel;

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
        $this->setLayout('doctor',['select'=>'Today Channelings']);
        $parameters=[];
        if($request->getParameters()){
            $parameters=$request->getParameters();
            $OpenedChanneling=$OpenedChanneling->findOne(["opened_channeling_ID"=>$parameters[0]['channeling']]);
            $Employee=new Employee();
            $Channeling=$Channeling->findOne(["Channeling_ID"=>$OpenedChanneling->channeling_ID]);
            $Doctor=$Doctor->customFetchAll("Select * from employee left join  doctor on doctor.nic=employee.nic where doctor.nic=".$Channeling->doctor);
            $Nurses=$Employee->customFetchAll("Select * from employee right join nurse_channeling_allocataion on employee.emp_ID=nurse_channeling_allocataion.emp_ID  left join channeling on channeling.channeling_ID=nurse_channeling_allocataion.channeling_ID  where nurse_channeling_allocataion.channeling_ID=".$OpenedChanneling->channeling_ID);
            
     
        }

        return $this->render('doctor/channeling-start',[
            'openedchanneling'=>$OpenedChanneling,
            'channeling'=>$Channeling,
            'doctor'=>$Doctor,
            'nurse'=>$Nurses
            

        ]);
    }

    // make a report model
    public function sessionAssistance(Request $request,Response $response){

        $this->setLayout("doctor-striped");
        $parameters=$request->getParameters();
        $OpenedChanneling=new OpenedChanneling();
        Application::$app->session->get('channeling');
        $referrralModel=new Referral();
        $reportModel = new ConsultationReport();
        $doctor = Application::$app->session->get('userObject')->nic;
        if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='start'){
           
            $id=$parameters[1]['id'];
            $OpenedChanneling->customFetchAll("update opened_channeling set status='started' where opened_channeling_ID=".$id);
            $appointment_detail=$OpenedChanneling->customFetchAll("SELECT * from appointment left join patient on patient.patient_ID=appointment.patient_ID left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where appointment.queue_no in (SELECT min(queue_no) from appointment where opened_channeling_ID=".$id.")"." and opened_channeling.opened_channeling_ID=".$id);
            if(!$appointment_detail){
                Application::$app->response->redirect('channeling-assistance?cmd=finish');
                return false;
            }
            Application::$app->session->set('channeling',$id);
            $referrals = $referrralModel->getReferrals($appointment_detail[0]['patient_ID'],$id);
            $reports = $reportModel->getReports($appointment_detail[0]['patient_ID'],$doctor);
            return $this->render("doctor/channeling-assistance-patient",[
                'appointment'=>$appointment_detail,
                'trash'=>$OpenedChanneling->customFetchAll("select * from  patient right join appointment on patient.patient_ID=appointment.patient_ID left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where opened_channeling.opened_channeling_ID =".$id." order by appointment.queue_no"),
                'referrals'=>$referrals,
                'reports'=>$reports
            ]);

        }
        if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='next'){
            $id=$parameters[1]['id'];
            $channeling=Application::$app->session->get('channeling');
            $appointment_detail[0]=$OpenedChanneling->getPatient($channeling,$id,'next');
            if(!$appointment_detail[0]){
                Application::$app->response->redirect('channeling-assistance?cmd=finish');
                return false;
            }
            $referrals = $referrralModel->getReferrals($appointment_detail[0]['patient_ID'],$appointment_detail[0]['opened_channeling_ID']);
            $reports = $reportModel->getReports($appointment_detail[0]['patient_ID'],$doctor);
            return $this->render("doctor/channeling-assistance-patient",[
                'appointment'=>$appointment_detail,
                'referrals'=>$referrals,
                'reports'=>$reports
                
            ]);
        }
        else if(isSet($parameters[0]['cmd'])&& $parameters[0]['cmd']=='previous'){
            $id=$parameters[1]['id'];
            $channeling=Application::$app->session->get('channeling');
            $appointment_detail[0]=$OpenedChanneling->getPatient($channeling,$id,'previous');
            if(!$appointment_detail[0]){
                Application::$app->response->redirect('channeling-assistance?cmd=finish');
                return false;
            }
            $referrals = $referrralModel->getReferrals($appointment_detail[0]['patient_ID'],$appointment_detail[0]['opened_channeling_ID']);
            $reports = $reportModel->getReports($appointment_detail[0]['patient_ID'],$doctor);
            return $this->render("doctor/channeling-assistance-patient",[
                'appointment'=>$appointment_detail,
                'referrals'=>$referrals,
                'reports'=>$reports
            ]);
        }
        else if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='finish'){
            return $this->render("doctor/channeling-finish",[
                
            ]);
        }
      

    }

    public function handleReports(Request $request,Response $response){
        $this->setLayout('doctor-striped');
        $parameter=$request->getParameters();
        echo $parameter[0]['spec'];
        $consultationModel = new ConsultationReport();
        $medHistoryModel = new MedicalHistory();
        $reportModel=new ReportModel();
        if($parameter[0]['spec']??''=='referral'){
            if($parameter[1]['mod']??''=='view'){
                $referralModel=new Referral();
                $referrals=$referralModel->customFetchAll("Select * from referrel where ref_ID=".$parameter[2]['id']);
                //$referralModel->findOne(['ref_ID'=>$parameter[2]['id']]);
               
                $reportModel->openFile('./media/patient/referrals/'.$referrals[0]['name']);
            }
        }
        if(isset($parameter[0]['spec']) && $parameter[0]['spec']=="medical-history"){
            if($request->isPost()){
                $medHistoryModel->loadData($request->getBody());
                if($medHistoryModel->validate() && $medHistoryModel->addReport()){
                    //redirect
                }
            }
            return $this->render('doctor/write-history-report',[
                'model'=>$medHistoryModel
            ]);
        }
        if (isset($parameter[0]['spec']) && $parameter[0]['spec'] == "consultation-report") {
            if ($request->isPost()) {
                $consultationModel->loadData($request->getBody());
                if ($consultationModel->validate() && $consultationModel->addReport()) {
                    //redirect
                }
                //render on fall-through
            }
                return $this->render('doctor/write-consultation-report', [
                    'model' => $consultationModel
                ]);

        }
    }

    

    

   



}