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
        // $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery WHERE delivered_flag = 'N' AND delivery_rider = ".'"'.Application::$app->session->get('userObject')->emp_ID.'"');
        $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery WHERE completed_time IS NULL AND delivery_rider = ".'"'.Application::$app->session->get('userObject')->emp_ID.'"');
        // var_dump($delivery);
        // exit;
        return $this->render('delivery/delivery-my-deliveries' ,[
            'deliveries'=>$delivery,
            'model'=>$deliveryModel
        ]);
    }

    //view delivery details
    public function viewDeliveryDetails(Request $request,Response $response){
        $parameters=$request->getParameters();
        $this->setLayout("delivery-rider",['select'=>'Pending Deliveries']);
        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery INNER JOIN _order ON delivery.delivery_ID = _order.delivery_ID WHERE delivery.delivery_ID = ".$parameters[0]['id']); //pass id
        return $this->render('delivery/delivery-view-delivery' ,[
            'delivery'=>$delivery[0],
            'model'=>$deliveryModel
        ]);
    }

    //view all deliveries
    public function viewAllDeliveries(){
        $this->setLayout("delivery-rider",['select'=>'Completed Deliveries']);
        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery WHERE completed_time IS NOT NULL AND delivery_rider = ".'"'.Application::$app->session->get('userObject')->emp_ID.'"');
        return $this->render('delivery/delivery-all-deliveries' ,[
            'deliveries'=>$delivery,
            'model'=>$deliveryModel
        ]);
    }

    //complete delivery using the PIN
    public function completeDelivery(Request $request, Response $response){
        // var_dump("sdgdsf");
        $parameters=$request->getParameters();
        $this->setLayout("delivery-rider",['select'=>'Pending Deliveries']);
        // var_dump($parameters);
        // var_dump($deliveryModel);
        
        $deliveryModel=new Delivery();
        $deliveryModel->loadData($request->getBody());
        
        $confirming_delivery = $deliveryModel->customFetchAll("SELECT * FROM delivery WHERE delivery_ID=".$parameters[0]['id']);
        
        // var_dump($confirming_delivery);
        // exit;

        if ( $confirming_delivery[0]['PIN'] === $parameters[1]['pin'] ) {
            echo "correct";

            $update_status = $deliveryModel->customFetchAll("UPDATE delivery SET completed_date = CURRENT_TIMESTAMP, completed_time = CURRENT_TIMESTAMP  WHERE delivery_ID=".$parameters[0]['id']);

            $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery WHERE completed_time IS NULL AND delivery_rider = ".'"'.Application::$app->session->get('userObject')->emp_ID.'"');
            return $this->render('delivery/delivery-my-deliveries' ,[
                'deliveries'=>$delivery,
                'model'=>$deliveryModel
            ]);

        } else {
            echo "incorrect";
            
            //error msg

            $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery INNER JOIN _order ON delivery.delivery_ID = _order.delivery_ID WHERE delivery.delivery_ID = ".$parameters[0]['id']); 
            return $this->render('delivery/delivery-view-delivery' ,[
                'delivery'=>$delivery[0],
                'model'=>$deliveryModel
            ]);
        }
        

        $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery WHERE completed_time IS NULL AND delivery_rider = ".'"'.Application::$app->session->get('userObject')->emp_ID.'"');
        return $this->render('delivery/delivery-all-deliveries' ,[
            'deliveries'=>$delivery,
            'model'=>$deliveryModel
        ]);
    }

    //View My Details
    public function viewPersonalDetails(){
        $this->setLayout("delivery-rider",['select'=>'My Detail']);
        $userModel = new Employee();
        // $user = $userModel->findOne(106);
        $user = $userModel->customFetchAll("SELECT * FROM employee WHERE email=".'"'.Application::$app->session->get('user').'"');
        return $this->render('delivery/delivery-view-personal-details' ,[
            'user' => $user[0]
        ]);
    }

}