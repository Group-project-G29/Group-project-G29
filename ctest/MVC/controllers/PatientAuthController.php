<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\User;
use app\core\DbModel;
use app\core\Response;
use app\models\Advertisement;
use app\models\Channeling;
use app\models\Employee;
use app\models\OpenedChanneling;
use app\models\Patient;
use app\models\PatientLoginForm;
use app\models\Appointment;
use app\models\Cart;
use app\models\Medicine;
use app\models\Referral;


class PatientAuthController extends Controller{
    public function login(Request $request,Response $response){
      
        $this->setLayout('visitor-homepage');
        $PatientLoginForm=new PatientLoginForm();


        if($request->isPost()){
            $PatientLoginForm->loadData($request->getBody());
            if($PatientLoginForm->validate() && $PatientLoginForm->login()){
                Application::$app->session->setFlash('success',"Welcome ");
                $response->redirect('/ctest/patient-main');
                return true;
            }
        }
        return $this->render('patient/home-with-login',[
            'model'=>$PatientLoginForm
        ]);
       
       
        
    }
    public function register(Request $request){

        $this->setLayout('auth');
        $registerModel=new Patient();
        if($request->isPost()){
           
            $registerModel->loadData($request->getBody());
            $registerModel->loadFiles($_FILES);
            if($registerModel->validate() && $registerModel->register()){
                $user=$registerModel->findOne(['nic'=>$registerModel->nic]);
                Application::$app->login($user,'patient');
                Application::$app->session->setFlash('success',"Thanks for registering");
                //create new medicine cart for the patient
                $cartModel=new Cart();
                $cartModel->createCart(Application::$app->session->get('userObject')->patient_ID);
                Application::$app->response->redirect('/ctest/patient-main');
                
                exit;
            };

            return $this->render('patient/patient-registration',[
                'model'=>$registerModel
            ]);
        }
        return $this->render('patient/patient-registration',[
            'model'=>$registerModel,
        ]);
    }
    public function logout(Request $request,Response $response){
        $registerModel=new Patient();
        if($registerModel->logout()){
            Application::$app->session->setFlash('success',"You are logged out");
            Application::$app->response->redirect("/ctest/");
            exit;
        }
    }
    public function mainPage(){
        $this->setLayout('visitor-homepage');
        return $this->render('patient/home',[
            
        ]);
    }

    public function channelingView(Request $request){
        $this->setLayout('visitor');
        
        $parameters=$request->getParameters();
        $speciality=$parameters[0]['spec']??'';
        $ChannelingModel=new Channeling();
        $OpenedChannelingModel=new OpenedChanneling();
        $Doctor=new Employee();
        $patient_appointments=
        $Channeling=$Doctor->customFetchAll("Select * from employee right join doctor on employee.nic=doctor.nic right join channeling on doctor.nic=channeling.doctor left join opened_channeling on opened_channeling.channeling_ID=channeling.channeling_ID where doctor.speciality="."'".$speciality."'");
        if($speciality){
            Application::$app->session->set('channelings',$Channeling);
            return $this->render('patient/channeling-doctor-list-on-speciality',[
                
                'channelings'=>$Channeling,
               
               
            ]);
        }
       
        $ChannelingModel=new Channeling();
        $specialities=$ChannelingModel->customFetchAll("Select distinct speciality from doctor");
        
        return $this->render('patient/channelings-categories',[

            'specialities'=>$specialities, 
            'app'=>$ChannelingModel
            
        
        ]);
    }

   

    public function viewAppointments(){
        $this->setLayout('patient',['select'=>'Appointments']);
        $AppointmentModel=new Appointment();
        $Channelings=$AppointmentModel->customFetchAll("Select * from appointment left join opened_channeling on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join employee on employee.nic=channeling.doctor left join doctor on doctor.nic=employee.nic where appointment.patient_ID=".Application::$app->session->get('user'));
        return $this->render('patient/patient-all-appointments',[
                'channelings'=>$Channelings
           
            
        
        ]);

    }
    public function handleReferral(Request $request){
        $parameter=$request->getParameters();
        $ReferralModel=new Referral();
        $appointment=new Appointment();
        if($request->isPost()){
            $ReferralModel->loadData($request);
            $ReferralModel->loadFiles($_FILES);
            $appointment_detail=$appointment->customFetchAll("select * from appointment left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join doctor on doctor.nic=channeling.doctor where appointment_ID=".$parameter[1]['id'])[0];
            $ReferralModel->setter($appointment_detail['doctor'],$appointment_detail['appointment_ID'],$appointment_detail['speciality'],"","softcopy","");
            if($ReferralModel->addReferral()){
                Application::$app->session->setFlash('success',"Appointment successfully created");
                Application::$app->response->redirect("/ctest/patient-all-appointment");
            }
        }    
    }




    public function handleAppointments(Request $request,Response $response){
        $OpenedChannelingModel=new OpenedChanneling();
        $this->setLayout('visitor');
        $patient=Application::$app->session->get('user')??'';
        $parameters=$request->getParameters();
        $opened_channeling_id=$parameters[1]['id']??''; 
        $AppointmentModel=new Appointment();
        $appointment_id='';
        if(!$patient){
                Application::$app->session->setFlash('success',"Login to set appointments");
                Application::$app->response->redirect("/ctest/");
        }
        
        if($parameters[0]['mod']??''=='referral'){
            $patient=Application::$app->session->get('user')??'';
            if(!$patient){
                Application::$app->session->setFlash('success',"Login here to ceate appointment");
                Application::$app->response->redirect("/ctest/");
            }
            $ReferralModel=new Referral();
            $ChannelingModel=new Channeling();
            $ReferrlaModel=new Referral();
            $AppointmentModel=new Appointment();

            $appointment=$AppointmentModel->findOne(['Appointment_ID'=>$parameters[1]['id']]);
            $Channeling=$ChannelingModel->customFetchAll("select * from appointment left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join doctor on doctor.nic=channeling.doctor where appointment_ID=".$parameters[1]['id'])[0];
            //Update query

            return $this->render('patient/patient-add-referal',[
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
            $response->redirect('/ctest/patient-all-appointment');
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
        
            $appointment_id=$AppointmentModel->setAppointment([$opened_channeling_id,$patient,$number,"Pending"]);
            $OpenedChannelingModel->increasePatientNumber($opened_channeling_id);
            Application::$app->response->redirect("patient-appointment?mod=referral&id=".$appointment_id[0]['last_insert_id()']);
            
            

        }
    }

    public function doctorAppointment(Request $request,Response $response){
        $parameters = $request->getParameters();
        $employee = new Employee();
        $this->setLayout('visitor');
        if($parameters[0]['spec']=='doctor'){
            $doctors=$employee->getAccounts('doctor');
            return $this->render("patient/appointment-show-doctor",[
                'doctors'=>$doctors
                                
            ]);
            

        }
        else if($parameters[0]['spec']=='appointment'){
            $channelings=$employee->getChannelings($parameters[1]['id']);
            return $this->render('patient/patient-appointment-doctor',[
                'channelings'=>$channelings
                
            ]);
            


        }
        
    }

    public function medicineOrder(Request $request,Response $response){
        $content_amount=12;
        $parameters=$request->getParameters();
        $this->setLayout('visitor-homepage');
        $cartModel=new Cart();
        $medicineModel=new Medicine();
        $patient=Application::$app->session->get('userObject');
        $cart=$cartModel->getPatientCart($patient->patient_ID??'')[0]['cart_ID']??'';
        $cartItems=$cartModel->fetchAssocAllByName(['cart_ID'=>$cart],'medicine_cart');
        //show main page
        if(isset($parameters[0]['spec']) && $parameters[0]['spec']=='main'){
            $adModel=new Advertisement();
            $advertisements=$adModel->getAdvertisements('pharmacy');
            return $this->render("patient/pharmacy-main-page",[
                'advertisements'=>$advertisements
            ]);
        }       
        // search medicine
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='search'){
            $medicineModel=new Medicine();
            //?cmd=search&value=panadl&page=1
            $medicines=$medicineModel->searchMedicineByPage($content_amount,$parameters[2]['page'],$parameters[1]['value']);
            Application::$app->session->set('page',$parameters[2]['page']);
            Application::$app->session->set('value',$parameters[1]['value']);
            return $this->render("patient/pharmacy-main-search-result",[
                'medicines'=>$medicines,
                'cartItems'=>$cartItems
            ]);
        }
        if(isset($parameters[0]['spec']) && $parameters[0]['spec']=='medicine'){
            $page=Application::$app->session->get('page');
            $value=Application::$app->session->get('value');
            //?cmd=search&value=panadl&page=1
            $medicines=$medicineModel->searchMedicineByPage($content_amount,$page,$value);
            if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='add'){
                if(!$patient){
                    Application::$app->session->setFlash('success',"Log into order medicine");
                    Application::$app->response->redirect("/ctest/");
                    exit;
                }
                $cart=$cartModel->getPatientCart($patient->patient_ID??'')[0]['cart_ID']??'';
                $cartModel->addItem($parameters[2]['item'],$cart,$parameters[3]['amount']);
            }
            if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='delete'){
                $patient=Application::$app->session->get('userObject');
                $cart=$cartModel->getPatientCart($patient->patient_ID);
                $cartModel->removeItem($parameters[2]['item'],$cart);
            }
            return $this->render("patient/pharmacy-main-search-result",[
                'medicines'=>$medicines,
                'cartItems'=>$cartItems
            ]);
            
            
        } 

    }

}