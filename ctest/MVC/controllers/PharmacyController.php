<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\Delivery;
use app\models\Patient;
use app\models\PatientNotification;
use app\models\Payment;
use app\models\Prescription;
use app\models\User;
use app\core\DbModel;
use app\core\Response;
use app\models\Medicine;
use app\models\Employee;
use app\models\Advertisement;
use app\models\Order;
use app\models\FrontdeskOrder;
use app\models\PharmacyAdvertisement;

class PharmacyController extends Controller{

//=======================CREATE NEW ORDERS===================================
    public function createNewFrontdeskOrder(Request $request,Response $response){
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Front Desk Orders']);
        $orderModel=new FrontdeskOrder();

        if($request->isPost()){

            $orderModel->loadData($request->getBody());
            $orderModel->pharmacist_ID = Application::$app->session->get('userObject')->emp_ID;
            $orderModel->date = date("Y-m-d");
            $orderModel->time = date("h:i:s");
            $orderModel->addFrontdeskOrder();
            $order_details = $orderModel->get_last_inserted_order($orderModel->contact)[0];
            $order_medicines = $orderModel->get_order_medicines($order_details["order_ID"]);

            //all medicine details
            $medicineModel = new Medicine();
            $medicine_array = $medicineModel->getAllMedicine();
                
            return $this->render('pharmacy/pharmacy-frontdesk-view-pending',[
                'order_details'=>$order_details,
                'order_medicines'=>$order_medicines,
                'medicine_array' => $medicine_array
            ]);
        }

        return $this->render('pharmacy/pharmacy-new-order-form',[
            'ordermodel'=>$orderModel,
        ]);
    }

//==========================FRONT DESK ORDERS=====================================
    public function viewFrontdeskPendingOrder(){
        $this->setLayout("pharmacy",['select'=>'Front Desk Orders']);
        $orderModel=new FrontdeskOrder();
        //get frontdesk orders
        $orders=$orderModel->get_frontdesk_pending_orders(); 
        return $this->render('pharmacy/pharmacy-frontdesk-pending',[
            'orders'=>$orders,
            'model'=>$orderModel,
        ]);
    }

    public function detailsFrontdeskPending(Request $request){
        $parameters=$request->getParameters();
        $orderModel = new FrontdeskOrder();
        $order_details = $orderModel->get_order_details($parameters[0]['id'])[0];
        $order_medicines = $orderModel->get_order_medicines($parameters[0]['id']);
        
        //all medicine details
        $medicineModel = new Medicine();
        $medicine_array = $medicineModel->getAllMedicine();

        $this->setLayout("pharmacy",['select'=>'Front Desk Orders']);
        return $this->render('pharmacy/pharmacy-frontdesk-view-pending',[
            'order_details'=>$order_details,
            'order_medicines'=>$order_medicines,
            'medicine_array' => $medicine_array
        ]);
    }

    public function addNewFrontItem(Request $request){
        $parameters=$request->getParameters();

        // get the medicine name array
        $medName = explode('-',$_POST['name']);
        $medicineModel = new Medicine();
        // get medicine id using the above array
        $med_ID = $medicineModel->getMedicineID($medName[0],$medName[1]);
        // get medicine details
        $med_details = $medicineModel->get_medicine_details($med_ID);

        $orderModel = new FrontdeskOrder();
        if ( $med_details[0]["amount"]>=$_POST["amount"] ) { 
            // if available reduce stocks
            $reduce_med_amount = $medicineModel->reduceMedicine($med_ID, $_POST["amount"], true);
            $frontdesk_medicine = $orderModel->add_new_front_item($parameters[0]['id'], $med_ID, $_POST["amount"], $med_details[0]["unit_price"],'include');
        } else {
            $frontdesk_medicine = $orderModel->add_new_front_item($parameters[0]['id'], $med_ID, $_POST["amount"], $med_details[0]["unit_price"],'exclude');
        }

        //all medicine details
        $medicine_array = $medicineModel->getAllMedicine();

        $order_details = $orderModel->get_order_details($parameters[0]['id'])[0];
        $order_medicines = $orderModel->get_order_medicines($parameters[0]['id']);

        $this->setLayout("pharmacy",['select'=>'Front Desk Orders']);
        return $this->render('pharmacy/pharmacy-frontdesk-view-pending',[
            'order_details'=>$order_details,
            'order_medicines'=>$order_medicines,
            'medicine_array' => $medicine_array
        ]);
    }
    
    public function deleteFrontdeskOrder(Request $request){
        $parameters=$request->getParameters();
        $orderModel = new FrontdeskOrder();
        $medicineModel=new Medicine();

        $order_medicines = $orderModel->get_order_medicines($parameters[0]['id']);
        foreach($order_medicines as $order_medicine){
            if($order_medicine["status"]=='include'){
                $increase_med_stock = $medicineModel->increaseMedicine($order_medicine['med_ID'],$order_medicine['order_amount'],true);
            }
        }
        $deleted_medicines = $orderModel->delete_med_record($parameters[0]['id']);
        $deleted_order = $orderModel->delete_order($parameters[0]['id']);
        
        $this->setLayout("pharmacy",['select'=>'Front Desk Orders']);
        //get frontdesk orders
        $orders=$orderModel->get_frontdesk_pending_orders(); 
        return $this->render('pharmacy/pharmacy-frontdesk-pending',[
            'orders'=>$orders,
            'model'=>$orderModel,
        ]);
    }

    public function finishFrontdeskOrder(Request $request){
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);

        $orderModel = new FrontdeskOrder();
        $update_order_status = $orderModel->set_processing_status($parameters[0]['id'],'packed');
        $update_total = $orderModel->write_total($parameters[0]['id'],$parameters[1]['total']);

        $order_details = $orderModel->get_order_details($parameters[0]['id']);
        $order_medicines = $orderModel->get_order_medicines($parameters[0]['id']);
        
        $this->setLayout("pharmacy",['select'=>'Front Desk Orders']);
        return $this->render('pharmacy/pharmacy-frontdesk-view-packed',[
            'order_details'=>$order_details,
            'order_medicines'=>$order_medicines,
        ]);
    }

    public function viewFrontdeskPackedOrder(){
        $this->setLayout("pharmacy",['select'=>'Front Desk Orders']);
        $orderModel=new FrontdeskOrder();
        //get frontdesk orders
        $orders=$orderModel->get_frontdesk_packed_orders(); 
        return $this->render('pharmacy/pharmacy-frontdesk-packed',[
            'orders'=>$orders,
            'model'=>$orderModel,
        ]);
    }

    public function detailsFrontdeskPacked(Request $request){
        $parameters=$request->getParameters();
        $orderModel = new FrontdeskOrder();
        $update_order_status = $orderModel->set_processing_status($parameters[0]['id'],'pending');
        $order_details = $orderModel->get_order_details($parameters[0]['id']);
        $order_medicines = $orderModel->get_order_medicines($parameters[0]['id']);
        
        $this->setLayout("pharmacy",['select'=>'Front Desk Orders']);
        return $this->render('pharmacy/pharmacy-frontdesk-view-packed',[
            'order_details'=>$order_details,
            'order_medicines'=>$order_medicines,
        ]);
    }

    public function cancleFrontdeskOrder(Request $request) {
        $parameters=$request->getParameters();
        $orderModel = new FrontdeskOrder();
        $order_details = $orderModel->get_order_details($parameters[0]['id'])[0];
        $order_medicines = $orderModel->get_order_medicines($parameters[0]['id']);
        
        //all medicine details
        $medicineModel = new Medicine();
        $medicine_array = $medicineModel->getAllMedicine();

        $this->setLayout("pharmacy",['select'=>'Front Desk Orders']);
        return $this->render('pharmacy/pharmacy-frontdesk-view-pending',[
            'order_details'=>$order_details,
            'order_medicines'=>$order_medicines,
            'medicine_array' => $medicine_array
        ]);
    }

    public function pickupFrontdeskOrder(Request $request) {
        $parameters=$request->getParameters();
        $orderModel = new FrontdeskOrder();
        $update_order_status = $orderModel->set_processing_status($parameters[0]['id'],'pickedup');
        $update_payment_status = $orderModel->set_payment_status($parameters[0]['id']);

        $this->setLayout("pharmacy",['select'=>'Front Desk Orders']);
        //get frontdesk orders
        $orders=$orderModel->get_frontdesk_finished_orders(); 
        return $this->render('pharmacy/pharmacy-frontdesk-finished',[
            'orders'=>$orders,
            'model'=>$orderModel,
        ]);
    }

    public function viewFrontdeskFinishedOrder(){
        $this->setLayout("pharmacy",['select'=>'Front Desk Orders']);
        $orderModel=new FrontdeskOrder();
        //get frontdesk orders
        $orders=$orderModel->get_frontdesk_finished_orders(); 
        return $this->render('pharmacy/pharmacy-frontdesk-finished',[
            'orders'=>$orders,
            'model'=>$orderModel,
        ]);
    }

    public function detailsFrontdeskFinished(Request $request){
        $parameters=$request->getParameters();
        $orderModel = new FrontdeskOrder();
        $order_details = $orderModel->get_order_details($parameters[0]['id']);
        $order_medicines = $orderModel->get_order_medicines($parameters[0]['id']);

        $this->setLayout("pharmacy",['select'=>'Front Desk Orders']);
        return $this->render('pharmacy/pharmacy-frontdesk-view-finished',[
            'order_details'=>$order_details,
            'order_medicines'=>$order_medicines,
        ]);
    }

//==========================PREVIOUS ORDERS=====================================
    public function viewPreviousOrder(){
        $this->setLayout("pharmacy",['select'=>'Previous Orders']);
        $orderModel=new Order();
        //get previous orders
        $orders=$orderModel->get_previous_orders(); 
        return $this->render('pharmacy/pharmacy-orders-previous',[
            'orders'=>$orders,
            'model'=>$orderModel,
        ]);
    }

    public function DetailsPreviousOrder(Request $request){
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Previous Orders']);
        $orderModel=new Order();

        //order details
        $order_details = $orderModel->get_order_details($parameters[0]['id']);

        //online medicine details
        $online_orders = $orderModel->view_online_order_details($parameters[0]['id']);  
        
        //softcopy prescription details
        $sf_orders = $orderModel->take_sf_orders($parameters[0]['id']);                 
        $sf_pres_med = [];
        foreach ($sf_orders as $key=>$sf_order){
            $sf_pres_med[$sf_order['prescription_ID']] = $orderModel->view_prescription_details($sf_order['prescription_ID']);
        }
        //e-prescription details
        $ep_orders = $orderModel->take_ep_orders($parameters[0]['id']);                 
        $ep_pres_med = [];
        foreach ($ep_orders as $key=>$ep_order){
            $ep_pres_med[$ep_order['prescription_ID']] = $orderModel->view_prescription_details($ep_order['prescription_ID']);
        }

        return $this->render('pharmacy/pharmacy-view-previous-order',[
            'order_details'=>$order_details,
            'online_orders'=>$online_orders,
            'sf_pres_med'=>$sf_pres_med,
            'ep_pres_med'=>$ep_pres_med,
            'ep_orders'=>$ep_orders,
            'sf_orders'=>$sf_orders,
            'model'=>$orderModel,
        ]);
    }

//===========================PENDING ORDERS======================================
    public function viewPendingOrder(){
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        // get pending orders - pending, waiting, accepted, rejected
        $orders=$orderModel->get_pending_orders(); 
        return $this->render('pharmacy/pharmacy-orders-pending',[
            'orders'=>$orders,
            'model'=>$orderModel,
        ]);
    }

    public function DetailsPendingOrder(Request $request){

        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        //order details
        $order_details = $orderModel->get_order_details($parameters[0]['id']);

        //online medicine details
        $online_orders = $orderModel->view_online_order_details($parameters[0]['id']); 
        
        //softcopy prescription details
        $sf_orders = $orderModel->take_sf_orders($parameters[0]['id']);              
        $sf_pres_med = [];
        foreach ($sf_orders as $key=>$sf_order){
            $sf_pres_med[$sf_order['prescription_ID']] = $orderModel->view_prescription_details($sf_order['prescription_ID']);
        }
        //e-prescription details
        $ep_orders = $orderModel->take_ep_orders($parameters[0]['id']);      
        $ep_pres_med = [];
        foreach ($ep_orders as $key=>$ep_order){
            $ep_pres_med[$ep_order['prescription_ID']] = $orderModel->view_prescription_details($ep_order['prescription_ID']);
        }

        return $this->render('pharmacy/pharmacy-view-pending-order',[
            'order_details'=>$order_details,
            'online_orders'=>$online_orders,
            'sf_pres_med'=>$sf_pres_med,
            'ep_pres_med'=>$ep_pres_med,
            'ep_orders'=>$ep_orders,
            'sf_orders'=>$sf_orders,
            'model'=>$orderModel,
        ]);
    }

    public function TakePendingOrder(Request $request){
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $medicineModel=new Medicine();
        $prescriptionModel=new Prescription();
        $medicine_array = $medicineModel->getAllMedicine();

        // changing processing status - from pending|waiting|accepted|rejected to processing 
        $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'processing');
        
        $order_details = $orderModel->get_order_details($parameters[0]['id']);
        
        // online medicine details
        $online_orders = $orderModel->view_online_order_details($parameters[0]['id']);  
        // var_dump($online_orders);exit;
        // calculate total for online orderes medical products
        $online_total = 0;
        foreach ($online_orders as $key=>$online_order){
            if( $online_order["status"]=='include' ){
                $online_total = $online_total + (int)$online_order["order_amount"] * (int)$online_order["unit_price"];
            }
        }
        
        // e-prescription details
        $ep_orders = $orderModel->take_ep_orders($parameters[0]['id']);                 
        $ep_pres_med = [];
        // calculate e-prescription total
        $ep_total=0;
        foreach ($ep_orders as $key=>$ep_order){
            $ep_pres_med[$ep_order['prescription_ID']] = $orderModel->view_prescription_details($ep_order['prescription_ID']);
            $ep_total = $ep_total + (int)$ep_order["total_price"];
        }

        // softcopy prescription details
        $sf_orders = $orderModel->take_sf_orders($parameters[0]['id']);                 
        $sf_pres_med = [];
        foreach ($sf_orders as $key=>$sf_order){
            $sf_pres_med[$sf_order['prescription_ID']] = $orderModel->view_prescription_details($sf_order['prescription_ID']);
        }

        // reduce medicine from stocks if available
        $sf_total = 0;
        foreach ($sf_pres_med as $sf_pres_separate){
            foreach ($sf_pres_separate as $sf_pres_med_separate){
                if($sf_pres_med_separate['order_amount']<=$sf_pres_med_separate['available_amount']){
                    // reduce medicine amount from stocks
                    $reduce_med_amount = $medicineModel->reduceMedicine($sf_pres_med_separate['med_ID'],$sf_pres_med_separate['order_amount'], true);
                    // update prescription total for available medicines
                    $update_prescription_total = $prescriptionModel->update_prescription_total($sf_pres_med_separate["prescription_ID"],$sf_pres_med_separate["order_amount"]*$sf_pres_med_separate["current_price"]);
                    // calculate total
                    $sf_total = $sf_total + $sf_pres_med_separate["order_amount"]*$sf_pres_med_separate["current_price"];
                }
            }
        }

        // update total price of the order
        $update_order_total = $orderModel->write_total($order_details[0]["order_ID"], $online_total+$ep_total+$sf_total);

        return $this->render('pharmacy/pharmacy-view-processing-order',[
            'order_details'=>$order_details,
            'online_orders'=>$online_orders,
            'sf_pres_med'=>$sf_pres_med,
            'ep_pres_med'=>$ep_pres_med,
            'ep_orders'=>$ep_orders,
            'sf_orders'=>$sf_orders,
            'ordermodel'=>$orderModel,
            'prescriptionmodel'=>$prescriptionModel,
            'medicine_array'=>$medicine_array
        ]);
    }

    public function deleteRejectedOrder(Request $request){
        $parameters=$request->getParameters();
        $orderModel=new Order();
        $medicineModel=new Medicine();
        $prescriptionModel=new Prescription();
        // $medicine_array = $medicineModel->getAllMedicine();
        
        // get order details
        $order_details = $orderModel->get_order_details($parameters[0]['id']);
        
        // online medicine details
        $online_orders = $orderModel->view_online_order_details($parameters[0]['id']);  
        // calculate total for online orderes medical products
        $online_total = 0;
        foreach ($online_orders as $key=>$online_order){
            if( $online_order["status"]=='include' ) {
                $increase_med_amount = $medicineModel->increaseMedicine($online_order['med_ID'],$online_order['order_amount'],true);
            }
        }
        
        // e-prescription details
        $ep_orders = $orderModel->take_ep_orders($parameters[0]['id']);                 
        $ep_pres_med = [];
        // calculate e-prescription total
        $ep_total=0;
        foreach ($ep_orders as $key=>$ep_order){
            $ep_pres_med[$ep_order['prescription_ID']] = $orderModel->view_prescription_details($ep_order['prescription_ID']);
            $reset_total_pres = $prescriptionModel->reset_total($ep_order['prescription_ID']);
        }
        //increase medicine stocks if included
        foreach ($ep_pres_med as $ep_pres_separate){
            foreach ($ep_pres_separate as $ep_pres_med_separate){
                if( $ep_pres_med_separate['status']=='include' ){
                    $increase_med_amount = $medicineModel->increaseMedicine($ep_pres_med_separate['med_ID'],$ep_pres_med_separate['order_amount'],true);
                }
            }
        }
        
        // softcopy prescription details
        $sf_orders = $orderModel->take_sf_orders($parameters[0]['id']);                 
        $sf_pres_med = [];
        foreach ($sf_orders as $key=>$sf_order){
            $sf_pres_med[$sf_order['prescription_ID']] = $orderModel->view_prescription_details($sf_order['prescription_ID']);
            $reset_total_pres = $prescriptionModel->reset_total($sf_order['prescription_ID']);
        }

        // increase medicine stocks if included
        $sf_total = 0;
        foreach ($sf_pres_med as $sf_pres_separate){
            foreach ($sf_pres_separate as $sf_pres_med_separate){
                if($sf_pres_med_separate['status']=='include'){
                    $increase_med_amount = $medicineModel->increaseMedicine($sf_pres_med_separate['med_ID'],$sf_pres_med_separate['order_amount'],true);
                }
            }
        }

        //set processing status from rejected tio deleted
        $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'deleted');
        // reset total price of the order
        $reset_order_total = $orderModel->reset_total($parameters[0]['id']);

        $this->setLayout("pharmacy",['select'=>'Orders']);
        // get pending orders - pending, waiting, accepted, rejected
        $orders=$orderModel->get_pending_orders(); 
        return $this->render('pharmacy/pharmacy-orders-pending',[
            'popup' => 'deleted_order',
            'orders'=>$orders,
            'model'=>$orderModel,
        ]);
    }
    
//=========================PROCESSING ORDERS====================================
    public function viewProcessingOrder(){
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        // get processing orders
        $orders=$orderModel->get_processing_orders();
        return $this->render('pharmacy/pharmacy-orders-processing',[
            'orders'=>$orders,
            'model'=>$orderModel,
        ]);
    }

    public function DetailsProcessingOrder(Request $request){  
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $medicineModel=new Medicine();
        $prescriptionModel=new Prescription();

        //all medicine details
        $medicine_array = $medicineModel->getAllMedicine();

        //order details
        $order_details = $orderModel->get_order_details($parameters[0]['id']);

        //online medicine details
        $online_orders = $orderModel->view_online_order_details($parameters[0]['id']);  
        
        //softcopy prescription details
        $sf_orders = $orderModel->take_sf_orders($parameters[0]['id']);                 
        $sf_pres_med = [];
        foreach ($sf_orders as $key=>$sf_order){
            $sf_pres_med[$sf_order['prescription_ID']] = $orderModel->view_prescription_details($sf_order['prescription_ID']);
        }
        //e-prescription details
        $ep_orders = $orderModel->take_ep_orders($parameters[0]['id']);                 //prescription details
        $ep_pres_med = [];
        foreach ($ep_orders as $key=>$ep_order){
            $ep_pres_med[$ep_order['prescription_ID']] = $orderModel->view_prescription_details($ep_order['prescription_ID']);
        }

        return $this->render('pharmacy/pharmacy-view-processing-order',[
            'order_details'=>$order_details,
            'online_orders'=>$online_orders,
            'sf_pres_med'=>$sf_pres_med,
            'ep_pres_med'=>$ep_pres_med,
            'ep_orders'=>$ep_orders,
            'sf_orders'=>$sf_orders,
            'ordermodel'=>$orderModel,
            'prescriptionmodel'=>$prescriptionModel,
            'medicine_array'=>$medicine_array
        ]);
    }

    // public function addMedicineProcessingOrder(Request $request){     //NOT
        
    //     $parameters=$request->getParameters();
    //     // var_dump($parameters);
    //     // exit;
    //     $this->setLayout("pharmacy",['select'=>'Orders']);
    //     $orderModel=new Order();
        
    //     // $order_type = $orderModel->getOrderType($parameters[0]['id']);

    //     // if ( $order_type == 'Online Order' ) {
    //     //     $orders=$orderModel->view_online_order_details($parameters[0]['id']);
    //     //     return $this->render('pharmacy/pharmacy-view-processing-order',[
    //     //         'orders'=>$orders,
    //     //         'model'=>$orderModel,
    //     //     ]);

    //     // } else if ( $order_type == 'Softcopy-prescription' ) {
    //         $orders=$orderModel->view_prescription_details($parameters[0]['id']);
    //         // var_dump(($orders));
    //         // EXIT;
    //         $prescreption = new Prescription;
    //         // $prescreption_medicine = $prescreption->add_med_rec($_POST["medicine"], $parameters[1]['presid'], $_POST["amount"]);
    //         $curr_pres_orders = $prescreption->get_curr_orders($orders[0]['prescription_ID']);
    //         return $this->render('pharmacy/pharmacy-view-pending-sf-order',[
    //             'orders'=>$orders,   
    //             'model'=>$prescreption,
    //             'curr_pres_orders'=>$curr_pres_orders 
    //         ]);
    //     // }
    // }

    public function notifyProcessingOrder(Request $request){
        $parameters=$request->getParameters();

        $orderModel = new Order();
        $order = $orderModel->getOrderByID($parameters[0]['id']);

        $notificationModel = new PatientNotification();
        // check for existing notifications
        $existing_notifications = $notificationModel->getNotificationIDs($parameters[0]['id']);

        // if unread notifications exist, remove them
        if ($existing_notifications){
            foreach ($existing_notifications as $key=>$existing_notification) {
                $remove_notifications = $notificationModel->removeNotifications($existing_notification["noti_ID"]);
            }
        }

        // send new notification
        $create_notification = $notificationModel->createOrderNotification( $order[0]['order_ID'], $order[0]['patient_ID'] );
        // set processing status from processing to waiting
        // waits till the patient confirms or reject
        $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'waiting');

        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        // get pending orders - pending, waiting, accepted, rejected
        $orders=$orderModel->get_pending_orders(); 
        return $this->render('pharmacy/pharmacy-orders-pending',[
            'popup' => 'notify-na-medicine',
            'orders'=>$orders,
            'model'=>$orderModel,
        ]);
    }

    public function cancleProcessingOrder(Request $request){
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();

        // set processing status from processing to pending
        $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'pending');

        // get pending orders - pending, waiting, accepted, rejected
        $orders=$orderModel->get_pending_orders(); 
        return $this->render('pharmacy/pharmacy-orders-pending',[
            'orders'=>$orders,
            'model'=>$orderModel,
        ]);
    }

    public function finishProcessingOrder(Request $request){    //check if the order is pickup or delivery
        $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $riderMOdel = new Employee;
        $orderModel=new Order();

        // get the postal code and other details of the order
        $postal_code = $orderModel->get_postal_code($parameters[0]['id']);

        // check for the pickup status - pickup|delivery
        if ( $postal_code[0]["pickup_status"] == 'delivery' ) {
        // =====IF DELIVERY=====
            // select a suitable rider who has been already assigned to deliver an order to a nearby address (postal code)
            $rider = $riderMOdel->select_suitable_rider($postal_code[0]["postal_code"], $postal_code[0]["order_ID"]);
            $deliveryModel = new Delivery;
            if($rider) {
                $updated_rider_ID=$deliveryModel->update_rider_ID($postal_code[0]["delivery_ID"], $rider[0]["delivery_rider"]);
            } else {
                // if there is no such rider ten assign a rider from the queue
                $rider = $riderMOdel->select_queue_rider();

                if ($rider) {
                    // if rider is assigned from the queue
                    $updated_rider_ID=$deliveryModel->update_rider_ID($postal_code[0]["delivery_ID"], $rider[0]["delivery_rider_ID"]);
                    //dequeue a delivery rider - check something went wrong-> deleted few records at once
                    $deleted_rider = $riderMOdel->dequeue_rider($rider[0]["delivery_rider_ID"]);

                } else {
                    // if there were no rider available in the queue
                    // delivery is waiting until rider comes to the queue
                    $updated_order=$deliveryModel->set_delivery_without_rider($parameters[0]['id']);
                }
            }
        }

        // store total value in the order table - better to pass the total as a parameter
        $updated_total_order = $orderModel->write_total($parameters[0]['id'],$parameters[1]['total']);
        // set processing status from processing to packed
        $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'packed');
        // get packed orders
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
        // get packed orders
        $orders=$orderModel->get_packed_orders();
        
        return $this->render('pharmacy/pharmacy-orders-delivering',[
            'orders'=>$orders,
            'model'=>$orderModel,
        ]);
    }

    public function trackOrder(Request $request){
        $parameters=$request->getParameters();
        $orderModel=new Order();
        // order details
        $order_details = $orderModel->get_order_details($parameters[0]['id']);
        
        if ($request->isPost()){
            // var_dump($order_details);exit;
            
            if ( isset($_POST["picked-up"]) ) {
                if ( $order_details[0]['payment_status']=='pending' && isset($_POST['payment_status']) ) {
                    // $curr_order=$orderModel->getOrderByID($parameters[0]['id']);
                    
                    // set processing status from packed to pickedup
                    $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'pickedup');
                    
                    // update payment in order table
                    $updated_payment_order=$orderModel->update_payment_status($parameters[0]['id']);
                    
                    // update payment status in payment table
                    $paymentModel = new Payment();
                    $updated_payment_status=$paymentModel->update_payment_status($parameters[0]['id']);
                    
                    $this->setLayout("pharmacy",['select'=>'Previous Orders']);
                    // get previous orders - pickedup|deleted
                    $orders=$orderModel->get_previous_orders(); 
                    return $this->render('pharmacy/pharmacy-orders-previous',[
                        'orders'=>$orders,
                        'model'=>$orderModel,
                    ]);
                    
                } else {
                    // online medicine details
                    $online_orders = $orderModel->view_online_order_details($parameters[0]['id']);  
                    
                    // softcopy prescription details
                    $sf_orders = $orderModel->take_sf_orders($parameters[0]['id']);                 
                    $sf_pres_med = [];
                    foreach ($sf_orders as $key=>$sf_order){
                        $sf_pres_med[$sf_order['prescription_ID']] = $orderModel->view_prescription_details($sf_order['prescription_ID']);
                    }
                    // e-prescription details
                    $ep_orders = $orderModel->take_ep_orders($parameters[0]['id']);                 
                    $ep_pres_med = [];
                    foreach ($ep_orders as $key=>$ep_order){
                        $ep_pres_med[$ep_order['prescription_ID']] = $orderModel->view_prescription_details($ep_order['prescription_ID']);
                    }
                    
                    $this->setLayout("pharmacy",['select'=>'Orders']);
                    return $this->render('pharmacy/pharmacy-track-order',[
                        'order_details'=>$order_details,
                        'online_orders'=>$online_orders,
                        'sf_pres_med'=>$sf_pres_med,
                        'ep_pres_med'=>$ep_pres_med,
                        'ep_orders'=>$ep_orders,
                        'sf_orders'=>$sf_orders,
                        'model'=>$orderModel,
                        'err' => 'pending_payment'
                    ]);
                }

            } elseif ( isset($_POST["go-to-process"] )) {

                // set processing status from packed to processing
                $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'processing');

                // online medicine details
                $online_orders = $orderModel->view_online_order_details($parameters[0]['id']); 
                
                // softcopy prescription details
                $sf_orders = $orderModel->take_sf_orders($parameters[0]['id']);           
                $sf_pres_med = [];
                foreach ($sf_orders as $key=>$sf_order){
                    $sf_pres_med[$sf_order['prescription_ID']] = $orderModel->view_prescription_details($sf_order['prescription_ID']);
                }
                // e-prescription details
                $ep_orders = $orderModel->take_ep_orders($parameters[0]['id']);             
                $ep_pres_med = [];
                foreach ($ep_orders as $key=>$ep_order){
                    $ep_pres_med[$ep_order['prescription_ID']] = $orderModel->view_prescription_details($ep_order['prescription_ID']);
                }
                $this->setLayout("pharmacy",['select'=>'Orders']);
                return $this->render('pharmacy/pharmacy-view-processing-order',[
                    'order_details'=>$order_details,
                    'online_orders'=>$online_orders,
                    'sf_pres_med'=>$sf_pres_med,
                    'ep_pres_med'=>$ep_pres_med,
                    'ep_orders'=>$ep_orders,
                    'sf_orders'=>$sf_orders,
                    'model'=>$orderModel,
                ]);
            }

        }

        // online medicine details
        $online_orders = $orderModel->view_online_order_details($parameters[0]['id']);  
        
        // softcopy prescription details
        $sf_orders = $orderModel->take_sf_orders($parameters[0]['id']);                 
        $sf_pres_med = [];
        foreach ($sf_orders as $key=>$sf_order){
            $sf_pres_med[$sf_order['prescription_ID']] = $orderModel->view_prescription_details($sf_order['prescription_ID']);
        }
        // e-prescription details
        $ep_orders = $orderModel->take_ep_orders($parameters[0]['id']);                 
        $ep_pres_med = [];
        foreach ($ep_orders as $key=>$ep_order){
            $ep_pres_med[$ep_order['prescription_ID']] = $orderModel->view_prescription_details($ep_order['prescription_ID']);
        }

        $this->setLayout("pharmacy",['select'=>'Orders']);
        return $this->render('pharmacy/pharmacy-track-order',[
            'order_details'=>$order_details,
            'online_orders'=>$online_orders,
            'sf_pres_med'=>$sf_pres_med,
            'ep_pres_med'=>$ep_pres_med,
            'ep_orders'=>$ep_orders,
            'sf_orders'=>$sf_orders,
            'model'=>$orderModel,
        ]);
    }

    // public function processOrderAgain(Request $request){
    //     $parameters=$request->getParameters();
    //     $this->setLayout("pharmacy",['select'=>'Orders']);
    //     $orderModel=new Order();

    //     // set processing status from packed to processing
    //     $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'processing');
        
    //     // order details
    //     $order_details = $orderModel->get_order_details($parameters[0]['id']);

    //     // online medicine details
    //     $online_orders = $orderModel->view_online_order_details($parameters[0]['id']); 
        
    //     // softcopy prescription details
    //     $sf_orders = $orderModel->take_sf_orders($parameters[0]['id']);           
    //     $sf_pres_med = [];
    //     foreach ($sf_orders as $key=>$sf_order){
    //         $sf_pres_med[$sf_order['prescription_ID']] = $orderModel->view_prescription_details($sf_order['prescription_ID']);
    //     }
    //     // e-prescription details
    //     $ep_orders = $orderModel->take_ep_orders($parameters[0]['id']);             
    //     $ep_pres_med = [];
    //     foreach ($ep_orders as $key=>$ep_order){
    //         $ep_pres_med[$ep_order['prescription_ID']] = $orderModel->view_prescription_details($ep_order['prescription_ID']);
    //     }

    //     return $this->render('pharmacy/pharmacy-view-processing-order',[
    //         'order_details'=>$order_details,
    //         'online_orders'=>$online_orders,
    //         'sf_pres_med'=>$sf_pres_med,
    //         'ep_pres_med'=>$ep_pres_med,
    //         'ep_orders'=>$ep_orders,
    //         'sf_orders'=>$sf_orders,
    //         'model'=>$orderModel,
    //     ]);
    // }

    // public function pickupOrder(Request $request){
    //     // payment checkbox ------------------------------------------------------------>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    //     $orderModel=new Order();
    //     $curr_order=$orderModel->getOrderByID($parameters[0]['id']);
    //     var_dump($curr_order);exit;
    //     // set processing status from packed to pickedup
    //     $updated_order=$orderModel->set_processing_status($parameters[0]['id'],'pickedup');
    //     $this->setLayout("pharmacy",['select'=>'Previous Orders']);
    //     // get previous orders - pickedup|deleted
    //     $orders=$orderModel->get_previous_orders(); 
    //     return $this->render('pharmacy/pharmacy-orders-previous',[
    //         'orders'=>$orders,
    //         'model'=>$orderModel,
    //     ]);
    // }
    
    public function addNewOrderItem(Request $request) {
        $parameters=$request->getParameters();
        $pres_ID = 0;

        // take the prescription id
        foreach($_POST as $key=>$post_ele){
            if(substr($key,0,4)==='name') {
                $pres_ID = (int)substr($key,4);
            }
        }

        // get the medicine name array
        $medName = explode('-',$_POST['name'.$pres_ID]);
        $medicineModel = new Medicine();
        // get medicine id using the above array
        $med_ID = $medicineModel->getMedicineID($medName[0],$medName[1]);
        // get medicine details
        $med_details = $medicineModel->get_medicine_details($med_ID);
        
        // get order details
        $prescriptionModel = new Prescription();
        $pres_order_ID = $prescriptionModel->get_order_ID_by_pres_ID($pres_ID);

        // add medicine to the prescription
        if ( $med_details[0]["amount"]>=$_POST["amount"] ) { 
        // if available reduce stocks
        $reduce_med_amount = $medicineModel->reduceMedicine($med_ID, $_POST["amount"], true);
        $prescreption_medicine = $prescriptionModel->add_med_rec($med_ID, $parameters[0]['presid'], $_POST["amount"], $med_details[0]["unit_price"],'include');
        } else {
            $prescreption_medicine = $prescriptionModel->add_med_rec($med_ID, $parameters[0]['presid'], $_POST["amount"], $med_details[0]["unit_price"],'exclude');
        }

        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $medicine_array = $medicineModel->getAllMedicine();
        $order_details = $orderModel->get_order_details($pres_order_ID[0]["order_ID"]);

        // online medicine details
        $online_orders = $orderModel->view_online_order_details($pres_order_ID[0]["order_ID"]);  
        
        // softcopy prescription details
        $sf_orders = $orderModel->take_sf_orders($pres_order_ID[0]["order_ID"]);                 
        $sf_pres_med = [];
        foreach ($sf_orders as $key=>$sf_order){
            $sf_pres_med[$sf_order['prescription_ID']] = $orderModel->view_prescription_details($sf_order['prescription_ID']);
        }
        // e-prescription details
        $ep_orders = $orderModel->take_ep_orders($pres_order_ID[0]["order_ID"]);                 
        $ep_pres_med = [];
        foreach ($ep_orders as $key=>$ep_order){
            $ep_pres_med[$ep_order['prescription_ID']] = $orderModel->view_prescription_details($ep_order['prescription_ID']);
        }

        return $this->render('pharmacy/pharmacy-view-processing-order',[
            'order_details'=>$order_details,
            'online_orders'=>$online_orders,
            'sf_pres_med'=>$sf_pres_med,
            'ep_pres_med'=>$ep_pres_med,
            'ep_orders'=>$ep_orders,
            'sf_orders'=>$sf_orders,
            'model'=>$orderModel,
            'medicine_array'=>$medicine_array
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
        // get current date
        $curr_date = date("Y-m-d");
        // get current date into an array
        $date_arr = explode('-',$curr_date);
        $current_year = (int)$date_arr[0];
        $previous_year = (int)$date_arr[0]-1;

        // define an array to store the total income for past 12 months separately
        $monthly_income_from_orders = [0,0,0,0,0,0,0,0,0,0,0,0];
        // labels for months
        $month_labels = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];

        $orderModel=new Order();
        // get pickedup orders
        $orders=$orderModel->get_pickedup_orders(); 

        // calculating the incomes for past 12 months
        foreach ($orders as $order){
            if( (int)explode('-',$order["completed_date"])[1] < (int)$date_arr[1] && 
                (int)explode('-',$order["completed_date"])[0] === (int)$date_arr[0] ) {
                    $monthly_income_from_orders[ (int)explode('-',$order["completed_date"])[1]-1 ] += $order['total_price'] ; 
            } else if ((int)explode('-',$order["completed_date"])[1] >= (int)$date_arr[1] && 
                (int)explode('-',$order["completed_date"])[0] === (int)$date_arr[0]-1 ) {
                    $monthly_income_from_orders[ (int)explode('-',$order["completed_date"])[1]-1 ] += $order['total_price'] ; 
            }          
        }

        // arrange the months into a order
        $position = 0;
        $arranged_monthly_income_from_orders = [];
        for ( $i=(int)$date_arr[1]; $i<=12; $i++ ) {
            $arranged_monthly_income_from_orders[$position][0]=$previous_year;
            $arranged_monthly_income_from_orders[$position][1]=$month_labels[$i];
            $arranged_monthly_income_from_orders[$position][2]=$monthly_income_from_orders[$i-1];
            $position++;
        }
        for ( $i=1; $i<(int)$date_arr[1]; $i++ ) {
            $arranged_monthly_income_from_orders[$position][0]=$current_year;
            $arranged_monthly_income_from_orders[$position][1]=$month_labels[$i];
            $arranged_monthly_income_from_orders[$position][2]=$monthly_income_from_orders[$i-1];
            $position++;
        }

        return $this->render('pharmacy/pharmacy-view-report',[
            'medicine_income'=>$arranged_monthly_income_from_orders
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
    public function editPersonalDetails(Request $request,Response $response){  
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

                if($employeeModel->validate() && $employeeModel->updateRecord(['emp_ID'=>$parameters[1]['id']])){
                    $response->redirect('/ctest/pharmacy-view-advertisement'); 
                    Application::$app->session->setFlash('success',"User Profile Updated Successfully.");
                    Application::$app->response->redirect('/ctest/pharmacy-view-personal-details');
                    exit; 
                };
                var_dump($employeeModel);
                exit;
            } 
        }
        return $this->render('pharmacy/pharmacy-view-personal-details',[
            'model'=>$employeeModel,
        ]);
    }

}