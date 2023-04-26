<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\User;
use app\core\DbModel;
use app\core\Response;
use app\models\Employee;
use app\models\EmployeeLoginForm;

class EmployeeAuthController extends Controller{
    public function login(Request $request,Response $response){
      
        $this->setLayout('auth');
        $EmployeeLoginForm=new EmployeeLoginForm();


        if($request->isPost()){
            $EmployeeLoginForm->loadData($request->getBody());
            //login is called only if validate is true
            if($EmployeeLoginForm->validate() && $EmployeeLoginForm->login()){
                $Employee=new Employee();
                $user=$Employee->findOne(['email'=>$EmployeeLoginForm->username]);
                
                Application::$app->session->setFlash('success',"Welcome ".$EmployeeLoginForm->username);
                if($user->role=="receptionist"){
                    $response->redirect('/ctest/receptionist-handle-patient?mod=view');
                }
                else if($user->role=='lab'){
                    $response->redirect('/ctest/lab-view-all-test');
                } 
                else if($user->role=='delivery'){
                    $response->redirect('/ctest/delivery-my-deliveries');
                }
                else{
                    $response->redirect('/ctest/'.$user->role);
                }
                
                return true;
            }
        }
        return $this->render('login',[
            'model'=>$EmployeeLoginForm
        ]);
       
       
        
    }

   

    public function logout(Request $request,Response $response){
        $registerModel=new Employee();
        if($registerModel->logout()){
            Application::$app->session->setFlash('success',"You are logged out");
            Application::$app->response->redirect("/ctest/login");
            exit;
        }
    }

}