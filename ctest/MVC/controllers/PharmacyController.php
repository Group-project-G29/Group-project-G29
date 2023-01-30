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
use app\models\Order;
use app\models\PharmacyAdvertisement;

class PharmacyController extends Controller{
 
//==============================Orders===========================================

    //View Order
    public function viewPendingOrder(){
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $orders=$orderModel->customFetchAll("Select * from _order where processing_status = 'pending' order by time_of_creation asc");
        // $orders=$orderModel->customFetchAll("Select * from medicine_in_order inner join medical_products on medicine_in_order.med_ID=medical_products.med_ID where medicine_in_order.order_ID = 1"); 
        // $orders=$orderModel->customFetchAll("Select * from _order inner join medicine_in_order on _order.cart_ID=medicine_in_order.cart_ID inner join cart on medicine_in_order.cart_ID=cart.cart_ID inner join patient on cart.patient_ID=patient.patient_ID where processing_status = 'pending' order by _order.time_of_creation asc");
        // $orders=$orderModel->customFetchAll("
        //     SELECT * FROM medicine_in_order 
        //     INNER JOIN _order ON medicine_in_order.order_ID=_order.order_ID
        //     INNER JOIN cart ON _order.cart_ID  = cart.cart_ID
        //     INNER JOIN patient ON cart.patient_ID = patient.patient_ID;
        // ");
        // var_dump($orders);
        // exit;
        return $this->render('pharmacy/pharmacy-orders-pending',[
            'orders'=>$orders,
            'model'=>$orderModel
        ]);
    }

    public function DetailsPendingOrder(){
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $orders=$orderModel->customFetchAll("Select * from medical_products inner join medicine_in_order on medicine_in_order.med_ID=medical_products.med_ID where medicine_in_order.order_ID = 1"); 
        return $this->render('pharmacy/pharmacy-view-pending-order',[
            'orders'=>$orders,
            'model'=>$orderModel
        ]);
    }

    public function cancleProcessOrder(){
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();

        //write the sql query to remove the process from table  

        $orders=$orderModel->customFetchAll("Select * from _order where processing_status = 'processing' order by time_of_creation asc");
        return $this->render('pharmacy/pharmacy-orders-processing',[
            'orders'=>$orders,
            'model'=>$orderModel
        ]);
    }

    public function trackOrder(){
        $this->setLayout("pharmacy",['select'=>'Orders']);
        return $this->render('pharmacy/pharmacy-track-order',[
        ]);
    }

    public function viewProcessingOrder(){
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $orders=$orderModel->customFetchAll("Select * from _order where processing_status = 'processing' order by time_of_creation asc");
        return $this->render('pharmacy/pharmacy-orders-processing',[
            'orders'=>$orders,
            'model'=>$orderModel
        ]);
    }
    public function viewDeliveringOrder(){
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        $orders=$orderModel->customFetchAll("Select * from _order where processing_status = 'packed' order by time_of_creation asc");
        return $this->render('pharmacy/pharmacy-orders-delivering',[
            'orders'=>$orders,
            'model'=>$orderModel
        ]);
    }

    public function createNewOrder(Request $request,Response $response){
        // $parameters=$request->getParameters();
        $this->setLayout("pharmacy",['select'=>'Orders']);
        $orderModel=new Order();
        // echo "dfasd";
        // exit;

        if($request->isPost()){
        //     echo "jkhygadf";
        // var_dump($request);

        //     exit;
            // update medicine
            // $orderModel->loadData($request->getBody());
            // $orderModel->loadFiles($_FILES);
            // if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='update'){
            //     if($orderModel->validate() && $orderModel->updateRecord(['med_ID'=>$parameters[1]['id']])){
            //         $response->redirect('/ctest/view-medicine'); 
            //         Application::$app->session->setFlash('success',"Medicine successfully updated ");
            //         Application::$app->response->redirect('/ctest/pharmacy-view-medicine');
            //         exit; 
            //      };
            // } 
            
            if($orderModel->validate() && $orderModel->addOrder()){
               Application::$app->session->setFlash('success',"Order successfully added ");
               Application::$app->response->redirect('pharmacy/pharmacy-orders-pending'); 
               $this->setLayout("pharmacy",['select'=>'Orders']);
            //    $orderModel=new Order();
               $orders=$orderModel->customFetchAll("Select * from medical_products order by name asc");
               return $this->render('pharmacy/pharmacy-orders-pending',[
                   'orders'=>$orders,
                   'model'=>$orderModel
               ]);
            }
        }

        // echo "here";
        // exit;
        return $this->render('pharmacy/pharmacy-new-order',[
            'model'=>$orderModel
        ]);
    }


//==============================Medicines===========================================    

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
            $medicine=$medicineModel->customFetchAll("Select * from medical_products where med_ID=".$parameters[1]['id']);
            $medicineModel->updateData($medicine,$medicineModel->fileDestination());
            Application::$app->session->set('medicine',$parameters[1]['id']);
            return $this->render('pharmacy/pharmacy-update-medicine',[
                'model'=>$medicineModel,
            ]);
        }

        if($request->isPost()){
            // update medicine
            $medicineModel->loadData($request->getBody());
            $medicineModel->loadFiles($_FILES);
            if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='update'){
                if($medicineModel->validate() && $medicineModel->updateRecord(['med_ID'=>$parameters[1]['id']])){
                    $response->redirect('/ctest/view-medicine'); 
                    Application::$app->session->setFlash('success',"Medicine successfully updated ");
                    Application::$app->response->redirect('/ctest/pharmacy-view-medicine');
                    exit; 
                 };
            } 
            
            if($medicineModel->validate() && $medicineModel->addMedicine()){
               Application::$app->session->setFlash('success',"Medicine successfully added ");
               Application::$app->response->redirect('/ctest/pharmacy-view-medicine'); 
               $this->setLayout("pharmacy",['select'=>'Medicines']);
               $medicineModel=new Medicine();
               $medicines=$medicineModel->customFetchAll("Select * from medical_products order by name asc");
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
        $medicines=$medicineModel->customFetchAll("Select * from medical_products order by name asc");
        return $this->render('pharmacy/pharmacy-view-medicine',[
            'medicines'=>$medicines,
            'model'=>$medicineModel
        ]);

    }


//==============================Advertisements===========================================

    //delete update insert advertisement
    public function handleAdvertisement(Request $request,Response $response){
        $parameters=$request->getParameters();
        $this->setLayout('pharmacy',['select'=>'Advertisement']);
        $advertisementModel=new PharmacyAdvertisement();

        //Delete operation
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='delete'){
            $advertisementModel->deleteRecord(['ad_ID'=>$parameters[1]['id']]);
            Application::$app->session->setFlash('success',"Advertisement successfully deleted ");
            $response->redirect('/ctest/pharmacy-view-advertisement');
            return true;
        }

        //Go to update page of a advertisement
        if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
            $advertisement=$advertisementModel->customFetchAll("Select * from advertisement where ad_ID=".$parameters[1]['id']);
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
                    
                
                if($advertisementModel->validate() && $advertisementModel->updateRecord(['ad_ID'=>$parameters[1]['id']])){
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
               $advertisements=$advertisementModel->customFetchAll("Select * from advertisement order by name asc");
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
        $this->setLayout("pharmacy",['select'=>'PharmacyAdvertisement']);
        $advertisementModel=new PharmacyAdvertisement();
        $advertisements=$advertisementModel->customFetchAll("Select * from advertisement order by title asc");
        return $this->render('pharmacy/pharmacy-view-advertisement',[
            'advertisements'=>$advertisements,
            'model'=>$advertisementModel
        ]);
    }


//==============================Reports===========================================    

    //View Reports
    public function viewReports(){
        $this->setLayout("pharmacy",['select'=>'Reports']);
        // $userModel = new Employee();
        // $user = $userModel->findOne(106);
        // $user = $userModel->customFetchAll("SELECT * FROM employee WHERE email=".'"'.Application::$app->session->get('user').'"');
        return $this->render('pharmacy/pharmacy-view-report',[
            // 'user' => $user[0]
        ]);
    }


//==============================My Details===========================================    

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
        // echo 'afdsaf';
        // exit;

        if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
            // echo "afkjsahdfkjsahfkjsahbdkj";
            $employee=$employeeModel->customFetchAll("Select * from employee where emp_ID=".$parameters[1]['id']);
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
            // var_dump($employeeModel);
            // exit;
            if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='update'){
                var_dump($parameters);
                exit;
                if($employeeModel->updateRecord(['emp_ID'=>$parameters[1]['id']])){
                    // echo 'def';
                    // exit;
                    $response->redirect('/ctest/pharmacy-view-personal-details'); 
                    Application::$app->session->setFlash('success',"Account successfully updated ");
                    Application::$app->response->redirect('/ctest/pharmacy-view-personal-details');
                    exit; 
                 };
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