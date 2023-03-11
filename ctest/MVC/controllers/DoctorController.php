<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\ConsultationReport;
use app\core\Response;
use app\models\Channeling;

use app\models\Employee;
use app\models\MedicalHistory;
use app\models\OpenedChanneling;
use app\models\Referral;
use app\core\ReportModel;
use app\models\Appointment;
use app\models\LabTestRequest;
use app\models\MedicalReport;
use app\models\Patient;
use app\models\PreChannelingTest;
use app\models\SOAPReport;

class DoctorController extends Controller{

    public function todayChannelings(Request $request,Response $response){
        Application::$app->session->set('popshow',''); 
        $parameter=$request->getParameters();
        $Channeling=new Channeling();
        $testModel= new PreChannelingTest();
        $OpenedChanneling=new OpenedChanneling();
        $Channelings=$OpenedChanneling->customFetchAll("select * from opened_channeling where channeling_ID in (select channeling_ID from channeling where doctor=".Application::$app->session->get('userObject')->nic.")");
        $ChannelingsM=$Channeling->getDocChannelings();
        $this->setLayout('doctor',['select'=>'All Channelings']);
        if(isset($parameter[0]['spec']) && $parameter[0]['spec']=='pre-channeling-test'){
            if(isset($parameter[1]['cmd']) && $parameter[1]['cmd']=='add'){
                $testID=$testModel->getIDbyName($parameter[2]['id']);
                if($testID && !$testModel->isExist($parameter[3]['channeling'],$testID)){
                    $testModel->allocateChannelingTest($testID,$parameter[3]['channeling']);
                    Application::$app->session->set('popshow',$parameter[3]['channeling']);
                }
                return $this->render('doctor/all-channelings',[
                'channeling_model'=>$Channeling,
                'channelings'=>$ChannelingsM,
                'tests'=>$testModel->getAllTests()
                
            ]);
            }
        }
        if(isset($parameter[0]['spec']) && $parameter[0]['spec']=='all'){
            $Channelings=$OpenedChanneling->customFetchAll("select * from opened_channeling where channeling_ID in (select channeling_ID from channeling where doctor=".Application::$app->session->get('userObject')->nic.")");
            return $this->render('doctor/all-channelings',[
                'channeling_model'=>$Channeling,
                'channelings'=>$ChannelingsM,
                'tests'=>$testModel->getAllTests(),
                
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
        $labRequestModel=new LabTestRequest();
        $prechannelingtest=new PreChannelingTest();
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
            $type=$appointmentMOdel->getappointmentType($patient,$id)[0]['type'];
            $testvalue=$prechannelingtest->getAssistanceValue(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling'));
            $weight=$prechannelingtest->getTestChanneling(1,Application::$app->session->get('cur_patient'));
            if($type=='consultation'){
                return $this->render("doctor/channeling-assistance-patient",[
                    'labrequests'=>$labRequestModel->getLabTestRequests(),
                    'appointment'=>$appointment_detail, 
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($patient,Application::$app->session->get('channeling')),
                    'pretestvalues'=>$testvalue,
                    'weight'=>$weight,
                    'alltests'=>$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient')),
                    
                ]);
            }
            else{
                return $this->render("doctor/channeling-assistance-patient-report",[
                    'labrequests'=>$labRequestModel->getLabTestRequests(),
                    'appointment'=>$appointment_detail, 
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($patient,Application::$app->session->get('channeling')),
                    'pretestvalues'=>$testvalue,
                    'weight'=>$weight,
                    'alltests'=>$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient'))
                    
                    
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
            $testvalue=$prechannelingtest->getAssistanceValue(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling'));
            $weight=$prechannelingtest->getTestChanneling(1,Application::$app->session->get('cur_patient'));
            if($type=='consultation'){
                return $this->render("doctor/channeling-assistance-patient",[
                    'labrequests'=>$labRequestModel->getLabTestRequests(),
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($patient,Application::$app->session->get('channeling')),
                    'pretestvalues'=>$testvalue,
                    'weight'=>$weight,
                    'alltests'=>$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient'))

                
                ]);
            }
            else{
                return $this->render("doctor/channeling-assistance-patient-report",[
                    'labrequests'=>$labRequestModel->getLabTestRequests(),
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($patient,Application::$app->session->get('channeling')),
                    'pretestvalues'=>$testvalue,
                    'weight'=>$weight,
                    'alltests'=>$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient'))
                
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
            $testvalue=$prechannelingtest->getAssistanceValue(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling'));
            $weight=$prechannelingtest->getTestChanneling(1,Application::$app->session->get('cur_patient'));
            if($appointment_type=='consultation'){
                return $this->render("doctor/channeling-assistance-patient",[
                    'labrequests'=>$labRequestModel->getLabTestRequests(),
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($appointment_detail[0]['patient_ID'],Application::$app->session->get('channeling')),
                    'pretestvalues'=>$testvalue,
                    'weight'=>$weight,
                    'alltests'=>$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient'))
                
                ]);
            }
            else{
                return $this->render("doctor/channeling-assistance-patient-report",[
                    'labrequests'=>$labRequestModel->getLabTestRequests(),
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($appointment_detail[0]['patient_ID'],Application::$app->session->get('channeling')),
                    'pretestvalues'=>$testvalue,
                    'weight'=>$weight,
                    'alltests'=>$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient'))
                
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
            $testvalue=$prechannelingtest->getAssistanceValue(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling'));
            $weight=$prechannelingtest->getTestChanneling(1,Application::$app->session->get('cur_patient'));
            if($appointment_type=='consultation'){
                return $this->render("doctor/channeling-assistance-patient",[
                    'labrequests'=>$labRequestModel->getLabTestRequests(),
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($appointment_detail[0]['patient_ID'],Application::$app->session->get('channeling')),
                    'pretestvalues'=>$testvalue,
                    'weight'=>$weight,
                    'alltests'=>$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient'))
                
                ]);
            }
            else{
                return $this->render("doctor/channeling-assistance-patient-report",[
                    'labrequests'=>$labRequestModel->getLabTestRequests(),
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($appointment_detail[0]['patient_ID'],Application::$app->session->get('channeling')),
                    'pretestvalues'=>$testvalue,
                    'weight'=>$weight,
                    'alltests'=>$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient'))
                
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
        $medicalReportModel=new MedicalReport();
        $consultationModel = new ConsultationReport();
        $medHistoryModel = new MedicalHistory();
        $channeling=new Channeling();
        $employeeModel=new Employee();
        $refModel=new Referral();
        $soapModel=new SOAPReport();
        $reportModel=new ReportModel();
        $userDoctor=Application::$app->session->get('userObject')->nic;
        $patient=Application::$app->session->get('cur_patient');
        if(isset($parameter[0]['cmd']) && $parameter[0]['cmd']=='delete' ){
            $medicalReportModel->deleteRecord(['report_ID'=>$parameter[1]['id']]);
            Application::$app->response->redirect(Application::$app->session->get('churl'));
            exit;
        }
        if(isset($parameter[0]['spec']) && $parameter[0]['spec']=='referral'){
            if(isset($parameter[1]['cmd']) && $parameter[0]['cmd']=='delete'){
                $refModel->deleteRecord(['ref_ID'=>$parameter[2]['id']]);
            }
            if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
                $referralModel=new Referral();
                $referrals=$referralModel->customFetchAll("Select * from referrel where ref_ID=".$parameter[2]['id']);
                if($referrals[0]['type']=='softcopy'){
                    $response->redirect('./media/patient/referrals/'.$referrals[0]['name']);

                }
                else{
                    $referralModel->referralToPDF($referrals[0]['ref_ID']);
                }
                //$referralModel->findOne(['ref_ID'=>$parameter[2]['id']]);
                
            }
            if ($request->isPost()) {
                $url=$request->getURL();
                $refModel->issued_doctor=$userDoctor;
                $refModel->patient=Application::$app->session->get('cur_patient');
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
            if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
                $soapModel=new SOAPReport();
                $report=$soapModel->customFetchAll("Select * from soap_report left join medical_report on soap_report.report_ID=medical_report.report_ID where medical_report.report_ID=".$parameter[2]['id']);
                if($report[0]['type']=='softcopy'){
                    $response->redirect('./media/patient//'.$report[0]['name']);

                }
                else{
                    $soapModel->SOAPreportToPDF($report[0]['report_ID']);
                }
                //$referralModel->findOne(['ref_ID'=>$parameter[2]['id']]);
                
            }
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

        if (isset($parameter[0]['spec']) && ($parameter[0]['spec'] == "consultation-report"||$parameter[0]['spec'] == "consultation") ){
            if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
                $report=$consultationModel->customFetchAll("Select * from consultation_report left join medical_report on consultation_report.report_ID=medical_report.report_ID where medical_report.report_ID=".$parameter[2]['id']);
                if($report[0]['type']=='softcopy'){
                    $response->redirect('./media/patient//'.$report[0]['name']);

                }
                else{
                    $consultationModel->ConsultationreportToPDF($report[0]['report_ID']);
                }
                //$referralModel->findOne(['ref_ID'=>$parameter[2]['id']]);
                
            }
            if ($request->isPost()){
                $medicalReportModel->loadData($request->getBody());
                $medicalReportModel->setReport('consultation','Consultation-report-'.date('Y:m:d'),$patient,$userDoctor,'e-report');
                $consultationModel->loadData($request->getBody());
                

                if ($consultationModel->validate() && $consultationModel->addReport($medicalReportModel)) {
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
            if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
                $soapModel=new SOAPReport();
                $report=$soapModel->customFetchAll("Select * from soap_report left join medical_report on soap_report.report_ID=medical_report.report_ID where medical_report.report_ID=".$parameter[2]['id']);
                if($report[0]['type']=='softcopy'){
                    $response->redirect('./media/patient//'.$report[0]['name']);

                }
                else{
                    $soapModel->SOAPreportToPDF($report[0]['report_ID']);
                }
                //$referralModel->findOne(['ref_ID'=>$parameter[2]['id']]);
                
            }
            if ($request->isPost()) {
                $soapModel->loadData($request->getBody());
                $medicalReportModel->setReport('consultation','SOAP-report-'.date('Y:m:d'),$patient,$userDoctor,'e-report');
                if ($soapModel->validate() && $soapModel->addReport($medicalReportModel)) {
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

    public function labTestRequestHandle(Request $request,Response $response){
        $parameters=$request->getParameters();
        $labTestRequestModel=new LabTestRequest();

        if($request->isPost()){
            //function to add new lab test request
            $labTestRequestModel->loadData($request->getBody());
            $labTestRequestModel->isExist($labTestRequestModel->name);
            $labTestRequestModel->createLabTestRequest($labTestRequestModel);

            //get the url in the session to reload
            Application::$app->session->set('popup','set');
            $response->redirect(Application::$app->session->get('churl'));

        }
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='delete'){
           //delete lab requests 
           $labTestRequestModel->deleteRecord(['request_ID'=>$parameters[1]['id']]);
           Application::$app->session->set('popup','set');
            $response->redirect(Application::$app->session->get('churl'));
        }

    }

  
    

    

   



}