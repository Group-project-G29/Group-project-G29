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
use app\models\Appointment;
use app\models\Patient;
use app\models\SOAPReport;

class DoctorController extends Controller{

    public function todayChannelings(Request $request,Response $response){
        $parameter=$request->getParameters();
        $Channeling=new Channeling();
        $OpenedChanneling=new OpenedChanneling();
        $Channelings=$OpenedChanneling->customFetchAll("select * from opened_channeling where channeling_ID in (select channeling_ID from channeling where doctor=".Application::$app->session->get('userObject')->nic.")");
        if(isset($parameter[0]['spec']) && $parameter[0]['spec']=='all'){
            echo "in";
            $this->setLayout('doctor',['select'=>'All Channelings']);
            $Channelings=$OpenedChanneling->customFetchAll("select * from opened_channeling where channeling_ID in (select channeling_ID from channeling where doctor=".Application::$app->session->get('userObject')->nic.")");
            return $this->render('doctor/all-channelings',[
                'channeling_model'=>$Channeling,
                'opened_channeling'=>$Channelings
            ]);
        }
        else{
            $this->setLayout('doctor',['select'=>'Today Channelings']);
            return $this->render('doctor/today-channelings',[
            'channeling_model'=>$Channeling,
            'opened_channeling'=>$Channelings

            ]);
        }
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
    public function viewPatient(Request $request){
        $patientModel=new Patient();
        $this->setLayout('doctor',['select'=>'patients']);
        $patients=$patientModel->getRecentPatientDoctor();
        return $this->render('doctor/doctor-all-patient',[
            'patients'=>$patients
        ]);
    }

    // make a report model
    public function sessionAssistance(Request $request,Response $response){
        //keep url in the seesion 
        Application::$app->session->set('churl',$request->getURL());
        
        $this->setLayout("doctor-striped");
        $parameters=$request->getParameters();
        $OpenedChanneling=new OpenedChanneling();
        Application::$app->session->get('channeling');
        $referrralModel=new Referral();
        $reportModel = new ConsultationReport();
        $appointmentMOdel=new Appointment();
        $doctor = Application::$app->session->get('userObject')->nic;

        if(isset($parameters[2]['set']) && $parameters[2]['set']=='used'){
            $appointmentMOdel->updateStatus($appointmentMOdel->getAppointment($parameters[1]['id'],Application::$app->session->get('channeling')),'used');
        }
        
        if(isset($parameters[2]['set']) && $parameters[2]['set']=='unused'){
            $appointmentMOdel->updateStatus($appointmentMOdel->getAppointment($parameters[1]['id'],Application::$app->session->get('channeling')),'unused');

        }
        //check whether the appointment is used or not
        if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='start'){
            
            $id=$parameters[1]['id']??'';
            $OpenedChanneling->customFetchAll("update opened_channeling set status='started' where opened_channeling_ID=".$id);
            $patient=$OpenedChanneling->getLastPatient("consultation",$id);
            if(!$patient){
                $patient=$OpenedChanneling->getLastPatient("labtest",$id);
            }
            //get patient in the session
            Application::$app->session->set('cur_patient',$patient);
            
            $appointment_detail=$OpenedChanneling->customFetchAll("SELECT * from appointment left join patient on patient.patient_ID=appointment.patient_ID left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where opened_channeling.opened_channeling_ID='$id' and patient.patient_ID='".$patient."'");
            //$appointment_detail=$OpenedChanneling->customFetchAll("SELECT * from appointment left join patient on patient.patient_ID=appointment.patient_ID left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where appointment.queue_no in (SELECT min(queue_no) from appointment where opened_channeling_ID=".$id.")"." and opened_channeling.opened_channeling_ID=".$id);
            if(!$appointment_detail){
                Application::$app->response->redirect('channeling-assistance?cmd=finish'); // if(!$appointment_detail){
                    Application::$app->response->redirect('channeling-assistance?cmd=finish');
                    return false;
                }
            
            Application::$app->session->set('channeling',$id);
            $referrals = $referrralModel->getReferrals($appointment_detail[0]['patient_ID'],$doctor);
            $reports = $reportModel->getReports($appointment_detail[0]['patient_ID'],$doctor);
            $type=$appointmentMOdel->getAppointmentType($patient,$id)[0]['type'];
            if($type=='consultation'){
                return $this->render("doctor/channeling-assistance-patient",[
                    'appointment'=>$appointment_detail, 
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($patient,Application::$app->session->get('channeling'))
                    
                ]);
            }
            else{
                return $this->render("doctor/channeling-assistance-patient-report",[
                    'appointment'=>$appointment_detail, 
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($patient,Application::$app->session->get('channeling'))
                    
                ]);
            }

        }
        if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='switch'){
            $id=$parameters[1]['id'];
            Application::$app->session->set('cur_patient',$id);
            $channeling=Application::$app->session->get('channeling');
            $type=$appointmentMOdel->getAppointmentType($id,$channeling)[0]['type']??'';
            $patient="";
            if($type=='labtest'){
                //get last consultation patient
                $patient=$OpenedChanneling->getLastPatient('consultation',$channeling);
                $type="consultation";
            }
            else if($type=='consultation'){
                //get last labtest patient
                $patient=$OpenedChanneling->getLastPatient('labtest',$channeling);
                $type="labtest";
            }
            $appointment_detail[0]=$OpenedChanneling->getPatient($channeling,$patient,'this',$type);
            if(!$appointment_detail[0]){
                Application::$app->response->redirect('channeling-assistance?cmd=finish');
                return false;
            }
           
            $referrals = $referrralModel->getReferrals($appointment_detail[0]['patient_ID'],$doctor);
            $reports = $reportModel->getReports($appointment_detail[0]['patient_ID'],$doctor);
            if($type=='consultation'){
                return $this->render("doctor/channeling-assistance-patient",[
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($patient,Application::$app->session->get('channeling'))
                
                ]);
            }
            else{
                return $this->render("doctor/channeling-assistance-patient-report",[
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($patient,Application::$app->session->get('channeling'))
                
                ]);
            }

        }
        if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='next'){
            $id=$parameters[1]['id'];
            $channeling=Application::$app->session->get('channeling');
            $appointment_type=$appointmentMOdel->getAppointmentType($id,$channeling)[0]['type'];
            $appointment_detail[0]=$OpenedChanneling->getPatient($channeling,$id,'next',$appointment_type);
            Application::$app->session->set('cur_patient',$appointment_detail[0]['patient_ID']);
            if(!$appointment_detail[0]){
                Application::$app->response->redirect('channeling-assistance?cmd=finish');
                return false;
            }
            $referrals = $referrralModel->getReferrals($appointment_detail[0]['patient_ID'],$doctor);
            $reports = $reportModel->getReports($appointment_detail[0]['patient_ID'],$doctor);
            if($appointment_type=='consultation'){
                return $this->render("doctor/channeling-assistance-patient",[
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($appointment_detail[0]['patient_ID'],Application::$app->session->get('channeling'))
                
                ]);
            }
            else{
                return $this->render("doctor/channeling-assistance-patient-report",[
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($appointment_detail[0]['patient_ID'],Application::$app->session->get('channeling'))
                
                ]);
            }
        }
        else if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='previous'){
            $id=$parameters[1]['id'];
            Application::$app->session->set('cur_patient',$id);
            $channeling=Application::$app->session->get('channeling');
            $appointment_type=$appointmentMOdel->getAppointmentType($id,$channeling)[0]['type'];
            $appointment_detail[0]=$OpenedChanneling->getPatient($channeling,$id,'previous',$appointment_type);
            Application::$app->session->set('cur_patient',$appointment_detail[0]['patient_ID']);
            if(!$appointment_detail[0]){
                Application::$app->response->redirect('channeling-assistance?cmd=finish');
                return false;
            }
            $referrals = $referrralModel->getReferrals($appointment_detail[0]['patient_ID'],$doctor);
            $reports = $reportModel->getReports($appointment_detail[0]['patient_ID'],$doctor);
            
            if($appointment_type=='consultation'){
                return $this->render("doctor/channeling-assistance-patient",[
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($appointment_detail[0]['patient_ID'],Application::$app->session->get('channeling'))
                
                ]);
            }
            else{
                return $this->render("doctor/channeling-assistance-patient-report",[
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($appointment_detail[0]['patient_ID'],Application::$app->session->get('channeling'))
                
                ]);
            }
        }
        else if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='finish'){
            $openedChannelingModel=new OpenedChanneling();
            $appointments=$openedChannelingModel->getAllAppointments(Application::$app->session->get('channeling'));
            return $this->render("doctor/channeling-finish",[
                'appointments'=>$appointments
            ]);
        }
      

    }

    public function handleReports(Request $request,Response $response){
        $this->setLayout('doctor-striped');
        $parameter=$request->getParameters();
        $consultationModel = new ConsultationReport();
        $medHistoryModel = new MedicalHistory();
        $channeling=new Channeling();
        $employeeModel=new Employee();
        $refModel=new Referral();
        $soapModel=new SOAPReport();
        $reportModel=new ReportModel();
        $userDoctor=Application::$app->session->get('userObject')->nic;
        $patient=Application::$app->session->get('cur_patient');
        if(isset($parameter[0]['spec']) && $parameter[0]['spec']=='referral'){
            if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
                $referralModel=new Referral();
                $referrals=$referralModel->customFetchAll("Select * from referrel where ref_ID=".$parameter[2]['id']);
                //$referralModel->findOne(['ref_ID'=>$parameter[2]['id']]);
                
                $response->redirect('./media/patient/referrals/'.$referrals[0]['name']);
            }
            if ($request->isPost()) {
                $url=$request->getURL();
                $refModel->issued_doctor=$userDoctor;
                $refModel->patient=Application::$app->session->get('cur_patient');
                echo $refModel->patient;
                $refModel->type='e-referral';
                $refModel->loadData($request->getBody());
                if ($refModel->validate() && $refModel->addReferral()) {
                    Application::$app->response->redirect(Application::$app->session->get('churl'));
                    exit;
                }
                //render on fall-through
            }
            $doctors=$employeeModel->getDoctors();
            $specialities=$channeling->getSpecialities();
            return $this->render('doctor/write-referral-report', [
                'model' => $refModel,
                'doctors'=>$doctors,
                'specialities'=>$specialities
            ]);
        }
        if(isset($parameter[0]['spec']) && $parameter[0]['spec']=="medical-history-report"){
            if($request->isPost()){
                $medHistoryModel->loadData($request->getBody());
                $medHistoryModel->setReport('medical-history','Medical-history-'.date('Y:m:d'),$patient,$userDoctor,'e-report');
                if($medHistoryModel->validate() && $medHistoryModel->addReport()){
                    //redirect
                    Application::$app->response->redirect(Application::$app->session->get('churl'));
                    exit;
                }
            }
            return $this->render('doctor/write-history-report',[
                'model'=>$medHistoryModel
            ]);
        }
        if (isset($parameter[0]['spec']) && $parameter[0]['spec'] == "consultation-report") {
            if ($request->isPost()) {
                $consultationModel->loadData($request->getBody());
                $consultationModel->setReport('consultation','Consultation-report-'.date('Y:m:d'),$patient,$userDoctor,'e-report');

                if ($consultationModel->validate() && $consultationModel->addReport()) {
                    //redirect
                    Application::$app->response->redirect(Application::$app->session->get('churl'));
                    exit;
                }
                //render on fall-through
            }
                return $this->render('doctor/write-consultation-report', [
                    'model' => $consultationModel
                ]);

        }
        if (isset($parameter[0]['spec']) && $parameter[0]['spec'] == "soap-report") {
            if ($request->isPost()) {
                $soapModel->loadData($request->getBody());
                $soapModel->setReport('medical-history','S.O.P-'.date('Y:m:d'),$patient,$userDoctor,'e-report');
                if ($soapModel->validate() && $soapModel->addReport()) {
                    //redirect
                    Application::$app->response->redirect(Application::$app->session->get('churl'));
                    exit;
                }
                //render on fall-through
            }
                return $this->render('doctor/write-soap-report', [
                    'model' => $soapModel
                ]);

        }
        
    }

    public function handlePrescription(Request $request,Response $response){
        $this->setLayout('doctor-striped');
        return $this->render('doctor/write-prescription',[

        ]);
    }

    

    

   



}