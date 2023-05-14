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
use app\core\SummaryReports;
use app\models\AdminNotification;
use app\models\Appointment;
use app\models\LabReport;
use app\models\LabTestRequest;
use app\models\MedicalReport;
use app\models\Medicine;
use app\models\Order;
use app\models\Patient;
use app\models\PreChannelingTest;
use app\models\Prescription;
use app\models\SOAPReport;

class DoctorController extends Controller{

    public function mover(Request $request,Response $response){
        $response->redirect(Application::$app->session->get('churl'));
        exit;
    }

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
                
                $testID=$testModel->getIDbyName(urldecode($parameter[2]['id']));
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
        Application::$app->session->set('popup','unset');
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

    // doctor live session assistance
    public function sessionAssistance(Request $request,Response $response){
        //keep url in the seesion 
        //if session variable is no there redirect to last seen patient
        
        $parameters=$request->getParameters();
        // if(!Application::$app->session->get('cur_patient') && $parameters['cmd']!='start' && $parameters['cmd']!='finish'){
            //     $response->redirect("channeling-assistance?cmd=start");
            //     return false;
        // }
        $this->setLayout("doctor-striped");
        $OpenedChanneling=new OpenedChanneling();
        $patientModel=new Patient();
        Application::$app->session->get('channeling');
        $referrralModel=new Referral();
        $reportModel = new ConsultationReport();
        $appointmentMOdel=new Appointment();
        $labRequestModel=new LabTestRequest();
        $prechannelingtest=new PreChannelingTest();
        $medicalReport=new MedicalReport();
        $doctor = Application::$app->session->get('userObject')->nic;
        if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='channeling-finish'){
            $OpenedChanneling->finish(Application::$app->session->get('channeling'));
            $response->redirect('channeling?channeling='.Application::$app->session->get('channeling'));
            exit;
        }
        $prescriptionModel=new Prescription();
        $labreportModel=new LabReport();
        //check if there is a patient no
        
    
       
        Application::$app->session->set('churl',$request->getURL());
        if(isset($parameters[2]['set']) && $parameters[2]['set']=='used'){
            if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='move'){
                $appointmentMOdel->updateStatus($appointmentMOdel->getappointment(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling')),'used');
            }
            else{
                $appointmentMOdel->updateStatus($appointmentMOdel->getAppointment($parameters[1]['id'],Application::$app->session->get('channeling')),'used');
            }
        }
        
        if(isset($parameters[2]['set']) && $parameters[2]['set']=='unused'){
            if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='move'){
                $appointmentMOdel->updateStatus($appointmentMOdel->getAppointment(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling')),'unused');
                
            }
            else{
                $appointmentMOdel->updateStatus($appointmentMOdel->getAppointment($parameters[1]['id'],Application::$app->session->get('channeling')),'unused');
            }
            
        }

        //check whether the appointment is used or not
        if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='start'){
    
            $id=$parameters[1]['id']??'';
            $ch=$OpenedChanneling->findOne(['opened_channeling_ID'=>$id]); 
             if($ch->status!='finished'){

                $OpenedChanneling->customFetchAll("update opened_channeling set status='started' where opened_channeling_ID=".$id);
            }
            $patient=$OpenedChanneling->getLastPatient("consultation",$id);
            if(!$patient){
                $patient=$OpenedChanneling->getLastPatient("labtest",$id);
              
            }   
            //get patient in the session
            
            $appointment_detail=$OpenedChanneling->customFetchAll("SELECT * from appointment left join patient on patient.patient_ID=appointment.patient_ID left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where opened_channeling.opened_channeling_ID='$id' and patient.patient_ID='".$patient."'");
            //$appointment_detail=$OpenedChanneling->customFetchAll("SELECT * from appointment left join patient on patient.patient_ID=appointment.patient_ID left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where appointment.queue_no in (SELECT min(queue_no) from appointment where opened_channeling_ID=".$id.")"." and opened_channeling.opened_channeling_ID=".$id);
            if(!$appointment_detail){
                Application::$app->response->redirect('channeling-assistance?cmd=finish'); // if(!$appointment_detail){
                    Application::$app->response->redirect('channeling-assistance?cmd=finish');
                    return false;
                }
            else{
                Application::$app->session->set('cur_patient',$patient);

            }
            
            Application::$app->session->set('channeling',$id);
            $referrals = $referrralModel->getReferrals($patient,$doctor);
            $reports = $reportModel->getReports($appointment_detail[0]['patient_ID'],$doctor);
            $type=$appointmentMOdel->getappointmentType($patient,$id)[0]['type'];
            $testvalue=$prechannelingtest->getAssistanceValue(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling'));
            $weight=$prechannelingtest->getTestChanneling(1,Application::$app->session->get('cur_patient'));
            $labreports=$labreportModel->getAllLabReports(Application::$app->session->get('cur_patient'));
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
                    'recent'=>$medicalReport->getRecentReports(Application::$app->session->get('cur_patient'),$doctor),
                    'prescription'=>$prescriptionModel->getPrescriptionByPatient([Application::$app->session->get('cur_patient'),$doctor]),
                    'labreports'=>$labreports
                    
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
                    'alltests'=>$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient')),
                    'recent'=>$medicalReport->getRecentReports(Application::$app->session->get('cur_patient'),$doctor),
                    'prescription'=>$prescriptionModel->getPrescriptionByPatient([Application::$app->session->get('cur_patient'),$doctor]),
                    'labreports'=>$labreports
                    
                ]);
            }

        }
        if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='switch'){
            $id=$parameters[1]['id'];
            $channeling=Application::$app->session->get('channeling');
            $type=$appointmentMOdel->getAppointmentType($id,$channeling)[0]['type']??'';
            $patient="";
            if($type=='labtest'){
                //get last consultation patient
                $patient=$OpenedChanneling->getLastPatient('consultation',$channeling);
                
                $type="consultation";
                Application::$app->session->set('cur_patient',$patient);
            }
            else if($type=='consultation'){
                //get last labtest patient
                $patient=$OpenedChanneling->getLastPatient('labtest',$channeling);
                $type="labtest";
                Application::$app->session->set('cur_patient',$patient);
            }
            $appointment_detail[0]=$OpenedChanneling->getPatient($channeling,$patient,'this',$type);
            if(!$appointment_detail[0]){
                Application::$app->response->redirect('channeling-assistance?cmd=finish');
                return false;
            }
            else{
                Application::$app->session->set('cur_patient',$patient);

            }
           
            $referrals = $referrralModel->getReferrals($appointment_detail[0]['patient_ID'],$doctor);

            $reports = $reportModel->getReports($appointment_detail[0]['patient_ID'],$doctor);
            $testvalue=$prechannelingtest->getAssistanceValue(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling'));
            $weight=$prechannelingtest->getTestChanneling(1,Application::$app->session->get('cur_patient'));
            $labreports=$labreportModel->getAllLabReports(Application::$app->session->get('cur_patient'));
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
                    'recent'=>$medicalReport->getRecentReports(Application::$app->session->get('cur_patient'),$doctor),
                    'prescription'=>$prescriptionModel->getPrescriptionByPatient([Application::$app->session->get('cur_patient'),$doctor]),
                    'labreports'=>$labreports

                
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
                    'alltests'=>$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient')),
                    'recent'=>$medicalReport->getRecentReports(Application::$app->session->get('cur_patient'),$doctor),
                    'prescription'=>$prescriptionModel->getPrescriptionByPatient([Application::$app->session->get('cur_patient'),$doctor]),
                    'labreports'=>$labreports
                
                ]);
            }

        }
        if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='next'){
            $id=$parameters[1]['id'];
            $channeling=Application::$app->session->get('channeling');
            $appointment_type=$appointmentMOdel->getAppointmentType($id,$channeling)[0]['type'];
            $appointment_detail[0]=$OpenedChanneling->getPatient($channeling,$id,'next',$appointment_type);
            if(!$appointment_detail[0]){
                Application::$app->response->redirect('channeling-assistance?cmd=finish');
                return false;
            }
            else{
                Application::$app->session->set('cur_patient',$appointment_detail[0]['patient_ID']);
            }
            $referrals = $referrralModel->getReferrals($appointment_detail[0]['patient_ID'],$doctor);
            $reports = $reportModel->getReports($appointment_detail[0]['patient_ID'],$doctor);
            $testvalue=$prechannelingtest->getAssistanceValue(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling'));
            $weight=$prechannelingtest->getTestChanneling(1,Application::$app->session->get('cur_patient'));
            $alltests=$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient'));
            $labreports=$labreportModel->getAllLabReports(Application::$app->session->get('cur_patient'));
            if($appointment_type=='consultation'){
                return $this->render("doctor/channeling-assistance-patient",[
                    'labrequests'=>$labRequestModel->getLabTestRequests(),
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($appointment_detail[0]['patient_ID'],Application::$app->session->get('channeling')),
                    'pretestvalues'=>$testvalue,
                    'weight'=>$weight,
                    'alltests'=>$alltests,
                    'recent'=>$medicalReport->getRecentReports(Application::$app->session->get('cur_patient'),$doctor),
                    'prescription'=>$prescriptionModel->getPrescriptionByPatient([Application::$app->session->get('cur_patient'),$doctor]),
                    'labreports'=>$labreports
                
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
                    'alltests'=>$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient')),
                    'recent'=>$medicalReport->getRecentReports(Application::$app->session->get('cur_patient'),$doctor),
                    'prescription'=>$prescriptionModel->getPrescriptionByPatient([Application::$app->session->get('cur_patient'),$doctor]),
                    'labreports'=>$labreports
                
                ]);
            }
        }
        else if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='previous'){
            $id=$parameters[1]['id'];
            Application::$app->session->set('cur_patient',$id);
            $channeling=Application::$app->session->get('channeling');
            $appointment_type=$appointmentMOdel->getAppointmentType($id,$channeling)[0]['type'];
            $appointment_detail[0]=$OpenedChanneling->getPatient($channeling,$id,'previous',$appointment_type);
           
            if(!$appointment_detail[0]){
                Application::$app->response->redirect('channeling-assistance?cmd=finish');
                return false;
            }
            else{
                Application::$app->session->set('cur_patient',$appointment_detail[0]['patient_ID']);
            }
            $referrals = $referrralModel->getReferrals($appointment_detail[0]['patient_ID'],$doctor);
            $reports = $reportModel->getReports($appointment_detail[0]['patient_ID'],$doctor);
            $testvalue=$prechannelingtest->getAssistanceValue(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling'));
            $weight=$prechannelingtest->getTestChanneling(1,Application::$app->session->get('cur_patient'));
            $labreports=$labreportModel->getAllLabReports(Application::$app->session->get('cur_patient'));
            if($appointment_type=='consultation'){
                return $this->render("doctor/channeling-assistance-patient",[
                    'labrequests'=>$labRequestModel->getLabTestRequests(),
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($appointment_detail[0]['patient_ID'],Application::$app->session->get('channeling')),
                    'pretestvalues'=>$testvalue,
                    'weight'=>$weight,
                    'alltests'=>$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient')),
                    'recent'=>$medicalReport->getRecentReports(Application::$app->session->get('cur_patient'),$doctor),
                    'prescription'=>$prescriptionModel->getPrescriptionByPatient([Application::$app->session->get('cur_patient'),$doctor]),
                    'labreports'=>$labreports
                
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
                    'alltests'=>$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient')),
                    'recent'=>$medicalReport->getRecentReports(Application::$app->session->get('cur_patient'),$doctor),
                    'prescription'=>$prescriptionModel->getPrescriptionByPatient([Application::$app->session->get('cur_patient'),$doctor]),
                    'labreports'=>$labreports
                
                ]);
            }
        }
        else if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='move'){
            $openedChannelingModel=new OpenedChanneling();
            $id=$openedChannelingModel->getPatientByQ($parameters[1]['id']);
            if(!$id){
                $response->redirect('channeling-assistance?cmd=start&id='.Application::$app->session->get('channeling'));
                exit;
            }
            $channeling=Application::$app->session->get('channeling');
            $appointment_type=$appointmentMOdel->getAppointmentType($id,$channeling)[0]['type']??'';
            $appointment_detail[0]=$OpenedChanneling->getAPatient($channeling,$id);
            if(!$appointment_detail){
                $response->redirect('channeling-assistance?cmd=start&id='.Application::$app->session->get('channeling'));
                exit;
            }
            
            Application::$app->session->set('cur_patient',$id);
            
            $referrals = $referrralModel->getReferrals($appointment_detail[0]['patient_ID'],$doctor);
            $reports = $reportModel->getReports($appointment_detail[0]['patient_ID'],$doctor);
            $testvalue=$prechannelingtest->getAssistanceValue(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling'));
            $weight=$prechannelingtest->getTestChanneling(1,Application::$app->session->get('cur_patient'));
            $alltests=$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient'));
            $labreports=$labreportModel->getAllLabReports(Application::$app->session->get('cur_patient'));
            if($appointment_type=='consultation'){
                return $this->render("doctor/channeling-assistance-patient",[
                    'labrequests'=>$labRequestModel->getLabTestRequests(),
                    'appointment'=>$appointment_detail,
                    'referrals'=>$referrals,
                    'reports'=>$reports,
                    'status'=>$appointmentMOdel->getAppointmentStatus($appointment_detail[0]['patient_ID'],Application::$app->session->get('channeling')),
                    'pretestvalues'=>$testvalue,
                    'weight'=>$weight,
                    'alltests'=>$alltests,
                    'recent'=>$medicalReport->getRecentReports(Application::$app->session->get('cur_patient'),$doctor),
                    'prescription'=>$prescriptionModel->getPrescriptionByPatient([Application::$app->session->get('cur_patient'),$doctor]),
                    'labreports'=>$labreports
                
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
                    'alltests'=>$prechannelingtest->mainGetAllTestValues(Application::$app->session->get('cur_patient')),
                    'recent'=>$medicalReport->getRecentReports(Application::$app->session->get('cur_patient'),$doctor),
                    'prescription'=>$prescriptionModel->getPrescriptionByPatient([Application::$app->session->get('cur_patient'),$doctor]),
                    'labreports'=>$labreports
                
                ]);
            }
        }


        else if(isSet($parameters[0]['cmd']) && $parameters[0]['cmd']=='finish'){
            $openedChannelingModel=new OpenedChanneling();
            $appointments=$openedChannelingModel->getAllAppointmentsPatch(Application::$app->session->get('channeling'));
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
            $type=$medicalReportModel->fetchAssocAll(['report_ID'=>$parameter[1]['id']])[0]['type'];
            $medicalReportModel->deleteRecord(['report_ID'=>$parameter[1]['id']]);
            $response->redirect('doctor-report?spec='.$type);
            exit;
        }
        if(isset($parameter[0]['spec']) && $parameter[0]['spec']=='referral'){
            if(isset($parameter[1]['cmd']) && $parameter[1]['cmd']=='delete'){
                $refModel->deleteRecord(['ref_ID'=>$parameter[2]['id']]);
                $response->redirect('doctor-report?spec=referral');
                exit;
            }
            if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
                $referralModel=new Referral();
                $referralModel->setseen($parameter[2]['id']);
                $referrals=$referralModel->customFetchAll("Select * from referrel where ref_ID=".$parameter[2]['id']);
                if($referrals[0]['type']=='softcopy'){
                    $response->redirect('http://localhost/ctest/MVC/public/media/patient/referrals/'.$referrals[0]['name']);

                    
                }
                else{
                    $referralModel->referralToPDF($referrals[0]['ref_ID']);
                }
                //$referralModel->findOne(['ref_ID'=>$parameter[2]['id']]);
                
            }
            if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='update'){
                $referrals = $refModel->getReferrals($patient,$userDoctor);
                $doctors=$employeeModel->getDoctors();
                $specialities=$channeling->getSpecialities();
                if ($request->isPost()) {
                
                $refModel->issued_doctor=$userDoctor;
                $refModel->patient=Application::$app->session->get('cur_patient');
                $refModel->type='e-referral';
                $refModel->loadData($request->getBody());
                if ($refModel->validate() && $refModel->updateRecord(['ref_ID'=>$parameter[2]['id']])) {
                    return $this->render('doctor/update-referral-report',[
                        '_referral'=>$refModel->findOne(['ref_ID'=>$parameter[2]['id']]),
                        'referrals'=>$referrals,
                        'doctors'=>$doctors,
                        'specialities'=>$specialities,
                    ]);
                }
                //render on fall-through
                }
                $ref= $refModel->findOne(['ref_ID'=>$parameter[2]['id']]);
                if(!$ref){
                     $response->redirect('doctor-report?spec=referral');
                     exit;
                }
                return $this->render('doctor/update-referral-report',[
                    '_referral'=>$refModel->findOne(['ref_ID'=>$parameter[2]['id']]),
                    'referrals'=>$referrals,
                    'doctors'=>$doctors,
                    'specialities'=>$specialities,
                ]);
            }
            
            if ($request->isPost()) {
                $url=$request->getURL();
                $refModel->issued_doctor=$userDoctor;
                $refModel->patient=Application::$app->session->get('cur_patient');
                $refModel->type='e-referral';
                $refModel->loadData($request->getBody());
                $doctors=$employeeModel->getDoctors();
                $specialities=$channeling->getSpecialities();
                if ($refModel->validate() && $refModel->addReferral()) {
                    $doctors=$employeeModel->getDoctors();
                    $specialities=$channeling->getSpecialities();
                    $referrals = $refModel->getReferrals($patient,$userDoctor);
                    return $this->render('doctor/write-referral-report', [
                        'model' => $refModel,
                        'doctors'=>$doctors,
                        'specialities'=>$specialities,
                        'referrals'=>$referrals
                    ]);   
                
                }
            }
            $doctors=$employeeModel->getDoctors();
            $specialities=$channeling->getSpecialities();
            $referrals = $refModel->getReferrals($patient,$userDoctor);
            return $this->render('doctor/write-referral-report', [
                'model' => $refModel,
                'doctors'=>$doctors,
                'specialities'=>$specialities,
                'referrals'=>$referrals
            ]);
        }
        if(isset($parameter[0]['spec']) && $parameter[0]['spec']=="medical-history"||$parameter[0]['spec']=="medical-history-report"){
            if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
                $historyModel=new MedicalHistory();
                $report=$soapModel->customFetchAll("Select * from Medical_history left join medical_report on Medical_history.report_ID=medical_report.report_ID where medical_report.report_ID=".$parameter[2]['id']);
                if($report[0]['type']=='softcopy'){
                    $response->redirect('./media/patient//'.$report[0]['name']);

                }
                else{
                    $historyModel->HistoryreportToPDF($report[0]['report_ID']);
                }
                //$referralModel->findOne(['ref_ID'=>$parameter[2]['id']]);
                
            }
            if($request->isPost()){
                $medHistoryModel->loadData($request->getBody());
                $medicalReportModel->loadData($request->getBody());
                $medicalReportModel->setReport('medical-history','Medical-history-'.date('Y:m:d'),$patient,$userDoctor,'e-report');
                if($medHistoryModel->validate() && $medHistoryModel->addReport($medicalReportModel)){
                    
                }
            }
            return $this->render('doctor/write-history-report',[
                'model'=>$medHistoryModel,
                'todayreport'=>$medicalReportModel->getAllReportsDoctor()
            ]);
        }

        if (isset($parameter[0]['spec']) && ($parameter[0]['spec'] == "consultation-report"||$parameter[0]['spec'] == "consultation") ){
            if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
                $report=$consultationModel->customFetchAll("Select * from consultation_report left join medical_report on consultation_report.report_ID=medical_report.report_ID where medical_report.report_ID=".$parameter[2]['id']);
                if($report[0]['type']=='softcopy'){
                    $response->redirect('localhost/ctest/MVC/public/media/patient/medicalreports/'.$report[0]['name']);

                }
                else{
                    $consultationModel->ConsultationreportToPDF($report[0]['report_ID']);
                } 
            }
            if ($request->isPost()){
                $medicalReportModel->loadData($request->getBody());
                $medicalReportModel->setReport('consultation','Consultation-report-'.date('Y:m:d'),$patient,$userDoctor,'e-report');
                $consultationModel->loadData($request->getBody());
                

                if ($consultationModel->validate() && $consultationModel->addReport($medicalReportModel)) {
                    //redirect
                   
                }
                //render on fall-through
            }
            return $this->render('doctor/write-consultation-report', [
                'model' => $consultationModel,
                'todayreport'=>$medicalReportModel->getAllReportsDoctor()
            ]);

        }
        if (isset($parameter[0]['spec']) && $parameter[0]['spec'] == "soap-report" ||$parameter[0]['spec'] == "soap") {
            if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
                $soapModel=new SOAPReport();
                $report=$soapModel->customFetchAll("Select * from soap_report left join medical_report on soap_report.report_ID=medical_report.report_ID where medical_report.report_ID=".$parameter[2]['id']);
                if($report[0]['type']=='softcopy'){
                    $response->redirect('localhost/ctest/MVC/public/media/patient/medicalreports/'.$report[0]['name']);

                }
                else{
                    $soapModel->SOAPreportToPDF($report[0]['report_ID']);
                }
                //$referralModel->findOne(['ref_ID'=>$parameter[2]['id']]);
                
            }
            if ($request->isPost()) {
                $soapModel->loadData($request->getBody());
                $medicalReportModel->setReport('soap','SOAP-report-'.date('Y:m:d'),$patient,$userDoctor,'e-report');
                if ($soapModel->validate() && $soapModel->addReport($medicalReportModel)) {
                  
                }
                //render on fall-through
            }
                return $this->render('doctor/write-soap-report', [
                    'model' => $soapModel,
                    'todayreport'=>$medicalReportModel->getAllReportsDoctor()
                ]);

        }
        else if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
               $medicalModel=new MedicalReport();
               $medicalReport=$medicalModel->fetchAssocAll(['report_ID'=>$parameter[2]['id']]);
               $response->redirect('/ctest/MVC/public/media/patient/medicalreports/'.$medicalReport[0]['label']);        
            }
            
        if(isset($parameter[0]['mod']) && $parameter[0]['mod']=='update'){
   
            $type=$medicalReportModel->fetchAssocAll(['report_ID'=>$parameter[1]['id']])[0]['type'];
            $medicalModel=new MedicalReport();
            var_dump($medicalModel->findOne(['report_ID'=>$parameter[1]['id']]));
            if($request->isPost()){
                if($type=='consultation'){
                $model=new ConsultationReport();
                $model->loadData($request->getBody());
                $medicalModel->updater($model,$type,$parameter[1]['id']);
                $report=$model->findOne(['report_ID'=>$parameter[1]['id']]);
                return $this->render('doctor/update-consultation-report',[
                    'model'=>$report,
                    'todayreport'=>$medicalReportModel->getAllReportsDoctor()
                ]);
            }
            if($type=='medical-history'){
                $model=new MedicalHistory();
                $model->loadData($request->getBody());
                $medicalModel->updater($model,$type,$parameter[1]['id']);
                $report=$model->findOne(['report_ID'=>$parameter[1]['id']]);
                return $this->render('doctor/update-history-report',[
                    'model'=>$report,
                    'todayreport'=>$medicalReportModel->getAllReportsDoctor()
                ]);
            }
            if($type=='soap'){
                $model=new SOAPReport();
                $model->loadData($request->getBody());
                $medicalModel->updater($model,$type,$parameter[1]['id']);
                $report=$model->findOne(['report_ID'=>$parameter[1]['id']]);
                return $this->render('doctor/update-soap-report',[
                    'model'=>$report,
                    'todayreport'=>$medicalReportModel->getAllReportsDoctor()
                ]);
            }
            }
            if($type=='consultation'){
                $model=new ConsultationReport();
                $report=$model->findOne(['report_ID'=>$parameter[1]['id']]);
                return $this->render('doctor/update-consultation-report',[
                    'model'=>$report,
                    'todayreport'=>$medicalReportModel->getAllReportsDoctor()
                ]);
            }
            if($type=='medical-history'){
                $model=new MedicalHistory();
                $report=$model->findOne(['report_ID'=>$parameter[1]['id']]);
                return $this->render('doctor/update-history-report',[
                    'model'=>$report,
                    'todayreport'=>$medicalReportModel->getAllReportsDoctor()
                ]);
            }
            if($type=='soap'){
                $model=new SOAPReport();
                $report=$model->findOne(['report_ID'=>$parameter[1]['id']]);
                return $this->render('doctor/update-soap-report',[
                    'model'=>$report,
                    'todayreport'=>$medicalReportModel->getAllReportsDoctor()
                ]);
            }

        }
        
        
    
        
    }

    public function labTestRequestHandle(Request $request,Response $response){
        $parameters=$request->getParameters();
        $labTestRequestModel=new LabTestRequest();
    
        if($request->isPost()){
            //function to add new lab test request
            Application::$app->session->set('popup','set');
            $labTestRequestModel->loadData($request->getBody());
            if(!$labTestRequestModel->name){
                Application::$app->response->redirect(Application::$app->session->get('churl'));
                exit;
            } 
            if($labTestRequestModel->isThereTest(urldecode($labTestRequestModel->name))){
                $labTestRequestModel->isExist(urldecode($labTestRequestModel->name));    
                $labTestRequestModel->createLabTestRequest($labTestRequestModel);
            }

            //get the url in the session to reload
            $response->redirect(Application::$app->session->get('churl'));
            exit;

        }
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='delete'){
           //delete lab requests 
           $labTestRequestModel->deleteRecord(['request_ID'=>$parameters[1]['id']]);
           Application::$app->session->set('popup','set');
            $response->redirect(Application::$app->session->get('churl'));
            exit;
        }

    }
    public function handleLabReports(Request $request,Response $response){
        $MedicalReport=new MedicalReport();
        if($request->isPost()){
            $MedicalReport->addLabReportSoftCopy($request);
            //$response->redirect(Application::$app->session->get('churl'));
        }
    }

    //take prescriptoin medicine and show it in PDF format
    public function handlePrescription(Request $request,Response $response){
        $this->setLayout('doctor-striped');
        $patientModel=new Patient();
        $medicinesModel=new Medicine();
        $medicines=$medicinesModel->getAllMedicine();
        $prescriptionModel=new Prescription();
        $prescription=$prescriptionModel->isTherePrescription(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling'));
        $presmeds=[];
        $presdevice=[]; 
        $parameters=$request->getParameters();
        //show the prescription

        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='send'){
            $order=new Order();
            $order->pickup_status='pickup';
            $order->patient_ID=Application::$app->session->get('cur_patient');
            $order->cart_ID=null;
            $order->delivery_ID=null;
            $order->name=$patientModel->fetchAssocAll(['patient_ID'=>$order->patient_ID])[0]['name'];
            $order->address=$patientModel->fetchAssocAll(['patient_ID'=>$order->patient_ID])[0]['address'];
            $order->contact=$patientModel->fetchAssocAll(['patient_ID'=>$order->patient_ID])[0]['contact'];
            $order_ID=$order->savenofiles()[0]['last_insert_id()'];
            $prescriptionModel->customFetchAll("update prescription set order_ID=$order_ID where prescription_ID=".$prescriptionModel->isTherePrescription(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling')));
            $response->redirect("doctor-prescription");
            exit;
        }

        if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='delete'){
            $pid=$prescriptionModel->isTherePrescription(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling'));
            if($prescriptionModel->fetchAssocAll(['prescription_ID'=>$parameters[2]['id']]));{

                $prescriptionModel->deleteRecordByName(['prescription_ID'=>$pid,'med_ID'=>$parameters[2]['id']],'prescription_medicine');
            }
            $response->redirect('doctor-prescription');
        }
        if(isset($parameters[1]['mod']) && $parameters[1]['mod']=='view'){
                $report=$prescriptionModel->fetchAssocAll(['prescription_ID'=>$parameters[2]['id']]);
                // if($report[0]['type']=='softcopy'){
                //     $response->redirect('./media/patient//'.$report[0]['name']);

                // }
                //else{
                    $prescriptionModel->prescriptionToPDF($report[0]['prescription_ID']);
                    exit;
                //}
                
        }
        if($prescription){
            $presmeds=$prescriptionModel->getPrescriptionMedicine($prescription);
            $presdevice=$prescriptionModel->getPrescriptionDevice($prescription);
        }
        
        if($request->isPost()){

            //take current prescritption or create new one add medicine to it
            $prescription=$prescriptionModel->addPrescriptionMedicine(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling')); 
            $prescriptionModel->note=$prescription[0];
            $prescriptionModel->refills=0;
            $medicines=$medicinesModel->getAllMedicine();
            if($prescription){
                $presmeds=$prescriptionModel->getPrescriptionMedicine($prescription[2]);
                $presdevice=$prescriptionModel->getPrescriptionDevice($prescription[2]);
            }
            
           return $this->render('doctor/write-prescription',[
            'medicines'=>$medicines,
            'prescription_medicine'=>$presmeds,
            'prescription_device'=>$presdevice,
            'prescriptionModel'=>$prescriptionModel

        ]);
        }
        
        $medicines=$medicinesModel->getAllMedicine();
        return $this->render('doctor/write-prescription',[
            'medicines'=>$medicines,
            'prescription_medicine'=>$presmeds,
            'prescription_device'=>$presdevice,
            'prescriptionModel'=>$prescriptionModel

        ]);
    }
    public function handleNotis(Request $request,Response $response){
        $parameters=$request->getParameters();
        $this->setLayout('doctor',['select'=>'All Channelings']);
        $Channeling=new Channeling();
        $adminNotification=new AdminNotification();
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='clear' ){
            $adminNotification->makeRead($parameters[1]['id']);
        }
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='open'){
            $adminNotification->chOpenNoti($parameters[1]['id']);
        }
        else if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='cancel'){
            $adminNotification->chCancelNoti($parameters[1]['id']);
        }
        else if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='close'){
            $adminNotification->chCloseNoti($parameters[1]['id']);
            
        }
         
        $ChannelingsM=$Channeling->getDocChannelings();
        $testModel= new PreChannelingTest();
        return $this->render('doctor/all-channelings',[
            'channeling_model'=>$Channeling,
            'channelings'=>$ChannelingsM,
            'tests'=>$testModel->getAllTests()
                
        ]);
    }


    public function summaryReports(Request $request,Response $response){
        $employeeModel=new Employee();
        $channelingModel=new Channeling();
        $parameters=$request->getParameters();
        
        if(isset($parameters[0]['spec']) && $parameters[0]['spec']=='channeling_report'){
            $summaryReport=new SummaryReports();
            $summaryReport->pastChanneling(Application::$app->session->get('userObject')->nic);
            exit;
        }
        $this->setLayout('doctor',['select'=>'Report']);
        return $this->render('doctor/reports',[
            'patients'=>$employeeModel->getThisMonthPatients(Application::$app->session->get('userObject')->nic),
            'channelingsCount'=>sizeof($employeeModel->growthOfPatients(Application::$app->session->get('userObject')->nic)),
            'income'=>$employeeModel->calcuateThisMonthIncome(Application::$app->session->get('userObject')->nic),
            'patientchart'=>$employeeModel->growthOfPatients(Application::$app->session->get('userObject')->nic),
            'incomechart'=>$employeeModel->growthOfIncome(Application::$app->session->get('userObject')->nic),
            'eachchanneling'=>$channelingModel->patientCountbyChanneling(),
            'comparison'=>$channelingModel->appointmentComparison()

        ]);

        
    }
    public function myDetail(Request $request,Response $response){
        $this->setLayout('doctor',['select'=>'My Detail']);
        $parameters=$request->getParameters();
        $model=new Employee();
        $model->nic=Application::$app->session->get('userObject')->nic;
        $result=$model->customFetchAll("select * from employee left join doctor on doctor.nic=employee.nic where doctor.nic=".Application::$app->session->get('userObject')->nic)[0];
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='view'){
            return $this->render('doctor/mydetail',[
                'user'=>$result
            ]);
        }
      
        if($request->isPost()){
            $model->loadData($request->getBody());
            $model->loadFiles($_FILES);
            $model->nic=Application::$app->session->get('userObject')->nic;
            if($model->updateDoctorRecord(Application::$app->session->get('userObject')->nic)){
                return $this->render('doctor/update-doctor-detail',[
                    'me'=>$model
                ]);
            }
            
        }
        if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
            $result=$model->findOne(['nic'=>Application::$app->session->get('userObject')->nic]);
            return $this->render('doctor/update-doctor-detail',[
                'me'=>$result
            ]);
        }
    }
    
}