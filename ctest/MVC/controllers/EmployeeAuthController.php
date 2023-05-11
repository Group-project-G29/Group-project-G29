<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\User;
use app\core\DbModel;
use app\core\Email;
use app\core\Response;
use app\models\Employee;
use app\models\EmployeeLoginForm;
use app\models\OTP;
use app\models\Patient;

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



    public function getNIC(Request $request,Response $response){
            $this->setLayout('auth');
            $emailModel=new Email();
            $employee=new Employee();
            if($request->isPost()){
                
                $employee->loadData($request->getBody());
                $pat=$employee->fetchAssocAll(['email'=>$employee->email,'employee_status'=>'active']);
            
                if(!$pat){
                    $employee->customAddError('nic',"No Account Exists with this NIC number");
                    return $this->render('get-nic',[
                        'patient'=>$employee
                    ]);
                }
                else{
                    $pat=$pat[0];
                    $OTP=new OTP();
                    Application::$app->session->set('temp_user',$pat['emp_ID']);
                    $p=$employee->findOne(['emp_ID'=>$pat['emp_ID']]);
                    if($OTP->canSend($p->emp_ID,'employee')){
                        $rint=$OTP->canSend($p->emp_ID,'employee');
                    }
                    else{
                        $rint=$OTP->setOTP($p->emp_ID,'employee');
                    }
                    $emailModel->sendOTP($rint,$p->email);
                    return $this->render('sent-email');
                }
            }
            return $this->render('get-nic',[
                'patient'=>$employee
            ]);
        }
        public function OTP(Request $request,Response $response){
            $patient=Application::$app->session->get('temp_user');
            $parameters=$request->getParameters();
            $this->setLayout('auth');
            $OTP=new OTP();
            if($OTP->canSend($patient)){
                if($OTP->canSend($patient)[0]['OTP']==$parameters[0]['sec']){
                    if($request->isPost()){
                        $result=$OTP->changePassword($response,'employee');
                    
                        return $this->render('change-password',[
                            'model'=>$result
                        ]);
                    }
                
                    return $this->render('change-password',[
                        'model'=>new patient()
                    ]);
                }
            }
            else{
                exit;
            }


        }
}