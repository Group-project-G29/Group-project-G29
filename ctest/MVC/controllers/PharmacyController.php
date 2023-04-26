<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\Delivery;
use app\models\Patient;
use app\models\PatientNotification;
use app\models\Prescription;
use app\models\User;
use app\core\DbModel;
use app\core\Response;
use app\models\Medicine;
use app\models\Employee;
use app\models\Advertisement;
use app\models\Order;
use app\models\PharmacyAdvertisement;

class PharmacyController extends Controller{

 //==========================PREVIOUS ORDERS=====================================
    public function viewPreviousOrder(){
        $this->setLayout("pharmacy",['select'=>'Previous Orders']);
        $orderModel=new Order();
        $orders=$orderModel->get_previous_orders(); 
        $order_types = array();
        foreach ($orders as $key=>$order){
            $order_types[$key] = $orderModel->getOrderType($order['order_ID']);
        }
        return $this->render('pharmacy/pharmacy-orders-previous',[
            'orders'=>$orders,
            'model'=>$orderModel,
            'order_types'=>$order_types
        ]);
    }

    public function DetailsPreviousOrder($request){
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Previous Orders']);
        $orderModel=new Order();
        
        $order_type = $orderModel->getOrderType($parameters[0]['id']);

        if ( $order_type == 'Online Order' ) {
            $orders=$orderModel->view_previous_online_order_details($parameters[0]['id']);
            return $this->render('pharmacy/pharmacy-view-previous-order',[
                'orders'=>$orders,
                'order_type'=>$order_type,
                'model'=>$orderModel,
            ]);

        } else if ( $order_type == 'E-prescription' ) {
            $orders=$orderModel->view_previous_prescription__order_details($parameters[0]['id']);
            return $this->render('pharmacy/pharmacy-view-previous-order',[
                'orders'=>$orders,
                'order_type'=>$order_type,
                'model'=>$orderModel,
            ]);

        } else if ( $order_type == 'Softcopy-prescription' ) {
            $orders=$orderModel->view_previous_prescription__order_details($parameters[0]['id']);
            return $this->render('pharmacy/pharmacy-view-previous-order',[
                'orders'=>$orders,
                'order_type'=>$order_type,
                'model'=>$orderModel,
            ]);
        }
    }

//===========================PENDING ORDERS======================================
    public function viewPendingOrder(){
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $orders=$orderModel->get_pending_orders(); 
        $order_types = array();
        foreach ($orders as $key=>$order){
            $order_types[$key] = $orderModel->getOrderType($order['order_ID']);
        }
        return $this->render('pharmacy/pharmacy-orders-pending',[
            'orders'=>$orders,
            'model'=>$orderModel,
            'order_types'=>$order_types
        ]);
    }

    public function DetailsPendingOrder($request){
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        
        $order_type = $orderModel->getOrderType($parameters[0]['id']);

        if ( $order_type == 'Online Order' ) {
            $orders=$orderModel->view_online_order_details($parameters[0]['id']);
            return $this->render('pharmacy/pharmacy-view-pending-order',[
                'orders'=>$orders,
                'order_type'=>$order_type,
                'model'=>$orderModel,
            ]);

        } else if ( $order_type == 'E-prescription' ) {
            $orders=$orderModel->view_prescription_details($parameters[0]['id']);
            return $this->render('pharmacy/pharmacy-view-pending-order',[
                'orders'=>$orders,
                'order_type'=>$order_type,
                'model'=>$orderModel,
            ]);

        } else if ( $order_type == 'Softcopy-prescription' ) {
            $orders=$orderModel->view_prescription_details($parameters[0]['id']);
            // if ($orders){
                // return $this->render('pharmacy/pharmacy-view-pending-order',[
                //     'orders'=>$orders,
                //     'order_type'=>$order_type,
                //     'model'=>$orderModel,
                // ]);
            // } else {
                $prescriptionModel = new Prescription;
                $orders=$prescriptionModel->get_prescription_location($parameters[0]['id']);
                // var_dump($orders);
                // exit;
                return $this->render('pharmacy/pharmacy-view-pending-order',[
                    'orders'=>$orders,
                    'order_type'=>$order_type,
                    'model'=>$prescriptionModel,
                ]);
            // }
        }
    }

    public function TakePendingOrder($request){
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $order_type = $orderModel->getOrderType($parameters[0]['id']);
        $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'processing');
        
        if ( $order_type =='Online Order') {
            $orders=$orderModel->view_online_order_details($parameters[0]['id']);
            return $this->render('pharmacy/pharmacy-view-processing-order',[
                'orders'=>$orders,
                'order_type'=>$order_type,
                'model'=>$orderModel,
            ]);
        } else if ( $order_type == 'E-prescription' ) {
            $orders=$orderModel->view_prescription_details($parameters[0]['id']);
            return $this->render('pharmacy/pharmacy-view-processing-order',[
                'orders'=>$orders,
                'order_type'=>$order_type,
                'model'=>$orderModel,
            ]);
            
        } else if ( $order_type='Softcopy-prescription' ) {
            $orders=$orderModel->view_prescription_details($parameters[0]['id']);
            $prescriptionModel = new Prescription;
            $orders=$prescriptionModel->get_prescription_location($parameters[0]['id']);
            return $this->render('pharmacy/pharmacy-view-pending-sf-order',[
                'orders'=>$orders,
                'order_type'=>$order_type,
                'model'=>$prescriptionModel,
            ]);
        }

    }

    public function deleteRejectedOrder(Request $request){
        $parameters=$request->getParameters();
        $orderModel=new Order();
        $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'deleted');

        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orders=$orderModel->get_pending_orders(); 
        $order_types = array();
        foreach ($orders as $key=>$order){
            $order_types[$key] = $orderModel->getOrderType($order['order_ID']);
        }
        return $this->render('pharmacy/pharmacy-orders-pending',[
            'popup' => 'deleted_order',
            'orders'=>$orders,
            'model'=>$orderModel,
            'order_types'=>$order_types
        ]);
    }
    
//=========================PROCESSING ORDERS====================================
    public function viewProcessingOrder(){
        $this->setLayout("pharmacy",['select'=>'Orders']);

        $orderModel=new Order();
        $orders=$orderModel->get_processing_orders();
        
        $order_types = array();
        foreach ($orders as $key=>$order){
            $order_types[$key] = $orderModel->getOrderType($order['order_ID']);
        }
        return $this->render('pharmacy/pharmacy-orders-processing',[
            'orders'=>$orders,
            'model'=>$orderModel,
            'order_types'=>$order_types
        ]);
    }

    public function DetailsProcessingOrder($request){   //test the function----------->
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $order_type = $orderModel->getOrderType($parameters[0]['id']);

        if ( $order_type == 'Online Order' ) {
            $orders=$orderModel->view_online_order_details($parameters[0]['id']);
            return $this->render('pharmacy/pharmacy-view-processing-order',[
                'orders'=>$orders,
                'order_type'=>$order_type,
                'model'=>$orderModel,
            ]);

        } else if ( $order_type == 'E-prescription' ) {
            $orders=$orderModel->view_prescription_details($parameters[0]['id']);
            return $this->render('pharmacy/pharmacy-view-processing-order',[
                'orders'=>$orders,
                'order_type'=>$order_type,
                'model'=>$orderModel,
            ]);

        } else if ( $order_type == 'Softcopy-prescription' ) {
            $prescriptionModel = new Prescription;
            $orders=$prescriptionModel->get_prescription_location($parameters[0]['id']);
            $curr_pres_orders = $prescriptionModel->get_curr_orders($orders[0]['prescription_ID']);

            // var_dump($orders);
            // var_dump($curr_pres_orders);
            // exit;
            return $this->render('pharmacy/pharmacy-view-pending-sf-order',[
                'orders'=>$orders,
                'order_type'=>$order_type,
                'model'=>$orderModel,
                'curr_pres_orders'=>$curr_pres_orders 
            ]);
        }
                                        // $parameters=$request->getParameters();
                                        // $this->setLayout("pharmacy",['select'=>'Orders']);
                                        // $orderModel=new Order();
                                        // $orders=$orderModel->view_online_order_details($parameters[0]['id']);
                                        // return $this->render('pharmacy/pharmacy-view-processing-order',[
                                        //     'orders'=>$orders,
                                        //     'model'=>$orderModel,
                                        // ])
    }

    public function addMedicineProcessingOrder($request){     //NOT
        
        $parameters=$request->getParameters();
        // var_dump($parameters);
        // exit;
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        
        // $order_type = $orderModel->getOrderType($parameters[0]['id']);

        // if ( $order_type == 'Online Order' ) {
        //     $orders=$orderModel->view_online_order_details($parameters[0]['id']);
        //     return $this->render('pharmacy/pharmacy-view-processing-order',[
        //         'orders'=>$orders,
        //         'model'=>$orderModel,
        //     ]);

        // } else if ( $order_type == 'Softcopy-prescription' ) {
            $orders=$orderModel->view_prescription_details($parameters[0]['id']);
            // var_dump(($orders));
            // EXIT;
            $prescreption = new Prescription;
            // $prescreption_medicine = $prescreption->add_med_rec($_POST["medicine"], $parameters[1]['presid'], $_POST["amount"]);
            $curr_pres_orders = $prescreption->get_curr_orders($orders[0]['prescription_ID']);
            return $this->render('pharmacy/pharmacy-view-pending-sf-order',[
                'orders'=>$orders,   
                'model'=>$prescreption,
                'curr_pres_orders'=>$curr_pres_orders 
            ]);
        // }
        
    }

    public function notifyProcessingOrder($request){
        $parameters=$request->getParameters();
        // var_dump($parameters);
        // echo "notification sent";

        $orderModel = new Order();
        $order = $orderModel->getOrderByID($parameters[0]['id']);

        $notificationModel = new PatientNotification();
        $existing_notifications = $notificationModel->getNotificationIDs($parameters[0]['id']);

        if ($existing_notifications){
            foreach ($existing_notifications as $key=>$existing_notification) {
                $remove_notifications = $notificationModel->removeNotifications($existing_notification["noti_ID"]);
            }
        }

        $create_notification = $notificationModel->createOrderNotification( $order[0]['order_ID'], $order[0]['patient_ID'] );
        $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'waiting');

        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $orders=$orderModel->get_pending_orders(); 
        $order_types = array();
        foreach ($orders as $key=>$order){
            $order_types[$key] = $orderModel->getOrderType($order['order_ID']);
        }
        return $this->render('pharmacy/pharmacy-orders-pending',[
            'popup' => 'notify-na-medicine',
            'orders'=>$orders,
            'model'=>$orderModel,
            'order_types'=>$order_types
        ]);
        // header("/ctest/pharmacy-orders-pending");
        // $this->viewPendingOrder();
    }

    public function cancleProcessingOrder($request){
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $order_type = $orderModel->getOrderType($parameters[0]['id']);

        // if ( $order_type='Online Order' || $order_type='E-prescription' ) {
            $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'pending');
        // } else if ( $order_type='Softcopy-prescription' ){
            
        // }

        $orders=$orderModel->get_pending_orders(); 
        $order_types = array();
        foreach ($orders as $key=>$order){
            $order_types[$key] = $orderModel->getOrderType($order['order_ID']);
        }
        return $this->render('pharmacy/pharmacy-orders-pending',[
            'orders'=>$orders,
            'model'=>$orderModel,
            'order_types'=>$order_types
        ]);
    }

    public function finishProcessingOrder($request){    //check if the order is pickup or delivery
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $riderMOdel = new Employee;
        $orderModel=new Order();
        
        //selected the available rider 
        $postal_code = $orderModel->get_postal_code($parameters[0]['id']);
        // var_dump($postal_code);
        // exit;

        if ( $postal_code[0]["pickup_status"] == 'delivery' ) {
            $rider = $riderMOdel->select_suitable_rider($postal_code[0]["postal_code"], $postal_code[0]["order_ID"]);
            $deliveryModel = new Delivery;
            if($rider) {
                echo 'suitable rider selected';
                $updated_rider_ID=$deliveryModel->update_rider_ID($postal_code[0]["delivery_ID"], $rider[0]["delivery_rider"]);
            } else {
                $rider = $riderMOdel->select_queue_rider();

                if ($rider) {
                    echo 'queue rider selected';
                    $updated_rider_ID=$deliveryModel->update_rider_ID($postal_code[0]["delivery_ID"], $rider[0]["delivery_rider_ID"]);
                    //dequeue a delivery rider - check something went wrong-> deleted few records at once
                    $deleted_rider = $riderMOdel->dequeue_rider($rider[0]["delivery_rider_ID"]);
                } else {
                    //if there were no rider available in the queue
                    echo 'null rider selected';
                    $updated_order=$deliveryModel->set_delivery_without_rider($parameters[0]['id']);
                }
            }
            exit;
        }
            
        $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'packed');
        $orders=$orderModel->get_packed_orders();
        return $this->render('pharmacy/pharmacy-orders-delivering',[
            'orders'=>$orders,
            'model'=>$orderModel
        ]);
    }
    
    //==========================DELIVERING ORDERS=====================================
    public function viewDeliveringOrder(){
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $orders=$orderModel->get_packed_orders();
        
        $order_types = array();
        foreach ($orders as $key=>$order){
            $order_types[$key] = $orderModel->getOrderType($order['order_ID']);
        }
        return $this->render('pharmacy/pharmacy-orders-delivering',[
            'orders'=>$orders,
            'model'=>$orderModel,
            'order_types'=>$order_types
        ]);
    }

    public function trackOrder($request){
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $order_type = $orderModel->getOrderType($parameters[0]['id']);

        if ( $order_type =='Online Order' ){
            $orders=$orderModel->view_online_order_details($parameters[0]['id']);        
        } else if ( $order_type=='E-prescription' || $order_type=='Softcopy-prescription' ){
            $orders=$orderModel->view_prescription_details($parameters[0]['id']);        
        }
        return $this->render('pharmacy/pharmacy-track-order',[
            'orders'=>$orders,
            'model'=>$orderModel,
        ]);
    }

    public function processOrderAgain(Request $request){
        // echo "processOrderAgain";
        // exit;
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $order_type = $orderModel->getOrderType($parameters[0]['id']);
        $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'processing');
        
        if ( $order_type =='Online Order') {
            $orders=$orderModel->view_online_order_details($parameters[0]['id']);
            return $this->render('pharmacy/pharmacy-view-processing-order',[
                'orders'=>$orders,
                'order_type'=>$order_type,
                'model'=>$orderModel,
            ]);
        } else if ( $order_type == 'E-prescription' ) {
            $orders=$orderModel->view_prescription_details($parameters[0]['id']);
            return $this->render('pharmacy/pharmacy-view-processing-order',[
                'orders'=>$orders,
                'order_type'=>$order_type,
                'model'=>$orderModel,
            ]);
            
        } else if ( $order_type='Softcopy-prescription' ) {
            $orders=$orderModel->view_prescription_details($parameters[0]['id']);
            $prescriptionModel = new Prescription;
            $orders=$prescriptionModel->get_prescription_location($parameters[0]['id']);
            return $this->render('pharmacy/pharmacy-view-pending-sf-order',[
                'orders'=>$orders,
                'order_type'=>$order_type,
                'model'=>$prescriptionModel,
            ]);
        }
    }

    public function pickupOrder(Request $request){
        $parameters=$request->getParameters();
        $orderModel=new Order();
        $order_type = $orderModel->getOrderType($parameters[0]['id']);
        $curr_order=$orderModel->getOrderByID($parameters[0]['id']);
        $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'pickedup');
        $this->setLayout("pharmacy",['select'=>'Previous Orders']);
        $orders=$orderModel->get_previous_orders(); 
        $order_types = array();
        foreach ($orders as $key=>$order){
            $order_types[$key] = $orderModel->getOrderType($order['order_ID']);
        }
        return $this->render('pharmacy/pharmacy-orders-previous',[
            'orders'=>$orders,
            'model'=>$orderModel,
            'order_types'=>$order_types
        ]);
    }

//=======================CREATE NEW ORDERS===================================
    public function createNewOrder(Request $request,Response $response){
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        var_dump($orderModel);
        // echo "dfasd";
        // exit;

        if($request->isPost()){
        var_dump($_POST);
        $orderModel->loadData($request->getBody());
        // $orderModel->loadFiles($_FILES);
        var_dump($orderModel);
        exit;

            //enter to _order table
            //get order id in last one where pid|contact
            //same assf create order
                
                // $orders=$orderModel->get_prescription_location($parameters[0]['id']); //last order id
                // var_dump($orderModel);
                // exit;
                return $this->render('pharmacy/pharmacy-view-pending-sf-order',[
                    // 'orders'=>$orders,
                    'model'=>$orderModel,
                ]);
        }

        // var_dump($orderModel);
        // exit;
        return $this->render('pharmacy/pharmacy-new-order-form',[
            'model'=>$orderModel
        ]);
    }

    public function addNewOrderItem($request) {
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        
        $prescreption = new Prescription;
        $prescreption_medicine = $prescreption->add_med_rec($_POST["medicine"], $parameters[1]['presid'], $_POST["amount"]);
        $curr_pres_orders = $prescreption->get_curr_orders($parameters[1]['presid']);
        $patient = $prescreption->get_patient_details($parameters[1]['presid']);
        $orders=$prescreption->get_prescription_location($patient[0]['order_ID']);
        // var_dump($_POST);
        // var_dump($parameters);
        // // var_dump($prescreption_medicine);
        // var_dump($curr_pres_orders);
        // var_dump($patient);
        // exit;
        return $this->render('pharmacy/pharmacy-view-pending-sf-order',[
            //pass medicine names strength [panadol-50mg=>panadol-tablet],

            'orders'=>$orders,    //to get patient details and prescription photo
            'model'=>$prescreption,
            'curr_pres_orders'=>$curr_pres_orders  // to get medicines of prescription
        ]);
    }


//==============================MEDICINES===========================================    

    //delete update insert  medicine
    public function handleMedicine(Request $request,Response $response){

        $parameters=$request->getParameters();
        $this->setLayout('pharmacy',['select'=>'Medicines']);
        $medicineModel=new Medicine();

        //Delete operation
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='delete'){
            $medicineModel->deleteRecord(['med_ID'=>$parameters[1]['id']]);
            Application::$app->session->setFlash('success',"Medicine successfully deleted ");
            $response->redirect('/ctest/pharmacy-view-medicine');
            return true;
        }

        //Go to update page of a medicine
        if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
            $medicine=$medicineModel->get_medicine_details($parameters[1]['id']);
            $medicineModel->updateData($medicine,$medicineModel->fileDestination());
            Application::$app->session->set('medicine',$parameters[1]['id']);
            return $this->render('pharmacy/pharmacy-update-medicine',[
                'model'=>$medicineModel,
            ]);
        }

        if($request->isPost()){
            $medicineModel->loadData($request->getBody());
            $medicineModel->loadFiles($_FILES);
            
            //Update medicine
            if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='update'){
                $current_med = $medicineModel->get_medicine_details($parameters[1]['id']);
                
                if(!isset($_POST['img'])){
                    $medicineModel->img = $current_med[0]['img'];
                }
                $medicineModel->name = $current_med[0]['name'];
                $medicineModel->brand = $current_med[0]['brand'];
                $medicineModel->strength = $current_med[0]['strength'];
                $medicineModel->category = $current_med[0]['category'];
                $medicineModel->unit = $current_med[0]['unit'];

                if($medicineModel->validate() && $medicineModel->updateRecord(['med_ID'=>$parameters[1]['id']])){
                    $response->redirect('/ctest/view-medicine'); 
                    Application::$app->session->setFlash('success',"Medicine successfully updated ");
                    Application::$app->response->redirect('/ctest/pharmacy-view-medicine');
                    exit; 
                 } else {
                    // echo 'implement';
                    // exit;
                    $medicine=$medicineModel->get_medicine_details($parameters[1]['id']);
                    $medicineModel->updateData($medicine,$medicineModel->fileDestination());
                    Application::$app->session->set('medicine',$parameters[1]['id']);
                    return $this->render('pharmacy/pharmacy-update-medicine',[
                        'model'=>$medicineModel,
                    ]);
                 };
            } 
            
            //add new medicine
            if($medicineModel->validate() && $medicineModel->addMedicine()){
               Application::$app->session->setFlash('success',"Medicine successfully added ");
               Application::$app->response->redirect('/ctest/pharmacy-view-medicine'); 
               $this->setLayout("pharmacy",['select'=>'Medicines']);
               $medicineModel=new Medicine();
               $medicines=$medicineModel->select_medical_products();
               return $this->render('pharmacy/pharmacy-view-medicine',[
                   'medicines'=>$medicines,
                   'model'=>$medicineModel
               ]);
            }

        }

        return $this->render('pharmacy/pharmacy-add-medicine',[
            'model'=>$medicineModel,
        ]);
    }

    //view medicine
    public function viewMedicine(){
        $this->setLayout("pharmacy",['select'=>'Medicines']);
        $medicineModel=new Medicine();
        $medicines=$medicineModel->select_medical_products();
        return $this->render('pharmacy/pharmacy-view-medicine',[
            'medicines'=>$medicines,
            'model'=>$medicineModel
        ]);

    }


//==============================ADVERTISEMENTS===========================================

    //delete update insert advertisement
    public function handleAdvertisement(Request $request,Response $response){
        $parameters=$request->getParameters();
        $this->setLayout('pharmacy',['select'=>'Advertisement']);
        $advertisementModel=new PharmacyAdvertisement();
        
        //Delete operation
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='delete'){
            $current_add = $advertisementModel->get_selected_advertisement_details($parameters[1]['id']);
            $advertisementModel->deleteImage($current_add[0]['img']);       //unlink image - delete from folder
            $advertisementModel->deleteRecord(['ad_ID'=>$parameters[1]['id']]);
            Application::$app->session->setFlash('success',"Advertisement successfully deleted ");
            $response->redirect('/ctest/pharmacy-view-advertisement');
            return true;
        }
        
        //Go to update page of a advertisement
        if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
            $advertisement=$advertisementModel->get_selected_advertisement_details($parameters[1]['id']);
            $advertisementModel->updateData($advertisement,$advertisementModel->fileDestination());
            Application::$app->session->set('advertisement',$parameters[1]['id']);
            return $this->render('pharmacy/pharmacy-update-advertisement',[
                'model'=>$advertisementModel,
            ]);
        }
        
        if($request->isPost()){
            // update advertisement
            
            $advertisementModel->loadData($request->getBody());
            $advertisementModel->loadFiles($_FILES);

            if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='update'){
                $current_add = $advertisementModel->get_selected_advertisement_details($parameters[1]['id']);
                if(!isset($_POST['img'])){
                    $advertisementModel->img = $current_add[0]['img'];
                    // $advertisementModel->change_image($current_add[0]["img"]);
                }
                if($advertisementModel->validate() && $advertisementModel->updateRecord(['ad_ID'=>$parameters[1]['id']])){
                    $advertisementModel->deleteImage($current_add[0]['img']);   //delete previous photo when update done successfully
                    $response->redirect('/ctest/pharmacy-view-advertisement'); 
                    Application::$app->session->setFlash('success',"Advertisement successfully updated ");
                    Application::$app->response->redirect('/ctest/pharmacy-view-advertisement');
                    exit; 
                 };
            } 

            // add new advertisement
            if($advertisementModel->validate() && $advertisementModel->addAdvertisement()){
               Application::$app->session->setFlash('success',"Advertisement successfully added ");
               Application::$app->response->redirect('/ctest/pharmacy-view-advertisement'); 
               $this->setLayout("pharmacy",['select'=>'Advertisement']);
               $advertisementModel=new PharmacyAdvertisement();
            //    $advertisements=$advertisementModel->select_pharmacy_advertisements();
               $advertisements=$advertisementModel->get_advertisements();
               return $this->render('pharmacy/pharmacy-view-advertisement',[
                   'advertisements'=>$advertisements,
                   'model'=>$advertisementModel
               ]);
       
            }
        }

        return $this->render('pharmacy/pharmacy-add-advertisement',[
            'model'=>$advertisementModel,
        ]);
    }


    //view advertisement
    public function viewAdvertisement(){
        $this->setLayout("pharmacy",['select'=>'Advertisement']);
        $advertisementModel=new PharmacyAdvertisement();
        $advertisements=$advertisementModel->get_advertisements();
        return $this->render('pharmacy/pharmacy-view-advertisement',[
            'advertisements'=>$advertisements,
            'model'=>$advertisementModel
        ]);
    }


//==============================REPORTS===========================================    

    //View Reports
    public function viewReports(){
        $this->setLayout("pharmacy",['select'=>'Report']);
        // $userModel = new Employee();
        // $user = $userModel->findOne(106);
        // $user = $userModel->customFetchAll("SELECT * FROM employee WHERE email=".'"'.Application::$app->session->get('user').'"');
        return $this->render('pharmacy/pharmacy-view-report',[
            // 'user' => $user[0]
        ]);
    }


//==============================MY DETAILS===========================================    

    //View My Details
    public function viewPersonalDetails(){
        $this->setLayout("pharmacy",['select'=>'My Detail']);
        $userModel = new Employee();
        // $user = $userModel->findOne(106);
        $user = $userModel->customFetchAll("SELECT * FROM employee WHERE email=".'"'.Application::$app->session->get('user').'"');
        return $this->render('pharmacy/pharmacy-view-personal-details',[
            'user' => $user[0]
        ]);
    }

    //Update My Details
    public function editPersonalDetails(Request $request,Response $response){   //implement
        $parameters=$request->getParameters();
        
        $this->setLayout('pharmacy',['select'=>'My Detail']);
        $employeeModel=new Employee();
        
        if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
            $employee=$employeeModel->get_employee_details($parameters[1]['id']);
            $employeeModel->updateData($employee,$employeeModel->fileDestination());
            Application::$app->session->set('employee',$parameters[1]['id']);
            return $this->render('pharmacy/pharmacy-update-personal-details',[
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
                    $response->redirect('/ctest/pharmacy-view-advertisement'); 
                    Application::$app->session->setFlash('success',"User Profile Updated Successfully.");
                    Application::$app->response->redirect('/ctest/pharmacy-view-personal-details');
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
        return $this->render('pharmacy/pharmacy-view-personal-details',[
            'model'=>$employeeModel,
        ]);
    }

}