<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Database;
use app\core\Request;
use app\models\User;
use app\core\DbModel;
use app\core\Response;
use app\models\Employee;
use app\models\Medicine;
use app\models\Advertisement;
use app\models\Delivery;
use app\models\Order;
use app\models\deliveryAdvertisement;
use app\models\Payment;
use LogicException;

class DeliveryController extends Controller{
    
    //view my deliveries
    public function viewMyDeliveries(){
        $this->setLayout("delivery-rider",['select'=>'My Deliveries']);
        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->get_unfinished_deliveries(Application::$app->session->get('userObject')->emp_ID);
        // var_dump($delivery);
        // exit;
        return $this->render('delivery/delivery-my-deliveries' ,[
            'deliveries'=>$delivery,
            'model'=>$deliveryModel
        ]);
    }
    
    //view details of a selected delivery
    public function viewDeliveryDetails(Request $request,Response $response){
        $parameters=$request->getParameters();
        $this->setLayout("delivery-rider",['select'=>'My Deliveries']);
        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->view_delivery_details($parameters[0]['id']); //pass id
        return $this->render('delivery/delivery-view-delivery' ,[
            'delivery'=>$delivery[0],
            'model'=>$deliveryModel
        ]);
    }
    
    //pass  deliveries to another delivery rider
    public function passDelivery(Request $request){
        $parameters=$request->getParameters();
        // var_dump($parameters);
        
        $this->setLayout("delivery-rider",['select'=>'My Deliveries']);
        
        //make offline the user
        $userModel = new Employee();
        $riderMOdel = new Employee();
        $deliveryModel = new Delivery();
        $order_details = $deliveryModel->view_delivery_details($parameters[0]['id']); 
        
        // pop up message can be shown to select whether make offline or stay online
        $user=$userModel->make_rider_offline(Application::$app->session->get('userObject')->emp_ID);
        
        //pass using pharmacy functions
        $orderModel=new Order();
        
        //selected the available rider 
        $postal_code = $orderModel->get_postal_code($order_details[0]["order_ID"]);
        // var_dump($order_details);
        // var_dump($postal_code);
        $rider = $userModel->select_suitable_rider($postal_code[0]["postal_code"], $postal_code[0]["order_ID"]);
        // var_dump($rider);
        // exit;
        
        // echo 'pass';
        // exit;
        if($rider) {
            $updated_rider_ID=$deliveryModel->update_rider_ID($postal_code[0]["delivery_ID"], $rider[0]["delivery_rider"]);
            
        } else {
            $rider = $riderMOdel->select_queue_rider();
            
            if (!$rider){
                $updated_rider_ID=$deliveryModel->set_delivery_without_rider($parameters[0]['id']);
            } else {
                $updated_rider_ID=$deliveryModel->update_rider_ID($postal_code[0]["delivery_ID"], $rider[0]["delivery_rider_ID"]);
                //dequeue a delivery rider - check something went wrong-> deleted few records at once
                $deleted_rider = $riderMOdel->dequeue_rider($rider[0]["delivery_rider_ID"]);
            }
        }

        $user=$userModel->make_rider_online(Application::$app->session->get('userObject')->emp_ID);
        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->get_unfinished_deliveries(Application::$app->session->get('userObject')->emp_ID);
        return $this->render('delivery/delivery-my-deliveries' ,[
            'deliveries'=>$delivery,
            'model'=>$deliveryModel
        ]);
    }

    public function viewPendingDeliveries(){
        $this->setLayout("delivery-rider",['select'=>'Pending Deliveries']);
        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->get_null_rider_deliveries();
        return $this->render('delivery/delivery-pending-deliveries' ,[
            'deliveries'=>$delivery,
            'model'=>$deliveryModel
        ]);
    }

    public function getDelivery(Request $request){
        $parameters=$request->getParameters();
        $this->setLayout("delivery-rider",['select'=>'Pending Deliveries']);
        $deliveryModel=new Delivery();
        $get_delivery = $deliveryModel->update_rider_ID($parameters[0]['id'],Application::$app->session->get('userObject')->emp_ID);
        $delivery=$deliveryModel->get_null_rider_deliveries();
        return $this->render('delivery/delivery-pending-deliveries' ,[
            'deliveries'=>$delivery,
            'model'=>$deliveryModel
        ]);
    }

    //view completed deliveries
    public function viewAllDeliveries(){
        $this->setLayout("delivery-rider",['select'=>'Completed Deliveries']);
        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->get_finished_deliveries(Application::$app->session->get('userObject')->emp_ID);
        return $this->render('delivery/delivery-all-deliveries' ,[
            'deliveries'=>$delivery,
            'model'=>$deliveryModel
        ]);
    }

    //complete the delivery using the PIN
    public function completeDelivery(Request $request, Response $response){
        $parameters=$request->getParameters();
        $this->setLayout("delivery-rider",['select'=>'My Deliveries']);
        
        $deliveryModel=new Delivery();
        $riderMOdel = new Employee;
        $orderMOdel = new Order;
        
        $deliveryModel->loadData($request->getBody());
        $orderMOdel->loadData($request->getBody());
        $confirming_delivery = $deliveryModel->get_processing_delivery($parameters[0]['id']);
        // echo "\nparameters\n"; var_dump($parameters);echo "\npost\n";var_dump($_POST);echo "\ndelMOdel\n";var_dump($deliveryModel);echo "\norderMod\n";var_dump($orderMOdel);echo "\ncurr delivery\n";var_dump($confirming_delivery);exit;

        //check the payment status on delivery
        if ( $confirming_delivery[0]['payment_status'] == 'pending' && !isset($_POST['payment_status']) ) { 
            $delivery=$deliveryModel->view_delivery_details($parameters[0]['id']); 
            $this->setLayout("delivery-rider",['select'=>'My Deliveries']);
            return $this->render('delivery/delivery-view-delivery' ,[
                'delivery'=>$delivery[0],
                'model'=>$deliveryModel,
                'err'=>'pending_payment'
            ]);

        } else {
            // if pin is empty
            if(empty($_POST["PIN"])){
                $delivery=$deliveryModel->view_delivery_details($parameters[0]['id']); 
                $this->setLayout("delivery-rider",['select'=>'My Deliveries']);
                return $this->render('delivery/delivery-view-delivery' ,[
                    'delivery'=>$delivery[0],
                    'model'=>$deliveryModel,
                    'err'=>'empty_pin'
                ]);
            }

            if ( $confirming_delivery[0]['PIN'] === $_POST['PIN'] ) {
                $update_delivery_status = $deliveryModel->update_completed_date_time_delivery($parameters[0]['id']);
                $update_order_status = $deliveryModel->update_processing_status_order($parameters[0]['id']);
                // update payment status in payment table
                $paymentModel = new Payment();
                $updated_payment_status=$paymentModel->update_payment_status($parameters[0]['id']);
                
                        // ==== pop up implement ===============>>>
                        // if ( $confirming_delivery[0]['payment_status'] == 'pending'){
                            // echo 'payment done now';
                            //pop up msg is good
                            // exit;
                        // } else {
                            // echo 'payment done earlier';
                            //pop up msg is good
                            // exit;
                        // }
                        // ====================<<<<<<
                        $unfinished_delivery=$deliveryModel->get_unfinished_deliveries(Application::$app->session->get('userObject')->emp_ID);
                        //enque the rider to the queue if there is no deliveries to him
                if ( !$unfinished_delivery ) {
                    $null_rider_deliveries = $deliveryModel->get_null_rider_deliveries();
                    // var_dump($null_rider_deliveries);
                    // exit;
                    if ($null_rider_deliveries) {
                        $updated_rider_ID=$deliveryModel->update_rider_ID($null_rider_deliveries[0]["delivery_ID"], Application::$app->session->get('userObject')->emp_ID);
                        $postal_code = $null_rider_deliveries[0]["postal_code"];
                        $nearby_deliveries = $deliveryModel->get_nearby_deliveries($postal_code);

                        //loop for assign all
                        foreach ( $nearby_deliveries as $key=>$nearby_delivery) {
                            $updated_rider_ID=$deliveryModel->update_rider_ID($nearby_delivery["delivery_ID"],Application::$app->session->get('userObject')->emp_ID);
                        }
                        
                    } else {
                        $rider = $riderMOdel->enqueue_rider(Application::$app->session->get('userObject')->emp_ID);
                    }
                    $rider=$riderMOdel->make_rider_online(Application::$app->session->get('userObject')->emp_ID);
                }

                $delivery=$deliveryModel->get_unfinished_deliveries(Application::$app->session->get('userObject')->emp_ID);
                return $this->render('delivery/delivery-my-deliveries' ,[
                    'deliveries'=>$delivery,
                    'model'=>$deliveryModel
                ]);

            } else {

                //if pin is incorrect
                $delivery=$deliveryModel->view_delivery_details($parameters[0]['id']); 
                $this->setLayout("delivery-rider",['select'=>'My Deliveries']);
                return $this->render('delivery/delivery-view-delivery' ,[
                    'delivery'=>$delivery[0],
                    'model'=>$deliveryModel,
                    'err'=>'incorrect_pin'
                ]);
            }
        }
    }



    //View My Details
    public function viewPersonalDetails(){
        $this->setLayout("delivery-rider",['select'=>'My Detail']);
        $userModel = new Employee();
        $user = $userModel->customFetchAll("SELECT * FROM employee WHERE email=".'"'.Application::$app->session->get('user').'"');
        return $this->render('delivery/delivery-view-personal-details' ,[
            'user' => $user[0]
        ]);
    }

    public function editPersonalDetails(Request $request,Response $response){  
        $parameters=$request->getParameters();
        
        $this->setLayout("delivery-rider",['select'=>'My Detail']);
        $employeeModel=new Employee();
        
        if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
            $employee=$employeeModel->get_employee_details($parameters[1]['id']);
            $employeeModel->updateData($employee,$employeeModel->fileDestination());
            Application::$app->session->set('employee',$parameters[1]['id']);
            return $this->render('delivery/delivery-update-personal-details',[
                'model'=>$employeeModel,
                'user' => $employee[0]
            ]);
        }

        if($request->isPost()){
            // update personal details
            $employeeModel->loadData($request->getBody());
            $employeeModel->loadFiles($_FILES);
            if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='update'){
                $curr_employee = $employeeModel->get_employee_details($parameters[1]['id']);
                if(!isset($_POST['img'])){
                    $employeeModel->img = $curr_employee[0]['img'];
                }
                if($_POST['gender'] === 'select'){
                    $employeeModel->gender = $curr_employee[0]['gender'];
                }
                $employeeModel->emp_ID = $curr_employee[0]['emp_ID'];
                $employeeModel->role = $curr_employee[0]['role'];
                $employeeModel->password = $curr_employee[0]['password'];
                $employeeModel->cpassword = $curr_employee[0]['password'];

                //NIC duplication in validate
                //Remove existing nic and try, If not validated restore NIC.
                // $nic_remove = $employeeModel->customFetchAll("UPDATE employee SET nic=NULL WHERE emp_ID=$curr_employee[0]['emp_ID']");
                if($employeeModel->validate() && $employeeModel->updateRecord(['emp_ID'=>$parameters[1]['id']])){
                    // $response->redirect('/ctest/delivery-view-advertisement'); 
                    Application::$app->session->setFlash('success',"User Profile Updated Successfully.");
                    Application::$app->response->redirect('/ctest/delivery-view-personal-details');
                    exit; 
                };

                // var_dump($curr_employee);
                // var_dump($_POST);
                var_dump($employeeModel);
                exit;
            } 
            
            // if($employeeModel->validate() && $employeeModel->register()){
            //     echo 'efwesagdsg';
            //         // exit;
            //     Application::$app->session->setFlash('success',"Employee successfully added ");
            //     Application::$app->response->redirect('/ctest/pharmacy-view-personal-details'); 
            //     $this->setLayout("pharmacy",['select'=>'My Detail']);
            //     $userModel = new Employee();
            //     $user = $userModel->customFetchAll("SELECT * FROM employee WHERE email=".'"'.Application::$app->session->get('user').'"');
            //     return $this->render('pharmacy/pharmacy-view-personal-details',[
            //         'user'=>$user,
            //         'model'=>$userModel
            //     ]);
            // }
        }

        // echo "finish";
        // exit;
        return $this->render('delivery/delivery-view-personal-details',[
            'model'=>$employeeModel,
        ]);
    }

    //Online Offline Handling
    public function makeOnline(){
        $riderMOdel = new Employee;
        $rider=$riderMOdel->make_rider_online(Application::$app->session->get('userObject')->emp_ID);
        return json_encode(['status'=>true,'message'=>'user online']);
    }

    public function makeOffline(){
        $riderMOdel = new Employee;
        $rider=$riderMOdel->make_rider_offline(Application::$app->session->get('userObject')->emp_ID);
        return json_encode(['status'=>true,'message'=>'User Offlined']);
    }

}