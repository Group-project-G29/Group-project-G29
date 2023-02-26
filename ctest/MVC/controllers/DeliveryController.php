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
use LogicException;

class DeliveryController extends Controller{
    

    //view my deliveries
    public function viewMyDeliveries(){
        $this->setLayout("delivery-rider",['select'=>'Pending Deliveries']);
        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->get_unfinished_deliveries(Application::$app->session->get('userObject')->emp_ID);
        return $this->render('delivery/delivery-my-deliveries' ,[
            'deliveries'=>$delivery,
            'model'=>$deliveryModel
        ]);
    }
    
    //view details of a selected delivery
    public function viewDeliveryDetails(Request $request,Response $response){
        $parameters=$request->getParameters();
        $this->setLayout("delivery-rider",['select'=>'Pending Deliveries']);
        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->view_delivery_details($parameters[0]['id']); //pass id
        return $this->render('delivery/delivery-view-delivery' ,[
            'delivery'=>$delivery[0],
            'model'=>$deliveryModel
        ]);
    }
    
    public function passDelivery(Request $request){
        $parameters=$request->getParameters();
        var_dump($parameters);

        $this->setLayout("delivery-rider",['select'=>'Pending Deliveries']);
        
        //make offline the user
        $userModel = new Employee();
        $riderMOdel = new Employee;

        // pop up message can be shown to select whether make offline or stay online
        $user=$userModel->make_rider_offline(Application::$app->session->get('userObject')->emp_ID);
        
        //pass using pharmacy functions
        $orderModel=new Order();
        
        //selected the available rider 
        $postal_code = $orderModel->get_postal_code($parameters[0]['id']);
        $rider = $userModel->select_suitable_rider($postal_code[0]["postal_code"], $postal_code[0]["order_ID"]);
        
        $deliveryModel = new Delivery;
        if($rider) {
            $updated_rider_ID=$deliveryModel->update_rider_ID($postal_code[0]["delivery_ID"], $rider[0]["delivery_rider"]);

        } else {
            $rider = $riderMOdel->select_queue_rider();
            $updated_rider_ID=$deliveryModel->update_rider_ID($postal_code[0]["delivery_ID"], $rider[0]["delivery_rider_ID"]);
            //dequeue a delivery rider - check something went wrong-> deleted few records at once
            $deleted_rider = $riderMOdel->dequeue_rider($rider[0]["delivery_rider_ID"]);
        }

        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->get_unfinished_deliveries(Application::$app->session->get('userObject')->emp_ID);
        return $this->render('delivery/delivery-my-deliveries' ,[
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
        $this->setLayout("delivery-rider",['select'=>'Pending Deliveries']);
        
        $deliveryModel=new Delivery();
        $riderMOdel = new Employee;

        $deliveryModel->loadData($request->getBody());
        $confirming_delivery = $deliveryModel->get_processing_delivery($parameters[0]['id']);

        if ( $confirming_delivery[0]['PIN'] === $_POST['confirmation_PIN'] ) {

            $update_status = $deliveryModel->update_completed_date_time($parameters[0]['id']);
            $delivery=$deliveryModel->get_finished_deliveries(Application::$app->session->get('userObject')->emp_ID);

            //enque the rider to the queue if there is no deliveries to him
            if ( !$delivery ) {
                $rider = $riderMOdel->enqueue_rider(Application::$app->session->get('userObject')->emp_ID);
            }

            return $this->render('delivery/delivery-my-deliveries' ,[
                'deliveries'=>$delivery,
                'model'=>$deliveryModel
            ]);

        } else {
            //error msg
            //this part has to be implemented

            $delivery=$deliveryModel->view_delivery_details($parameters[0]['id']); 
            return $this->render('delivery/delivery-view-delivery' ,[
                'delivery'=>$delivery[0],
                'model'=>$deliveryModel
            ]);
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

}