<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
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

class DeliveryController extends Controller{
    

    //view my deliveries
    public function viewMyDeliveries(){
<<<<<<< HEAD
        $this->setLayout("delivery-rider",['select'=>'Pending Deliveries']);
=======
        $this->setLayout("delivery-rider",['select'=>'My Deliveries']);
>>>>>>> 20000758
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
<<<<<<< HEAD
        $this->setLayout("delivery-rider",['select'=>'Pending Deliveries']);
=======
        $this->setLayout("delivery-rider",['select'=>'My Deliveries']);
>>>>>>> 20000758
        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery WHERE delivery_ID = ".$parameters[0]['id']); //pass id
        return $this->render('delivery/delivery-view-delivery' ,[
            'delivery'=>$delivery[0],
            'model'=>$deliveryModel
        ]);
    }

    //view all deliveries
    public function viewAllDeliveries(){
<<<<<<< HEAD
        $this->setLayout("delivery-rider",['select'=>'Completed Deliveries']);
=======
        $this->setLayout("delivery-rider",['select'=>'All Deliveries']);
>>>>>>> 20000758
        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery WHERE completed_time IS NOT NULL AND delivery_rider = ".'"'.Application::$app->session->get('userObject')->emp_ID.'"');
        return $this->render('delivery/delivery-all-deliveries' ,[
            'deliveries'=>$delivery,
            'model'=>$deliveryModel
        ]);
    }

    //complete delivery using the PIN
    public function completeDelivery(){
        $deliveryModel=new Delivery();
<<<<<<< HEAD
        $this->setLayout("delivery-rider",['select'=>'Pending Deliveries']);
=======
        $this->setLayout("delivery-rider",['select'=>'My Deliveries']);
>>>>>>> 20000758
        $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery WHERE completed_time IS NOT NULL AND delivery_rider = ".'"'.Application::$app->session->get('userObject')->emp_ID.'"');
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