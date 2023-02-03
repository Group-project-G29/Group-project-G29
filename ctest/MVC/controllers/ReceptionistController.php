<?php
namespace app\controllers;

use app\controllers\PatientAuthController;
use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\Appointment;
use app\models\Channeling;
use app\models\Employee;
use app\models\Patient;
use app\models\Referral;
use app\models\OpenedChanneling;

    class ReceptionistController extends Controller{

        // view all patient go here
        public function handlePatient(Request $request,Response $response){
            $parameters=$request->getParameters();
            $patientModel=new Patient();
            $this->setLayout('receptionist',['select'=>'Patients']);
            if(isSet($parameters[0]['mod']) && $parameters[0]['mod']=='view'){
                if(isSet($parameters[1]['id'])){
                    $patient=$patientModel->fetchAssocOne(['patient_ID'=>$parameters[1]['id']]);
                    return $this->render("receptionist/patien-detail",[
                        'patient'=>$patient
                    ]);
                }
                $patients=$patientModel->customFetchall("Select * from patient");
                
                return $this->render("receptionist/all-patient",[
                    'patients'=>$patients
                ]);
            }
           
           
            else if($request->isPost()){ //post request can be either to update or create patient
                $patientModel->loadData($request->getBody());
                $patientModel->loadFiles($_FILES);
                // update patient
                if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='update'){
                    if(  $patientModel->updateRecord(['patient_ID'=>$parameters[1]['id']])){
                        Application::$app->session->setFlash('success',"Patient successfully updated ");
                        Application::$app->response->redirect('/ctest/receptionist-patient-information?mod=view&id='.$parameters[1]['id']);
                    }
                    //if failed to update show uddate patient form
                    else{
                        return $this->render('receptionist/update-patient',[
                            'model'=>$patientModel,
                        ]);
                    }
                    
                } 
                //create new patient if post request is not a update one
                else if($patientModel->validate() && $patientModel->register_non_session()){
                     Application::$app->session->setFlash('success',"Patient successfully added ");
                     Application::$app->response->redirect('/ctest/receptionist-handle-patient?mod=view');
                }
                //if failed to create a new patient show add patient form
                else{
                    return $this->render("receptionist/add-patient",[
                        'model'=>$patientModel
                    ]);
                }

                
            }
            //show add new patient form
            else if(isSet($parameters[0]['mod']) && $parameters[0]['mod']=='add'){
                return $this->render("receptionist/add-patient",[
                    'model'=>$patientModel
                ]);
            }
            //show patient update form
            else if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
                $patient=$patientModel->fetchAssocOne(['patient_ID'=>$parameters[1]['id']]);
                $patientModel->updateData($patient,$patientModel->fileDestination());
                Application::$app->session->set('patient',$parameters[1]['id']);
                return $this->render('receptionist/update-patient',[
                    'model'=>$patientModel,
                ]);
                
            }
            

        }

        public function handleAppointments(Request $request,Response $response){
            $this->setLayout('receptionist',['select'=>'Patients']);
            $parameters=$request->getParameters(); //[0=>['mod'=>'add],1=>['id'=>119],] $parameter[1]['id']
            $AppointmentModel=new Appointment();
            $ChannelingModel=new Channeling();
            $PatientModel=new Patient();
            $OpenedChannelingModel=new OpenedChanneling();
            $opened_channeling_id=$parameters[1]['id']??''; 
            $appointment_id='';
            if(isSet($parameters[0]['mod']) && $parameters[0]['mod']=='view'){
               $channelings=$ChannelingModel->customFetchAll("Select * from opened_channeling left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join doctor on  doctor.nic=channeling.doctor left join employee on employee.nic=doctor.nic"); 
               Application::$app->session->set('patient',$parameters[1]['id']);
               $patient=$PatientModel->fetchAssocOne(['patient_ID'=>$parameters[1]['id']]);
               return $this->render('receptionist/receptionist-patient-channeling-search',[
                    'channelings'=>$channelings,
                    'patient'=>$patient
               ]);
            }
            if($parameters[0]['mod']??''=='referral'){
               
                $ReferralModel=new Referral();
                $channelings=$ChannelingModel->customFetchAll("Select * from appointment  left join opened_channeling on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join doctor on  doctor.nic=channeling.doctor left join employee on employee.nic=doctor.nic where appointment.appointment_ID=".$parameters[1]['id']); 
                var_dump($channelings);
                if($request->isPost()){
                    $ReferralModel->loadFiles($_FILES);
                    $ReferralModel->setter($channelings[0]['nic'],$parameters[1]['id'],$channelings[0]['speciality'],'','soft-copy',$channelings[0]['name']);
                    $ReferralModel->addReferral();
                    Application::$app->session->setFlash('success',"Appointment Successfuly Created");
                    Application::$app->response->redirect('/ctest/receptionist-patient-information?mod=view&id='.Application::$app->session->get('patient'));
                }
                $ChannelingModel=new Channeling();
                $appointment=$AppointmentModel->findOne(['Appointment_ID'=>$parameters[1]['id']]);
                $Channeling=$ChannelingModel->customFetchAll("select * from appointment left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join doctor on doctor.nic=channeling.doctor where appointment_ID=".$parameters[1]['id'])[0];
                //Update query
    
                return $this->render('receptionist/receptionist-add-referral',[
                    'channeling'=>$Channeling,
                    'channelings'=>Application::$app->session->get('channelings'),
                    //unset session
                    'model'=>$ReferralModel,
                    'appointment'=>$appointment
                ]);
    
            }
            if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='delete'){
                $id=$AppointmentModel->customFetchAll("select opened_channeling_ID from appointment where appointment_ID=".$parameters[1]['id']);
                $AppointmentModel->deleteRecord(['Appointment_ID'=>$parameters[1]['id']]);
                Application::$app->session->setFlash('success',"Appointment successfully cancelled ");
                $OpenedChannelingModel->decreasePatientNumber($id[0]['opened_channeling_ID']);
                $OpenedChannelingModel->fixAppointmentNumbers($id[0]['opened_channeling_ID']);
                $response->redirect('/ctest/receptionist-patient-information?mod=view&id='.$parameters[2]['patient']);
                return true;
            }
            if( isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='add'){
                
                $AppointmentModel= new Appointment();
                $number=$AppointmentModel->customFetchAll("select max(queue_no) from appointment where opened_channeling_ID=".$parameters[1]['id']);
                if($number[0]['max(queue_no)']>0){
                    $number=$number[0]['max(queue_no)']+1;
                }
                else{
                    $number=1;
                }
                
                $appointment_id=$AppointmentModel->setAppointment([$parameters[1]['id'],Application::$app->session->get('patient'),$number,"Pending"]);
                $OpenedChannelingModel->increasePatientNumber($opened_channeling_id);
                Application::$app->response->redirect("receptionist-patient-appointment?mod=referral&id=".$appointment_id[0]['last_insert_id()']);
                
                
    
            }
        }

        public function patientInformation(Request $request){
            $this->setLayout("receptionist",['select'=>'Patients']);
            $parameters=$request->getParameters();
            $AppointmentModel=new Appointment();
            $appointments=$AppointmentModel->customFetchAll("Select * from appointment left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join doctor on doctor.nic=channeling.doctor left join employee on employee.nic=doctor.nic where appointment.patient_ID=".$parameters[1]['id']);
                if(isSet($parameters[0]['mod']) && $parameters[0]['mod']=='view'){
                    return $this->render("receptionist/patient-appointment-list",[
                        'appointments'=>$appointments //[[0]=>(first row),[1]=>(second)]
                    ]);
                }
        }


        public function viewPersonalDetails(){
            $this->setLayout("receptionist",['select'=>'My Detail']);
            $userModel = new Employee();
            // $user = $userModel->findOne(106);
            $user = $userModel->customFetchAll("SELECT * FROM employee WHERE email=".'"'.Application::$app->session->get('user').'"');
            return $this->render('receptionist/receptionist-view-personal-details',[
                'user' => $user[0]
            ]);
        }



    }





?>