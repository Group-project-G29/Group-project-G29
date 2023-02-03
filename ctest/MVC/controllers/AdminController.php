<?php
namespace app\controllers;

use app\core\Application;
use app\core\Calendar;
use app\core\Controller;
use app\core\Date;
use app\core\Request;
use app\core\Response;
use app\models\Channeling;
use app\models\Advertisement;

use app\models\Employee;
use app\models\NurseAllocation;
use app\models\OpenedChanneling;
use app\core\Time;

class AdminController extends Controller{
    // create add,view channeling session
    public function schedulingChanneling(Request $request){
        $this->setLayout('admin',['select'=>"Channelings Sessions"]);
        $ChannelingModel=new Channeling();
        $Employee=new Employee();
        $Doctors=$Employee->customFetchAll("select name,nic from employee where role='doctor'");
        $Nurses=$Employee->customFetchAll("select * from employee where role='nurse'");
        $Rooms=$Employee->customFetchAll("select * from room");
        $Doctor=[];
        $Room=[];
        $parameters=$request->getParameters();
        $nurseAllocationModel=new NurseAllocation();
        $timeModel=new Time();

        foreach($Rooms as $row){
            $Room[$row['name']]=$row['name'];
        }

        foreach($Doctors as $row){
            $Doctor[$row['name']]=$row['nic'];
        }
       

        //haandle post request from channeling scheduling form
        if($request->isPost()){
            
            $ChannelingModel->loadData($request->getBody());
            $result=$ChannelingModel->checkOverlap();
            if($result[0]){ 
                $ChannelingModel->customAddError('time',"Time overlap with ".$result[1]." channeling session"."<a href='#'>See Channeling Timetable</a>");
            }
            if($ChannelingModel->validate() ){
                //save data in database
                $return_id=$ChannelingModel->savedata();
                if($return_id){
                    $nurseAllocationModel->loadData($request->getBody());
                    $tempnurseAllocationModel=$nurseAllocationModel;
                    $success=false;
                    foreach($tempnurseAllocationModel->emp_ID as $nurse ){
                        $nurseAllocationModel->emp_ID=$nurse;
                        $nurseAllocationModel->channeling_ID=$return_id[0]['last_insert_id()'];
                        if($nurseAllocationModel->validate() && $nurseAllocationModel->savedata()){
                            $success=true;
                           
                        }
                    }

                    if($success){
                        $openedChannelingModel=new OpenedChanneling();
                        $calendarModel=new Calendar();
                        $date=$calendarModel->findDateByDay($ChannelingModel->start_date, date('l', strtotime($ChannelingModel->start_date)),$ChannelingModel->day);
                        $dateModel=new Date();
                        $date=$dateModel->arrayToDate($date);
                        //decide whether to close or opened
                        $openedChannelingModel->setter($return_id[0]['last_insert_id()'],$ChannelingModel->max_free_appointments,0,0,$date,"Opened"); 
                        if($openedChannelingModel->saveData()){
                            Application::$app->session->setFlash('success',"Channeling Session Added Successfully");
                            Application::$app->response->redirect("/ctest/schedule-channeling");
                            
                        }
                    }
                }
                
            }
        }

        if(isSet($parameters[0]['mod']) && $parameters[0]['mod']=='add'){
            $this->setLayout('admin',['select'=>"Schedule Channelings"]);
            return $this->render('administrator/schedule-channeling',[
                'employeemodel'=>$Employee,
                'channelingmodel'=>$ChannelingModel,
                'doctors'=>$Doctor,
                'nurses'=>$Nurses,
                'rooms'=>$Room,
                'test'=>$ChannelingModel->errors

            ]);
        }
         //view channeling 
        
        $channelings=$ChannelingModel->customFetchAll("Select * from channeling left join doctor on doctor.nic=channeling.doctor left join employee on employee.nic=doctor.nic");
        return $this->render('administrator/view-channeling',[
                'channelings'=>$channelings
        ]);
        
       
    }

    public function registerAccounts(Request $request,Response $response){
        $parameters=$request->getParameters();
        $openedChannelingModel=new OpenedChanneling();
        $registerModel=new Employee();
        $this->setLayout('admin',['select'=>"Manage Users"]);
        if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='add'){
            if($request->isPost()){
                $registerModel->loadData($request->getBody());
                $registerModel->loadFiles($_FILES);
                 if($registerModel->validate() && $registerModel->register()){
                    Application::$app->session->setFlash('success',"Thanks for registering");
                    Application::$app->response->redirect('/ctest/admin');
                    exit;
                }
    
            }
            return $this->render('administrator/employee-registration',[
                'model'=>$registerModel,
                
            ]);
        }
        else if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='delete'){
            $registerModel->deleteRecord(['emp_ID'=>$parameters[1]['id']]);
            Application::$app->session->setFlash('success',"Account successfully deleted ");
            $response->redirect('/ctest/admin');
            return true;
        }
       

         else if($request->isPost()){

           
            $registerModel->loadData($request->getBody());
            $registerModel->loadFiles($_FILES);
            if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
                    
                
                if( $registerModel->updateRecord(['emp_ID'=>$parameters[1]['id']])){
                    $response->redirect('/ctest/admin'); 
                    Application::$app->session->setFlash('success',"Account successfully updated ");
                    Application::$app->response->redirect('/ctest/admin');
                    exit; 
                 };
                
            } 
            
        
            return $this->render('administrator/employee-registration',[
                'model'=>$registerModel,
                'select'=>"Manage Users"
            ]);
        }
        else if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
            $employee=$registerModel->customFetchAll("Select * from employee where emp_ID=".$parameters[1]['id']);
            $registerModel->updateData($employee,$registerModel->fileDestination());
            if($employee['role']=='doctor'){
                $employee=$registerModel->customFetchAll("Select * from doctor where emp_ID=".$parameters[1]['id']);
                $registerModel->updateData($employee,$registerModel->fileDestination());
            }
            Application::$app->session->set('employee',$parameters[1]['id']);
            return $this->render('administrator/admin-update-account',[
                'model'=>$registerModel,
            ]);
            
        }


        else{
            $accounts=$openedChannelingModel->customFetchAll("select * from employee where role not like 'admin'");
            return $this->render('administrator/view-all-accounts',[
                'accounts'=>$accounts
            ]);
        }
        
    }
    
    //view advertisement
    public function viewAdvertisement(){

        $this->setLayout("admin",['select'=>'Advertisement']);
        $advertisementModel=new Advertisement();
        $advertisements=$advertisementModel->customFetchAll("Select * from advertisement order by title asc");
        return $this->render('administrator/main-adds',[
            'advertisements'=>$advertisements,
            'model'=>$advertisementModel
        ]);
    }
    
    //delete update insert advertisement
    public function handleAdvertisement(Request $request, Response $response){
        $parameters=$request->getParameters();
        $this->setLayout('admin',['select'=>'Advertisement']);
        $advertisementModel=new Advertisement();

        //Delete operation
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='delete'){
            $delRow= $advertisementModel->customFetchAll("Select * from advertisement where ad_ID = ".$parameters[1]['id']);
            $advertisementModel->deleteImage(['ad_ID'=>$delRow[0]['image']]);
            $advertisementModel->deleteRecord(['ad_ID'=>$parameters[1]['id']]);
            Application::$app->session->setFlash('success',"Advertisement successfully deleted ");
            $response->redirect('/ctest/main-adds');
            return true;
        }

        //Go to update page of a advertisement
        if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
            $advertisement=$advertisementModel->customFetchAll("Select * from advertisement where ad_ID=".$parameters[1]['id']);
            $advertisementModel->updateData($advertisement,$advertisementModel->fileDestination());
            Application::$app->session->set('advertisement',$parameters[1]['id']);
            return $this->render('administrator/main-adds-update',[
                'model'=>$advertisementModel,
            ]);
        }

        if($request->isPost()){
            // update advertisement
            $advertisementModel->loadData($request->getBody());
            $advertisementModel->loadFiles($_FILES);
            if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='update'){
                    
                if($advertisementModel->validate() && $advertisementModel->updateRecord(['ad_ID'=>$parameters[1]['id']])){
                    $response->redirect('/ctest/main-adds'); 
                    Application::$app->session->setFlash('success',"Advertisement successfully updated ");
                    Application::$app->response->redirect('/ctest/main-adds');
                    exit; 
                };
                
            } 
            
            // add advertisement
            if($advertisementModel->validate() && $advertisementModel->addAdvertisement()){
                Application::$app->session->setFlash('success',"Advertisement successfully added ");
                Application::$app->response->redirect('/ctest/main-adds'); 
                $this->setLayout("admin",['select'=>'Advertisement']);
                $advertisementModel=new Advertisement();
                $advertisements=$advertisementModel->customFetchAll("Select * from advertisement order by name asc");
                return $this->render('administrator/main-adds',[
                    'advertisements'=>$advertisements,
                    'model'=>$advertisementModel
                ]);
       
            }
        }

        return $this->render('administrator/add-main-adds',[
            'model'=>$advertisementModel,
        ]);
    }

}