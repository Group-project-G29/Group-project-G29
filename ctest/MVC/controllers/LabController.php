<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\User;
use app\core\DbModel;
use app\core\Response;
use app\models\LabTest;
use app\models\Medicine;
use app\models\Employee;


class LabController extends Controller
{
    //delete update insert  medicine
    public function handleTest(Request $request,Response $response){
        $parameters=$request->getParameters();
        // $this->setLayout('pharmacy');
        $LabTestModel=new Labtest();
        //Delete operation
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='delete'){
            $LabTestModel->deleteRecord(['name'=>$parameters[1]['id']]);
            Application::$app->session->setFlash('success',"Lab Test successfully deleted ");
            $response->redirect('/ctest/lab-view-all-test');
            return true;
        }
        //Go to update page of a medicine
        if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
            $labtest=$LabTestModel->customFetchAll("Select * from lab_tests where name="."'".$parameters[1]['id']."'");

            $LabTestModel->updateData($labtest,$LabTestModel->fileDestination());
            Application::$app->session->set('labtest',$parameters[1]['id']);
            return $this->render('lab/lab-test-update',[
                'model'=>$LabTestModel,
                'labtest'=>$labtest[0]
            ]);
        }
        if($request->isPost()){
            
            // update medicine
            $LabTestModel->loadData($request->getBody());
            $LabTestModel->loadFiles($_FILES);
            if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='update'){
                // echo 'hh';
                // exit;
                if($LabTestModel->validate() && $LabTestModel->updateRecord(['name'=>$parameters[1]['id']])){
                    $response->redirect('/ctest/lab-view-all-test'); 
                    Application::$app->session->setFlash('success',"lab test successfully updated ");
                    $response->redirect('/ctest/lab-view-all-test');
                    exit;
                 };
                
            } 
        
            if($LabTestModel->validate() && $LabTestModel->addTest()){
               Application::$app->session->setFlash('success',"Lab Test successfully added ");
               Application::$app->response->redirect('/ctest/lab'); 
               $labtest=$LabTestModel->customFetchAll("Select * from lab_tests ");
               return $this->render('lab/lab-view-all-test',[
                'model'=>$LabTestModel,
                'labtest'=>$labtest
            ]);
            };
        }
        return $this->render('lab/lab-add-new-test', [
            'model' => $LabTestModel,
        ]);
    }
    //view test
    public function viewTest()
    {
        $this->setLayout("lab", ['select' => 'Tests']);
        $labTestModel = new LabTest();
        $tests = $labTestModel->customFetchAll("SELECT * FROM lab_tests");
        return $this->render('lab/lab-view-all-test', [
            'tests' => $tests
        ]);
    }

            
                
           
     
    public function testRequest(){
        $this->setLayout("lab", ['select' => 'Requests']);
        $labTestModel=new LabTest();
        $tests=$labTestModel->customFetchAll("SELECT lab_request.test_name as test_name ,lab_request.requested_date_time , patient.name as patient_name,employee.name as doc_name from lab_request join employee on employee.nic=lab_request.doctor join patient on patient.patient_ID=lab_request.patient_ID");
        return $this->render('lab/lab-test-request',[
            'tests'=>$tests
        ]);
      
    }
    public function reportUpload(){
        $labTestModel=new LabTest();
        $tests=$labTestModel->customFetchAll("SELECT lab_request.requested_date_time , patient.name as patient_name,employee.name as doc_name from lab_request join employee on employee.nic=lab_request.doctor join patient on patient.patient_ID=lab_request.patient_ID");
        return $this->render('lab/lab-report-upload',[
            'tests'=>$tests[0]
        ]);
      
    }

    



    public function viewPersonalDetails(){
        $this->setLayout("lab", ['select' => 'My Detail']);
        $userModel = new Employee();
        $user = $userModel->customFetchAll("SELECT * FROM employee WHERE email=" . '"' . Application::$app->session->get('user') . '"');
        return $this->render('lab/lab-view-personal-details', [
            'user' => $user[0]
        ]);

    }

    public function writeReport()
    {
        $this->setLayout("lab", ['select' => 'Requests']);
        // $userModel = new Employee();
        // $user = $userModel->customFetchAll("SELECT * FROM employee WHERE email=" . '"' . Application::$app->session->get('user') . '"');
        return $this->render('lab/lab-write-test-report', [
            // 'user' => $user[0]
        ]);
    }
}
