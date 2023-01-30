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
        $this->setLayout("delivery-rider",['select'=>'My Deliveries']);
        $deliveryModel=new Delivery();
        // $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery WHERE delivered_flag = 'N' AND delivery_rider = ".'"'.Application::$app->session->get('userObject')->emp_ID.'"');
        $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery WHERE completed_time = 'NULL' AND delivery_rider = ".'"'.Application::$app->session->get('userObject')->emp_ID.'"');
        var_dump($delivery);
        exit;
        return $this->render('delivery/delivery-my-deliveries' ,[
            'deliveries'=>$delivery,
            'model'=>$deliveryModel
        ]);
    }

    //view my deliveries
    public function viewDeliveryDetails(Request $request,Response $response){
        $parameters=$request->getParameters();
        // var_dump($parameters);
        // exit;
        $this->setLayout("delivery-rider",['select'=>'My Deliveries']);
        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery WHERE delivery_ID = 100"); //pass id
        return $this->render('delivery/delivery-view-delivery' ,[
            'delivery'=>$delivery[0],
            'model'=>$deliveryModel
        ]);
    }

    //view all deliveries
    public function viewAllDeliveries(){
        $this->setLayout("delivery-rider",['select'=>'All Deliveries']);
        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery WHERE completed_time != 'NULL' AND delivery_rider = ".'"'.Application::$app->session->get('userObject')->emp_ID.'"');
        return $this->render('delivery/delivery-all-deliveries' ,[
            'deliveries'=>$delivery,
            'model'=>$deliveryModel
        ]);
        // $advertisementModel=new PharmacyAdvertisement();
        // $advertisements=$advertisementModel->customFetchAll("Select * from advertisement order by title asc");
        // return $this->render('delivery/delivery-all-deliveries');
        // ,[
        //     'advertisements'=>$advertisements,
        //     'model'=>$advertisementModel
        // ]);
    }

    public function completeDelivery(){
        $this->setLayout("delivery-rider",['select'=>'My Deliveries']);
        return $this->render('delivery/delivery-complete',[
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