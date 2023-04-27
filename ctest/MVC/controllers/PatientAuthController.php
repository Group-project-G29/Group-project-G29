<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\LabTest;
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
use app\models\ConsultationReport;
use app\models\Delivery;
use app\models\LabReport;
use app\models\MedicalHistory;
use app\models\MedicalReport;
use app\models\Medicine;
use app\models\Order;
use app\models\OTP;
use app\models\Payment;
use app\models\Prescription;
use app\models\Referral;
use app\models\SOAPReport;
use ReflectionFiber;

class PatientAuthController extends Controller{
    public function login(Request $request,Response $response){
      
        $this->setLayout('visitor-homepage-landing');
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
    public function pedlogin(Request $request,Response $response){
      
        $this->setLayout('visitor-homepage-landing');
        $PatientLoginForm=new PatientLoginForm();


        if($request->isPost()){
            $PatientLoginForm->loadData($request->getBody());
            if($PatientLoginForm->validate() && $PatientLoginForm->login()){
                Application::$app->session->setFlash('success',"Welcome ");
                $response->redirect('/ctest/patient-main');
                return true;
            }
        }
        return $this->render('patient/home-with--pediatric-login',[
            'model'=>$PatientLoginForm
        ]);
       
       
        
    }
    public function register(Request $request){

        $this->setLayout('auth');
        $registerModel=new Patient();
        if($request->isPost()){
           
            $registerModel->loadData($request->getBody());
            $registerModel->loadFiles($_FILES);
            if($registerModel->age<18){
            
                $registerModel->customAddError('age',"Age should be more than 17");
                return $this->render('patient/patient-registration',[
                    'model'=>$registerModel
                ]);
            }
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
       public function registerPediatric(Request $request){

        $this->setLayout('auth');
        $registerModel=new Patient();
        if($request->isPost()){
           
            $registerModel->loadData($request->getBody());
            $registerModel->loadFiles($_FILES);
            if($registerModel->age>=18){
                $registerModel->customAddError('age',"Age should be less than 19");
                $registerModel->validate();
                return $this->render('patient/pediatric-registration',[
                    'model'=>$registerModel
                ]);
            }
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

            return $this->render('patient/pediatric-registration',[
                'model'=>$registerModel
            ]);
        }
        return $this->render('patient/pediatric-registration',[
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
    public function getNIC(Request $request,Response $response){
        $this->setLayout('auth');
        $patient=new Patient();
        if($request->isPost()){
            
            $patient->loadData($request->getBody());
            $pat=$patient->fetchAssocAll(['nic'=>$patient->nic,'type'=>'adult']);
          
            if(!$pat){
                $patient->customAddError('nic',"No Patient Exists with this NIC");
                  return $this->render('patient/get-nic',[
                     'patient'=>$patient
                  ]);
            }
            else{
                Application::$app->session->set('temp_user',$patient->patient_ID);
                return $this->render('patient/set-otp');
            }
        }
        return $this->render('patient/get-nic',[
            'patient'=>$patient
        ]);
    }
    public function OTP(Request $request,Response $response){
        $parameters=$request->getParameters();
        $this->setLayout('auth');
        $OTP=new OTP();
        if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='send'){
          //create otp
          if($OTP->canSend(Application::$app->session->get('temp_user'))){
              $OTP->createOTP();
          }
            
        }
        if($request->isPost()){
            if($OTP->checkOTP(Application::$app->session->get('temp_user'))){

            }
        }
        return $this->render('patient/set-otp');


    }
    public function changePassword(){
        
    }

    public function channelingView(Request $request){
        $this->setLayout('visitor');
        $parameters=$request->getParameters();
        $speciality=$parameters[0]['spec']??'';
        $ChannelingModel=new Channeling();
        $OpenedChannelingModel=new OpenedChanneling();
        $Doctor=new Employee();
        $patient_appointments=
        $Channeling=$Doctor->customFetchAll("Select * from opened_channeling left JOIN channeling on opened_channeling.channeling_ID=channeling.channeling_ID left join doctor on channeling.doctor=doctor.nic left join employee on doctor.nic=employee.nic where channeling.speciality="."'".$speciality."'");
        if($speciality){
            Application::$app->session->set('channelings',$Channeling);
            return $this->render('patient/channeling-doctor-list-on-speciality',[
                
                'channelings'=>$Channeling,
               
               
            ]);
        }
       
        $ChannelingModel=new Channeling();
        $specialities=$ChannelingModel->customFetchAll("Select distinct channeling.speciality from opened_channeling left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID");
        
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
            $ReferralModel->setter($appointment_detail['doctor'],$appointment_detail['patient_ID'],$appointment_detail['speciality'],"","softcopy","",$appointment_detail['appointment_ID']);
            if($ReferralModel->addreferral(Application::$app->session->get('Appointment'))){
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
        $PaymentModel=new Payment();
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
                exit;
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
            $PaymentModel->deleteRecord(['appointment_ID'=>$parameters[1]['id']]);
            Application::$app->session->setFlash('success',"Appointment successfully cancelled ");
            $OpenedChannelingModel->increasePatientNumber($id[0]['opened_channeling_ID']);
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
            if(isSet($parameters[2]['type']) && $parameters[2]['type']=='consultation'){
                $appointment_id=$AppointmentModel->setAppointment([$opened_channeling_id,$patient,$number,"Pending",'consultation']);
                $OpenedChannelingModel->decreasePatientNumber($opened_channeling_id);
                $PaymentModel->createAppointmenPay(Application::$app->session->get('user'),'appointment',$AppointmentModel->getFee($appointment_id[0]['last_insert_id()']),$appointment_id[0]['last_insert_id()'],'pending');
                Application::$app->response->redirect("patient-appointment?mod=referral&id=".$appointment_id[0]['last_insert_id()']);
            }
            else if(isSet($parameters[2]['type']) && $parameters[2]['type']??''=='labtest'){
                $appointment_id=$AppointmentModel->setAppointment([$opened_channeling_id,$patient,$number,"Pending",'labtest']);
                $OpenedChannelingModel->decreasePatientNumber($opened_channeling_id);
                Application::$app->response->redirect("patient-appointment?mod=referral&id=".$appointment_id[0]['last_insert_id()']);
            }
            else{
                $appointment_id=$AppointmentModel->setAppointment([$opened_channeling_id,$patient,$number,"Pending",'consultation']);
                $OpenedChannelingModel->decreasePatientNumber($opened_channeling_id);
                $PaymentModel->createAppointmenPay(Application::$app->session->get('user'),'appointment',$AppointmentModel->getFee($appointment_id[0]['last_insert_id()']),$appointment_id[0]['last_insert_id()'],'pending');
                Application::$app->response->redirect("patient-appointment?mod=referral&id=".$appointment_id[0]['last_insert_id()']);
            }
        }
        if(isset($parameters[0]['spec']) && $parameters[0]['spec']=='referral'){
            $this->setLayout('patient',['select'=>'Appointments']);
            if(isset($parameters[1]['mod']) && $parameters[1]['mod']=='update'){
                $ReferralModel=new Referral();
                $ChannelingModel=new Channeling();
                $ReferrlaModel=new Referral();
                $AppointmentModel=new Appointment();
                
                $appointment=$AppointmentModel->findOne(['Appointment_ID'=>$parameters[2]['id']]);
                //Update query
                $Channelings=$AppointmentModel->customFetchAll("Select * from appointment left join opened_channeling on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join employee on employee.nic=channeling.doctor left join doctor on doctor.nic=employee.nic where appointment.patient_ID=".Application::$app->session->get('user'));

                return $this->render('patient/patient-all-appointments-add-refs',[
                    'channelings'=>$Channelings,
                    'model'=>$ReferralModel,
                    'appointment'=>$appointment,
                    'referrals'=>$ReferralModel->fetchAssocAll(['appointment_ID'=>$parameters[2]['id']])

                ]);
               
            }
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
        $this->setLayout('patient-pharmacy');
        $cartModel=new Cart();
        $medicineModel=new Medicine();
        $prescriptionModel=new Prescription();
        $patient=Application::$app->session->get('userObject');
        $cart=$cartModel->getPatientCart($patient->patient_ID??'')[0]['cart_ID']??'';
        $cartItems=$cartModel->fetchAssocAllByName(['cart_ID'=>$cart],'medicine_cart');
        //show order detail page
        if(isset($parameters[0]['spec']) && $parameters[0]['spec']=='prescription'){
            if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='add'){
                if(!Application::$app->session->get('user')){
                    Application::$app->session->setFlash('success',"Log into add Prescriptions");
                    Application::$app->response->redirect("/ctest/");
                    exit;
                }
                if($request->isPost()){
                    //post request to add new prescrtiption goes here
                    $prescriptionModel->addPrescriptionSoftCopy();
                }
            }
            if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='delete'){
                $prescriptionModel->deleteRecord(['prescription_ID'=>$parameters[2]['id']]);
            }
            if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='remove' ){
                $prescriptionModel->removeFromCart($parameters[2]['id']);
            }
            Application::$app->response->redirect("/ctest/patient-pharmacy?spec=main");
            return true;
    }
        if(isset($parameters[0]['spec']) && $parameters[0]['spec']=='order-main'){
            if(!Application::$app->session->get('user')){
                Application::$app->session->setFlash('success',"Log into view your Order");
                Application::$app->response->redirect("/ctest/");
            }
            $order=new Order();
            $lacked=$order->getLackedItems();
            $orderModel=new Order();
            $order_ID=$order->getPatientOrder()['order_ID']??'';
            return $this->render('patient/patient-track-order',[
                'lacked'=>$lacked,
                'order'=>$order->getPatientOrder(),
                'medicines'=>$orderModel->getOrderItem($order_ID),
                'prescriptions'=>$orderModel->getPrescriptionsInOrder($order_ID)
            ]);

        }
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
            //reload the search results
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
                if($parameters[3]['amount']>0){
                    //check amount
                    if(!$medicineModel->reduceMedicine($parameters[2]['item'],$parameters[3]['amount'])){
                        //show on red pop up
                        $medicinerror=$medicineModel->fetchAssocAll(['med_ID'=>$parameters[2]['item']]);
                        Application::$app->session->setFlash('error',"Sorry, No Enough ".$medicinerror[0]['name']." to Add to the Cart ");
                    }
                    else{
                    
                        $cartModel->addItem($parameters[2]['item'],$cart,$parameters[3]['amount']);
                    }
                }
                if($page)
                Application::$app->response->redirect("patient-pharmacy?cmd=search&value=$value&page=$page");
                else     
                Application::$app->response->redirect("patient-pharmacy?spec=main");
            }
            if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='delete'){
                $patient=Application::$app->session->get('userObject');
                $cart=$cartModel->getPatientCart($patient->patient_ID)[0]['cart_ID'];
                $cartModel->removeItem($parameters[2]['item'],$cart);
                if($page){
                    Application::$app->response->redirect("patient-pharmacy?spec=main");
                }
                else{
                    Application::$app->response->redirect("patient-pharmacy?cmd=search&value=$value&page=$page");
                }
            }
            if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='update'){

            }
            return $this->render("patient/pharmacy-main-search-result",[
                'medicines'=>$medicines,
                'cartItems'=>$cartItems
            ]);
            
            
        } 

    }
    //controller to make orders and mange payment in medical order processing system
    public function orderMedicine(Request $request,Response $response){
        $parameter=$request->getParameters();
        $this->setLayout('patient-pharmacy');
        $cartModel=new Cart();
        if($request->isPost()){
            $deliveryModel=new Delivery(); 
            $orderModel=new Order();
            $orderModel->loadData($request->getBody());            
            $deliveryModel->loadData($request->getBody());
            if($orderModel->getPatientOrder()){
                Application::$app->session->setFlash('error',"Please wait until next order get finished.");
                $cartModel=new Cart();
                $patient=Application::$app->session->get('userObject');
                $cart=$cartModel->getPatientCart($patient->patient_ID??'')[0]['cart_ID']??'';
                $cartItems=$cartModel->fetchAssocAllByName(['cart_ID'=>$cart],'medicine_cart');
                return $this->render('patient/patient-medicine-order',[
                    'cartItems'=>$cartItems,
                    'delivery'=>$deliveryModel,
                    'order'=>$orderModel
                ]);
            }
            //data validation
        
            if(!$orderModel->validate() || ($orderModel->pickup_status=='delivery' && !$deliveryModel->validate())){
                $cartModel=new Cart();
                $patient=Application::$app->session->get('userObject');
                $orderModel=new Order();
                $cart=$cartModel->getPatientCart($patient->patient_ID??'')[0]['cart_ID']??'';
                $cartItems=$cartModel->fetchAssocAllByName(['cart_ID'=>$cart],'medicine_cart');
                return $this->render('patient/patient-medicine-order',[
                    'cartItems'=>$cartItems,
                    'delivery'=>$deliveryModel,
                    'order'=>$orderModel
                ]);
            }

        }
        if($parameter[1]['mod']??''=='view'){
                $cartModel=new Cart();
                if($cartModel->getItemCount()==0){
                    if(Application::$app->session->get('page') || Application::$app->session->get('value')){
                        Application::$app->response->redirect('/ctest/patient-pharmacy?spec=main');
                    }
                    Application::$app->response->redirect("patient-pharmacy?cmd=search&value=".Application::$app->session->get('value')."&page=".Application::$app->session->get('page'));
                }
                $patient=Application::$app->session->get('userObject');
                $deliveryModel=new Delivery();
                $orderModel=new Order();
                $cart=$cartModel->getPatientCart($patient->patient_ID??'')[0]['cart_ID']??'';
                $cartItems=$cartModel->fetchAssocAllByName(['cart_ID'=>$cart],'medicine_cart');
                return $this->render('patient/patient-medicine-order',[
                    'cartItems'=>$cartItems,
                    'delivery'=>$deliveryModel,
                    'order'=>$orderModel
                ]);
            }
            if($parameter[0]['spec']??''=='order'){
            //if order is delivery order
            if($parameter[1]['cmd']??''=='complete'){
                if($request->isPost()){
                    //put order and delivery detail in session
                    Application::$app->session->set('order',$orderModel);
                    Application::$app->session->set('delivery',$deliveryModel);
                    Application::$app->response->redirect("/ctest/patient-payment?spec=medicine-order");
                        
                }
            }
            
        }
    }


    public function patientDashboard(Request $request,Response $response){
        $parameters=$request->getParameters();
        $orderModel=new Order();
        if(isSet($parameters[0]['spec']) && $parameters[0]['spec']=="orders"){
            
            $this->setLayout('patient',['select'=>'My Orders']);
            $orderModel=new Order();
            if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='reject'){
                $orderModel->setOrderStatus($parameters[2]['id'],'rejected');
                $response->redirect("patient-dashboard?spec=orders");
                return true;
            }
            else if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='accept'){
                $orderModel->setOrderStatus($parameters[2]['id'],'accepted');
                $response->redirect("patient-dashboard?spec=orders");
                return true;
            }
            if(!Application::$app->session->get('user')){
                Application::$app->session->setFlash('success',"Log into view your");
                Application::$app->response->redirect("/ctest/");
            }
            $order=new Order();
            $lacked=$order->getLackedItems();
            $order_ID=$order->getPatientOrder()['order_ID']??'';
            return $this->render('patient/patient-track-order',[
                'lacked'=>$lacked,
                'order'=>$order->getPatientOrder(),
                'medicines'=>$orderModel->getOrderItem($order_ID),
                'prescriptions'=>$orderModel->getPrescriptionsInOrder($order_ID)
            ]);
            // $orders=$orderModel->fetchAssocAll(['patient_ID'=>Application::$app->session->get('user')]);
            // return $this->render('patient/dashboard-order',[
            //     'orders'=>$orders,
                
            // ]);
            
            
            
            
        }
        if(isSet($parameters[0]['spec']) &&  $parameters[0]['spec']=='documentation'){
            //get all the documents and referrals
            $medicalReportModel=new MedicalReport();
            $referralModel=new Referral();
            $prescriptionModel=new Prescription();
            $labreportModel=new LabReport();
            $reports=$medicalReportModel->getReportsByPatient();
            $referrals=$referralModel->getReferralsByPatient(Application::$app->session->get('user'));
            $labreports=$labreportModel->getPatientReport(Application::$app->session->get('user'));
            $prescriptions=$prescriptionModel->getPatientPrescription(Application::$app->session->get('user'));
            $this->setLayout('patient',['select'=>'My Documentation']);
            return $this->render('patient/patient-my-documentation',[
                'reports'=>$reports,
                'referrals'=>$referrals,
                'labreports'=>array_reverse($labreports),
                'prescriptions'=>$prescriptions
                
            ]);
        
        }
        if(isSet($parameters[0]['spec']) && $parameters[0]['spec']=='appointments'){
            $this->setLayout('patient',['select'=>'Appointments']);
            $AppointmentModel=new Appointment();
            $Channelings=$AppointmentModel->customFetchAll("Select * from appointment left join opened_channeling on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join employee on employee.nic=channeling.doctor left join doctor on doctor.nic=employee.nic where appointment.patient_ID=".Application::$app->session->get('user'));
            return $this->render('patient/patient-all-appointments',[
                    'channelings'=>$Channelings
            
                
            
            ]);
            $this->setLayout('patient',['select'=>'Appointments']);
                    
        }
       
        if(isSet($parameters[0]['spec']) &&  $parameters[0]['spec']=='medical-analysis'){
            $this->setLayout('patient',['select'=>"Medical Analysis"]);
            $testModel=new LabReport();
            $values=$testModel->getAllParameterValue(Application::$app->session->get('user'));
            return $this->render('patient/medical-analysis',[
                'mainArray'=>$values
            ]);

        }
        if(isset($parameters[0]['spec']) && $parameters[0]['spec']=='payments'){
            $this->setLayout('patient',['select'=>'My Payments']);
            $paymentModel=new Payment();
            $payments=$paymentModel->fetchAssocAll(['patient_ID'=>Application::$app->session->get('user')]);
            return $this->render('patient/dashboard-payment',[
                'payments'=>$payments
            ]);
        }
        if(isSet($parameters[0]['spec']) &&  $parameters[0]['spec']=='my-detail'){

        }


        
    }

    public function patientPayment(Request $request,Response $response){
        $parameters=$request->getParameters();
        //order
        $payment=new Payment();
        $orderModel=new Order();
        $cartModel=new Cart();
        $this->setLayout("patient",['select'=>'My Payments']);
        if(isSet($parameters[0]['spec']) && $parameters[0]['spec']=='payment-gateway'){
            $amount=$cartModel->getCartPrice();
            $hash = strtoupper(
                                md5(
                                    '1222960' . 
                                    "Medicine Order-".Application::$app->session->get('user') . 
                                    number_format($amount, 2, '.', '') . 
                                    'LKR' .  
                                    strtoupper(md5('MzAzOTcxMjc5NTIzODU1OTk5ODg3MTE2MTM1NDU0MDgxODMzNjk2')) 
                                )); 
                                $cart=$cartModel->getPatientCart(Application::$app->session->get('user'))[0]['cart_ID'];
                                
                                return $this->render('patient/patient-payment-page-pgw',[
                                    'hash'=>$hash,
                                    'amount'=>$amount,
                                    'prescriptions'=>$orderModel->fetchAssocAllByName(['cart_ID'=>$cart],'prescription'),
                                    'medicines'=>$orderModel->customFetchAll("select medical_products.name,medical_products.unit,medicine_in_cart.amount,medical_products.unit_price,medical_products.med_ID from medicine_in_cart left join medical_products on medical_products.med_ID=medicine_in_cart.med_ID where medicine_in_cart.cart_ID=".$cart)
                                ]);
                            }
                            
        if(isSet($parameters[0]['spec']) && $parameters[0]['spec']=='medicine-order'){
            if(isSet($parameters[1]['cmd']) && $parameters[1]['cmd']=='complete'){
                $payment=new Payment();
                $cart=new Cart();
                $amount=$cart->getCartPrice();
                
                if(isset($parameters[2]['type']) && $parameters[2]['type']=='payon'){
                    $payment->createOrderPay(Application::$app->session->get('user'),'order',$amount,'pending');
                }
                else{

                    $payment->createOrderPay(Application::$app->session->get('user'),'order',$amount,'completed');
                }
                $order=Application::$app->session->get('order');
                $delivery=Application::$app->session->get('delivery');
                //---------------remove items from the session--------------------
                // $delivery->createPIN();
                // $delivery->createPIN();
                $cartModel=new Cart();
                //get the patient cart
                $user=$cartModel->getPatientCart(Application::$app->session->get('user'));
                //call the transfer function
                $cartModel->transferCartItem($user[0]['cart_ID'],$order->pickup_status,$delivery);
                
                //redirect to patient dashboard
                Application::$app->response->redirect("/ctest/patient-dashboard?spec=orders");
                
                
            }
        }

        $cart=$cartModel->getPatientCart(Application::$app->session->get('user'))[0]['cart_ID'];
        return $this->render("patient/patient-payment-page",[
            'prescriptions'=>$orderModel->fetchAssocAllByName(['cart_ID'=>$cart],'prescription'),
            'medicines'=>$orderModel->customFetchAll("select medical_products.name,medical_products.unit,medicine_in_cart.amount,medical_products.unit_price,medical_products.med_ID from medicine_in_cart left join medical_products on medical_products.med_ID=medicine_in_cart.med_ID where medicine_in_cart.cart_ID=".$cart)
        ]);

    }
    public function handleDocuments(Request $request,Response $response){
        $parameter=$request->getParameters();
        $historyModel=new MedicalHistory();
        $medicalReportModel=new MedicalReport();
        $prescriptionModel=new Prescription();
        $soapModel=new SOAPReport();
        $consultationModel=new ConsultationReport();
        $cartModel=new Cart();
        if(isset($parameter[0]['spec']) && $parameter[0]['spec']=='sec-prescription' ){
            if(isset($parameter[1]['cmd']) && $parameter[1]['cmd']=='add'){
                $cart=$cartModel->getPatientCart(Application::$app->session->get('user'))[0]['cart_ID'];
                $prescriptionModel->customFetchAll("update prescription set cart_ID=".$cart." where prescription_ID=".$parameter[2]['id']);
                $response->redirect('patient-dashboard?spec=documentation');
                exit;
            }
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
            return true;
        }
        else if (isset($parameter[0]['spec']) && ($parameter[0]['spec'] == "prescription") ){
            if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
                $report=$prescriptionModel->customFetchAll("Select * from prescription where prescription_ID=".$parameter[2]['id']);
                if($report[0]['type']=='softcopy prescription'){
                    $response->redirect('/ctest/MVC/public/media/patient/prescriptions/'.$report[0]['location']);
                }
                else{
                    $prescriptionModel->prescriptionToPDF($report[0]['prescription_ID']);
                } 
            }
            return true;
        }
        else if (isset($parameter[0]['spec']) && ($parameter[0]['spec'] == "consultation-report"||$parameter[0]['spec'] == "consultation") ){
            if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
                $report=$consultationModel->customFetchAll("Select * from consultation_report left join medical_report on consultation_report.report_ID=medical_report.report_ID where medical_report.report_ID=".$parameter[2]['id']);
                if($report[0]['type']=='softcopy'){
                    $response->redirect('/ctest/MVC/public/media/patient/medicalreports/'.$report[0]['name']);

                }
                else{
                    $consultationModel->ConsultationreportToPDF($report[0]['report_ID']);
                } 
            }
            return true;
        }
        else if (isset($parameter[0]['spec']) && $parameter[0]['spec'] == "soap-report" ||$parameter[0]['spec'] == "soap") {
            if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
                $soapModel=new SOAPReport();
                $report=$soapModel->customFetchAll("Select * from soap_report left join medical_report on soap_report.report_ID=medical_report.report_ID where medical_report.report_ID=".$parameter[2]['id']);
                if($report[0]['type']=='softcopy'){
                    $response->redirect('/ctest/MVC/public/media/patient/medicalreports/'.$report[0]['name']);
                    
                }
                else{
                    $soapModel->SOAPreportToPDF($report[0]['report_ID']);
                }
                //$referralModel->findOne(['ref_ID'=>$parameter[2]['id']]);
            }
            return true;
        }
        else if (isset($parameter[0]['spec']) && $parameter[0]['spec'] == "referral"){
            $referralModel=new Referral();
            if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
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
            if(isset($parameter[1]['cmd']) && $parameter[1]['cmd']=='delete'){
                $referralModel->deleteRecord(['ref_ID'=>$parameter[2]['id']]);
                $response->redirect('patient-dashboard?spec=appointments');
                return true;
            }
        }
        else if(isset($parameter[1]['mod']) && $parameter[1]['mod']=='view'){
               $medicalModel=new MedicalReport();
               $medicalReport=$medicalModel->fetchAssocAll(['report_ID'=>$parameter[2]['id']]);
               $response->redirect('/ctest/MVC/public/media/patient/medicalreports/'.$medicalReport[0]['label']);        
        }
    }
    //get 
    public function handelLabReports(Request $request,Response $response){
        $labreportModel=new LabReport();
        $parameters=$request->getParameters();
        if(isset($parameters[0]['spec']) && $parameters[0]['spec']='lab-report'){
            if(isset($parameters[1]['cmd']) &&  $parameters[1]['cmd']=='view'){
                $report=$labreportModel->fetchAssocAll(['report_ID'=>$parameters[2]['id']])[0];
                if($report['type']=='softcopy'){
                    $response->redirect('http://localhost/ctest/MVC/public/media/patient/labreports/'.$report['location']);   
                }
                else{
                    $labreportModel->labreporttoPDF($parameters[2]['id']);
                }
            }
        }
        return true;
    }

    public function accountHandle(Request $request,Response $response){
        $parameters=$request->getParameters();
        $this->setLayout('patient',['select'=>'My Detail']);
        $patientModel=new Patient();
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='view'){
            $patient=$patientModel->fetchAssocAll(['patient_ID'=>Application::$app->session->get('user')]);
            array_pop($patient[0]);
            return $this->render('patient/patient-my-detail',[
                'patient'=>$patient[0]
            ]);
        }
        if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
            if($request->isPost()){
                $patient=$patientModel->findOne(['patient_ID'=>Application::$app->session->get('user')]);
                $patientModel->loadData($request->getBody());
                $patientModel->password=$patient->password;
                $patientModel->cpassword=$patient->password;
                if($patientModel->validate()){
                    $patientModel->updaterecord(['patient_ID'=>Application::$app->session->get('user')]);
                    $response->redirect('patient-my-detail?cmd=view');
                }
                else{

                    return $this->render('patient/update-patient-my-detail',[
                        'patient'=>$patientModel
                    ]);
                }
            }
            $patient=$patientModel->findOne(['patient_ID'=>Application::$app->session->get('user')]);
            $patient->password='';
            return $this->render('patient/update-patient-my-detail',[
                'patient'=>$patient
            ]);
        }
        
    }
    

    public function contact_us(){
        // $this->setLayout("patient",['select'=>'Payments']);
        $this->setLayout('visitor-homepage');
        return $this->render('patient/contact',[
        ]);
    }

    public function labpage(){
        // $this->setLayout("patient",['select'=>'Payments']);
        $lab_test = new LabTest();
        $tests = $lab_test->get_lab_tests();
        $this->setLayout('patient-lab');
        return $this->render('patient/lab-page',[
            'tests' => $tests
        ]);
    } 

}