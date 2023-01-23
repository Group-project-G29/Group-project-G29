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
        $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery WHERE delivered_flag = 'N' AND delivery_rider = ".'"'.Application::$app->session->get('userObject')->emp_ID.'"');
        return $this->render('delivery/delivery-my-deliveries' ,[
            'deliveries'=>$delivery,
            'model'=>$deliveryModel
        ]);
    }

    //view all deliveries
    public function viewAllDeliveries(){
        $this->setLayout("delivery-rider",['select'=>'All Deliveries']);
        $deliveryModel=new Delivery();
        $delivery=$deliveryModel->customFetchAll("SELECT * FROM delivery WHERE delivered_flag = 'Y' AND delivery_rider = ".'"'.Application::$app->session->get('userObject')->emp_ID.'"');
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