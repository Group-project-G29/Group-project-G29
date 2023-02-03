<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\User;
use app\core\DbModel;
use app\core\Response;
use app\models\Medicine;

class PharmacyController extends Controller{
    //delete update insert  medicine
    public function handleMedicine(Request $request,Response $response){
        $parameters=$request->getParameters();
        $this->setLayout('pharmacy',['select'=>'Medicines']);
        $medicineModel=new Medicine();
        //Delete operation
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='delete'){
            $medicineModel->deleteRecord(['med_ID'=>$parameters[1]['id']]);
            Application::$app->session->setFlash('success',"Medicine successfully deleted ");
            $response->redirect('/ctest/pharmacist');
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
                    Application::$app->response->redirect('/ctest/pharmacist');
                    exit; 
                 };
                
            } 
        
            
            
            if($medicineModel->validate() && $medicineModel->addMedicine()){
               Application::$app->session->setFlash('success',"Medicine successfully added ");
               Application::$app->response->redirect('/ctest/pharmacist'); 
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

}