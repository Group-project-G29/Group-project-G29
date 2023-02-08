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
use app\models\Delivery;
use app\models\Medicine;
use app\models\Order;
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
            $ReferralModel->setter($appointment_detail['doctor'],$appointment_detail['patient_ID'],$appointment_detail['speciality'],"","softcopy","");
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
            if(isSet($parameters[2]['type']) && $parameters[2]['type']=='consultation'){
                $appointment_id=$AppointmentModel->setAppointment([$opened_channeling_id,$patient,$number,"Pending",'consultation']);
                $OpenedChannelingModel->increasePatientNumber($opened_channeling_id);
                Application::$app->response->redirect("patient-appointment?mod=referral&id=".$appointment_id[0]['last_insert_id()']);
            }
            else if($parameters[2]['type'] && $parameters[2]['type']??''=='labtest'){
                $appointment_id=$AppointmentModel->setAppointment([$opened_channeling_id,$patient,$number,"Pending",'labtest']);
                $OpenedChannelingModel->increasePatientNumber($opened_channeling_id);
                Application::$app->response->redirect("patient-appointment?mod=referral&id=".$appointment_id[0]['last_insert_id()']);
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
        $patient=Application::$app->session->get('userObject');
        $cart=$cartModel->getPatientCart($patient->patient_ID??'')[0]['cart_ID']??'';
        $cartItems=$cartModel->fetchAssocAllByName(['cart_ID'=>$cart],'medicine_cart');
        //show order detail page
        if(isset($parameters[0]['spec']) && $parameters[0]['spec']=='order-main'){
            $order=new Order();
            return $this->render('patient/patient-track-order',[
                'order'=>$order->getPatientOrder()
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
                        //show on reb pop up
                        $medicinerror=$medicineModel->fetchAssocAll(['med_ID'=>$parameters[2]['item']]);
                        Application::$app->session->setFlash('error',"Sorry, No Enough ".$medicinerror[0]['name']." to Add to the Cart ");
                    }
                    else{
                    
                        $cartModel->addItem($parameters[2]['item'],$cart,$parameters[3]['amount']);
                    }
                }
                Application::$app->response->redirect("patient-pharmacy?cmd=search&value=$value&page=$page");
            }
            if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='delete'){
                $patient=Application::$app->session->get('userObject');
                $cart=$cartModel->getPatientCart($patient->patient_ID)[0]['cart_ID'];
                $cartModel->removeItem($parameters[2]['item'],$cart);
                Application::$app->response->redirect("patient-pharmacy?cmd=search&value=$value&page=$page");
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
            //data validation
            if(!$orderModel->validate() || !$deliveryModel->validate()){
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
        if($parameter[0]['spec']??''=='order'){
            //if order is a pickup order
            if($orderModel->pickup_status=='pickup'){
                if($parameter[1]['cmd']??''=='complete'){
                    if($request->isPost()){
                        //get the patient cart
                        $user=$cartModel->getPatientCart(Application::$app->session->get('user'));
                        //call the transfer function
                        $cartModel->transferCartItem($user[0]['cart_ID'],$orderModel->pickup_status,$deliveryModel);
                        //redirect to patient dashboard
                        Application::$app->response->redirect("/ctest/patient-dashboard?spec=orders");
                        
                        
                    }
                }
            }
            //if order is delivery order
            else if($orderModel->pickup_status??''=='delivery'){
                if($parameter[1]['cmd']??''=='complete'){
                    if($request->isPost()){
                        //put order and delivery detail in session
                        Application::$app->session->set('order',$orderModel);
                        Application::$app->session->set('delivery',$deliveryModel);
                        Application::$app->response->redirect("/ctest/patient-payment?spec=medicine-order");
                        
                    }
                }
            }
            else if($parameter[1]['mod']??''=='view'){
                $cartModel=new Cart();
                if($cartModel->getItemCount()==0){
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
        }
    }


    public function patientDashboard(Request $request,Response $response){
        $parameters=$request->getParameters();
        if(isSet($parameters[0]['spec']) && $parameters[0]['spec']=="orders"){
            
            $this->setLayout('patient',['select'=>'My Orders']);
            $orderModel=new Order();
            if($parameters[1]['mod']??''=='view'){
                
                return $this->render('patient/dashboard-show-order',[
                    'items'=>$orderModel->getOrderItem($parameters[2]['id']),
                    'orderdetails'=>$orderModel->customFetchAll("select * from _order left join delivery on _order.delivery_ID=delivery.delivery_ID where _order.order_ID=".$parameters[2]['id'])
                ]);
            }
            $orders=$orderModel->fetchAssocAll(['patient_ID'=>Application::$app->session->get('user')]);
            return $this->render('patient/dashboard-order',[
                'orders'=>$orders,
                
            ]);
            
            
            
        }
        if(isSet($parameters[0]['spec']) &&  $parameters[0]['spec']??''=='documentation'){
        
        }
        if(isSet($parameters[0]['spec']) && $parameters[0]['spec']??''=='appointments'){
            $this->setLayout('patient',['select'=>'Appointments']);
            $AppointmentModel=new Appointment();
            $Channelings=$AppointmentModel->customFetchAll("Select * from appointment left join opened_channeling on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join employee on employee.nic=channeling.doctor left join doctor on doctor.nic=employee.nic where appointment.patient_ID=".Application::$app->session->get('user'));
            return $this->render('patient/patient-all-appointments',[
                    'channelings'=>$Channelings
            
                
            
            ]);
            $this->setLayout('patient',['select'=>'Appointments']);
                    
        }
        if (isSet($parameters[0]['spec']) &&  $parameters[0]['spec']=='payments'){

        }
        if(isSet($parameters[0]['spec']) &&  $parameters[0]['spec']=='medical-analysis'){

        }
        if(isSet($parameters[0]['spec']) &&  $parameters[0]['spec']=='my-detail'){

        }


        
    }

    public function patientPayment(Request $request,Response $response){
        $parameters=$request->getParameters();
        $this->setLayout("patient",['select'=>'Payments']);
        if($request->isPost()){
            if(isSet($parameters[0]['spec']) && $parameters[0]['spec']=='medicine-order'){
                $order=Application::$app->session->get('order');
                $delivery=Application::$app->session->get('delivery');
                //---------------remove items from the session--------------------
                $delivery->createPIN();
                $cartModel=new Cart();
                 //get the patient cart
                 $user=$cartModel->getPatientCart(Application::$app->session->get('user'));
                 //call the transfer function
                 $cartModel->transferCartItem($user[0]['cart_ID'],$order->pickup_status,$delivery);
                 //redirect to patient dashboard
                 Application::$app->response->redirect("/ctest/patient-dashboard?spec=orders");
                 

            }
        }
        return $this->render("patient/patient-payment-page");

    }
   


}