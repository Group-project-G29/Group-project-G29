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
use app\models\AdminNotification;

use app\models\Employee;
use app\models\NurseAllocation;
use app\models\OpenedChanneling;
use app\core\Time;

class AdminController extends Controller{
    // create add,view channeling session
    public function schedulingChanneling(Request $request,Response $response){
        $this->setLayout('admin',['select'=>"Channelings Sessions"]);
        $ChannelingModel=new Channeling();
        $Employee=new Employee();
        $Doctors=$Employee->customFetchAll("select name,nic from employee where employee_status='active' and role='doctor'");
        $Nurses=$Employee->customFetchAll("select * from employee where  role='nurse'");
        $Rooms=$Employee->customFetchAll("select * from room");
        $Doctor['Select']='';
        $Room['Select']='';
        $parameters=$request->getParameters();
        $nurseAllocationModel=new NurseAllocation();
        $timeModel=new Time();
        //process date structure
        foreach($Rooms as $row){
            $Room[$row['name']]=$row['name'];
        }

        foreach($Doctors as $row){
            $Doctor[$row['name']]=$row['nic'];
        }
        
        //show all opened channeling sessions after clicking on channeling session
        if(isset($parameters[0]['spec']) && $parameters[0]['spec']=='opened_channeling'){
            $OpenedChannelingModel=new OpenedChanneling();
            // close the channeling session (no appointment can be made)
            if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='close'){
               $OpenedChannelingModel->closeChanneling($parameters[2]['id']);
               exit;
               
            }
            //cancel the channelig session (remove all the appointments)
            if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='cancel'){
               $OpenedChannelingModel->cancelChanneling($parameters[2]['id']);
               exit;
            }
            if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='view' ){
                
                //get first 5 opened channeling sessions of the selected channeling session
                $op_channelings=$OpenedChannelingModel->getOpenedChannelings($parameters[2]['id']);
                $channeling=$ChannelingModel->fetchAssocAll(['channeling_ID'=>$parameters[2]['id']]);

                return $this->render('administrator/opened_channeling',[
                    'op_channelings'=>array_slice($op_channelings,0,5),
                    'channeling'=>$channeling
                ]);
            }
        }
        //handle post request from channeling scheduling form
        if($request->isPost()){
            //load all data in $_POST into the model
            $ChannelingModel->loadData($request->getBody());
            $nurseAllocationModel->loadData($request->getBody());
            //find if the new channeling session get overlapped with channeling session already scheduled
            $result=$ChannelingModel->checkOverlap();
        
            if((isset($result[0]) && $result[0]=='validation')){
                $this->setLayout('admin',['select'=>"Schedule Channelings"]);
                return $this->render('administrator/schedule-channeling',[
                    'employeemodel'=>$Employee,
                    'channelingmodel'=>$ChannelingModel,
                    'doctors'=>$Doctor,
                    'nurses'=>$Nurses,
                    'rooms'=>$Room,
                    

                ]);
            } 
            
           
            if( $ChannelingModel->validate() ){
                $nurseOverlap=$ChannelingModel->checkNurseOverlap($nurseAllocationModel->emp_ID,$ChannelingModel);
                $roomOverlap=$ChannelingModel->checkRoomOverlap($ChannelingModel->room,$ChannelingModel);
                //if validation falis inside checkOverlap
                if((isset($result[0]) && $result[0]=='validation') || $nurseOverlap || $roomOverlap){
                    $this->setLayout('admin',['select'=>"Schedule Channelings"]);
                    return $this->render('administrator/schedule-channeling',[
                        'employeemodel'=>$Employee,
                        'channelingmodel'=>$ChannelingModel,
                        'doctors'=>$Doctor,
                        'nurses'=>$Nurses,
                        'rooms'=>$Room,
                        'roomOverlaps'=>$roomOverlap,
                        'nurseOverlaps'=>$nurseOverlap
    
                    ]);
                }
                
                //save channeling in database
                $return_id=$ChannelingModel->savedata();
                if($return_id){
                    $success=true; //success variable is used to identify if a channeling session is successfully created
                    $tempnurseAllocationModel=$nurseAllocationModel;
                    $success=false;
                    //each nurse is saved in database one by one
                    //limit nurses
                    foreach($tempnurseAllocationModel->emp_ID as $nurse ){
                        $nurseAllocationModel->emp_ID=$nurse;
                        $nurseAllocationModel->channeling_ID=$return_id[0]['last_insert_id()'];
                        if($nurseAllocationModel->validate() && $nurseAllocationModel->savedata()){
                            $success=true;
                           
                        }
                    }
                    //new opened channeling session is created if a channeling is succesfully created
                    if($success){
                        $openedChannelingModel=new OpenedChanneling();
                        $calendarModel=new Calendar();
                        if($ChannelingModel->total_patients==0){
                            //if channeling session is not limited total_patients is -1
                            $openedChannelingModel->setter($return_id[0]['last_insert_id()'],-1,'',"Opened"); 
                        }
                        else{
                            $openedChannelingModel->setter($return_id[0]['last_insert_id()'],$ChannelingModel->total_patients,'',"Opened"); 

                        }
                        $frequency=$ChannelingModel->frequency." ".$ChannelingModel->frequency_type;
                        $duration=$ChannelingModel->schedule_for." ".$ChannelingModel->schedule_type;
                        $result=$openedChannelingModel->generateOpenedChannelings($ChannelingModel->start_date, date('l', strtotime($ChannelingModel->start_date)),$ChannelingModel->day,$duration,$openedChannelingModel,$frequency); 
                        
                        if($result){
                            Application::$app->session->setFlash('success',"Channeling Session Added Successfully");
                            Application::$app->response->redirect("/ctest/schedule-channeling");
                            
                        }
                        //if failed to generate channeling show this
                        else{
                            Application::$app->session->setFlash('error',"Channeling Session Failed to add becuase of overlap");
                            return $this->render('administrator/schedule-channeling',[
                                'employeemodel'=>$Employee,
                                'channelingmodel'=>$ChannelingModel,
                                'doctors'=>$Doctor,
                                'nurses'=>$Nurses,
                                'rooms'=>$Room,
                                'test'=>$ChannelingModel->errors

                            ]);
                        }
                    }
                }
                
            }
        }
        //show channeling schedule page
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
        
        //if reques is not a post or mod=get show all channeling page
        $channelings=$ChannelingModel->customFetchAll("Select * from channeling left join doctor on doctor.nic=channeling.doctor left join employee on employee.nic=doctor.nic");
        // var_dump($channelings);exit;
        return $this->render('administrator/view-channeling',[
                'channelings'=>$channelings
        ]);
        
       
    }

    // // Channeling sessions view
    // public function channelingSessionsView(Request $request){
    //     $this->setLayout("admin",['select'=>'Channelings Sessions']);
        
    //     $parameters=$request->getParameters();
    //     $speciality=$parameters[0]['spec']??'';
    //     // var_dump($parameters);exit;
    //     // $day=$parameters[1]['day']??'';
    //     // var_dump($day);exit;
        
    //     $ChannelingModel=new Channeling();
        
    //     $Channeling=$ChannelingModel->customFetchAll("SELECT * FROM `channeling` INNER JOIN `employee` ON channeling.doctor = employee.nic where channeling.speciality='$speciality' ORDER BY channeling.time; ");

    //     if($speciality){ //var_dump($speciality);exit;
    //         Application::$app->session->set('channelings',$Channeling);
    //         return $this->render('administrator/admin-all-channeling-category-list',[
                
    //             'channelings'=>$Channeling,
    //             'speciality'=>$speciality
               
               
    //         ]);
    //     }
       
    //     $ChannelingModel=new Channeling();
    //     $specialities=$ChannelingModel->customFetchAll("Select distinct channeling.speciality from opened_channeling left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID");
    //     // var_dump($specialities);exit;
    //     return $this->render('administrator/admin-all-channeling-categories',[

    //         'specialities'=>$specialities, 
    //         'app'=>$ChannelingModel
            
        
    //     ]);
    // }


    // admin employee account crud
    public function registerAccounts(Request $request,Response $response){
        $parameters=$request->getParameters();// [[0]=>['mod'=>'add'],[1]=>['id=>'2']]
        $openedChannelingModel=new OpenedChanneling();
        $registerModel=new Employee();
        $this->setLayout('admin',['select'=>"Manage Users"]);
       

        //create new employee acccount
        if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='add'){
            if($request->isPost()){
                $registerModel->loadData($request->getBody()); //load data into model
                $registerModel->loadFiles($_FILES); //load files such as images
                 if($registerModel->validate() && $registerModel->register()){
                    // if the data is validated and saved in database
                    Application::$app->session->setFlash('success',"Thanks for registering"); 
                    Application::$app->response->redirect('/ctest/admin');
                    exit;
                }
    
            }
            // if it is a get request show employee registration form
            return $this->render('administrator/employee-registration',[
                'model'=>$registerModel,
                
            ]);
        }
        // delete an account 
        else if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='delete'){
            $registerModel->deleteRecord(['emp_ID'=>$parameters[1]['id']]);
            Application::$app->session->setFlash('success',"Account successfully deleted ");
            $response->redirect('/ctest/admin');
            return true;
        }
       
        //update an account
         if($request->isPost()){
            $register=$registerModel->findOne(['emp_ID'=>$parameters[1]['id']]);
        
            $register->loadData($request->getBody());
            $register->loadFiles($_FILES);

            if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
               
                
                  
                if($register->validate()  && $registerModel->updateAccounts($parameters[1]['id'])){
                    $response->redirect('/ctest/admin'); 
                    Application::$app->session->setFlash('success',"Account successfully updated ");
                    Application::$app->response->redirect('/ctest/admin');
                    exit; 
                 };
                
            } 
            
        
            return $this->render('administrator/admin-update-account',[
                'model'=>$registerModel,
                'select'=>"Manage Users"
            ]);
        }
           //show update form
        $register=$registerModel->findOne(['emp_ID'=>$parameters[1]['id']]);
        if(isset($parameters[0]['mod']) && $parameters[0]['mod']=='update'){
            // $registerModel->loadData($request->getBody()); //load data into model
            // $registerModel->loadFiles($_FILES);
            // if($registerModel->validate()){
            //     //$registerModel->updateRecordv2(['emp_ID'=>$registerModel->emp_ID],['name'=>$registerModel->name,'nic'=>$registerModel->nic,'age'=>$registerModel->age,'contact'=>$registerModel->contact,'email'=>$registerModel->email,'address'=>$registerModel->address,'img'=>$registerModel->img,'gender'=>$registerModel->gender]);   
                
            // } 
            //show update page
            return $this->render('administrator/admin-update-account',[
                'model'=>$register,
            ]);
            
        }

      
        //defualt show all accounts
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
            $advertisementModel->deleteImage($delRow[0]['img'], $delRow[0]['type']);
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
            $advertisementModel->loadData($request->getBody());
            $advertisementModel->loadFiles($_FILES);
            $ad_ID=(int)$parameters[1]['id'];

            // update advertisement
            if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='update'){
                // var_dump($parameters);exit;
                if(!isset($_POST['img'])){
                    $imgName = $advertisementModel->customFetchAll("SELECT img FROM advertisement WHERE ad_ID=$ad_ID;");
                    $advertisementModel->img = $imgName[0]['img'];
                    // var_dump($advertisementModel);exit;
                }
                if($advertisementModel->validate() && $advertisementModel->updateRecord(['ad_ID'=>$ad_ID])){
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



    public function handleNotifications(Request $request,Response $response){
        $parameters=$request->getParameters();
        $this->setLayout('admin',['select'=>"Notification"]);
        $notificationModel=new AdminNotification();
        

        // var_dump($parameters);exit;
        // delete notification
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='delete'){
            $notificationModel->deleteRecord(['noti_ID'=>$parameters[1]['id']]);
            Application::$app->session->setFlash('success',"Account successfully deleted ");
            $response->redirect('/ctest/admin-notification');
            return true;
        }

        // update notification
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='update'){
            $id = (int)$parameters[1]['id'];
            // var_dump($id);exit;
            $notification=$notificationModel->customFetchAll("UPDATE admin_notification SET is_read = 0 WHERE noti_ID = $id;");
            // var_dump($notification);exit;
            $response->redirect('/ctest/admin-notification');
            return true;
        }

        $notifications=$notificationModel->customFetchAll("Select * from admin_notification order by created_date_time");

        return $this->render('administrator/view-notifications',[
            "notifications"=>$notifications,
            "model"=>$notificationModel,
        ]);
    }
    //update channeling session information
    public function changeChanneling(Request $request,Response $response){
        $this->setLayout('admin',['select'=>'Channelings Sessions']);
        $parameters=$request->getParameters();
        $channelingModel=new Channeling();
        $employeeModel=new Employee();
        $Employee=new Employee();
        $channelingModel=new Channeling();
        Application::$app->session->set('selected_channeling',(isset($parameters[1]['id']))?$parameters[1]['id']:$parameters[2]['id']);
        $Nurses=$Employee->customFetchAll("select * from employee where role='nurse'");
        $Rooms=$Employee->customFetchAll("select * from room");
         foreach($Rooms as $row){
            $Room[$row['name']]=$row['name'];
        }
        if(isset($parameters[0]['spec']) && $parameters[0]['spec']=='opened_channeling'){
            if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='cancel'){
                $channelingModel->cancelOpenedChanneling($parameters[2]['id']);
            }
            else if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='close'){
                $channelingModel->closeOpenedChanneling($parameters[2]['id']);
            }
            else if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='open'){
                $channelingModel->openOpenedChanneling($parameters[2]['id']);
            }
            
            $response->redirect('update-channeling?cmd=view&id='.Application::$app->session->get('selected_channeling'));           
            exit;
        }
        if(isset($parameters[0]['spec']) && $parameters[0]['spec']=='channeling'){
            if(isset($parameters[1]['cmd']) && $parameters[1]['cmd']=='cancel'){
                $channelingModel->cancelChanneling($parameters[2]['id']);
            }            
            $response->redirect('update-channeling?cmd=view&id='.Application::$app->session->get('selected_channeling'));           
            exit;
        }
                
        if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='view'){
            Application::$app->session->set('selected_channeling',(isset($parameters[1]['id']))?$parameters[1]['id']:$parameters[2]['id']);
            return $this->render('administrator/update-channeling',[
                'model'=>$channelingModel->findOne(['channeling_ID'=>$parameters[1]['id']]),
                'rooms'=>$Room,
                'nurses'=>$Nurses,
                'employeemodel'=>$employeeModel,
                'id'=>Application::$app->session->get('selected_channeling'), 
                'openedchannelings'=>$channelingModel->getOpenedChannelings($parameters[1]['id']),
                'channelings'=>$channelingModel->fetchAssocAll(['channeling_ID'=>$parameters[1]['id']])[0]
            ]);
        }
        
            if(isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='update'){
                $channelingModel=new Channeling();
                $channeling=$channelingModel->findOne(['channeling_ID'=>$parameters[1]['id']]);
                if($request->isPost()){
                    $roomOverlaps=$channelingModel->checkRoomOverlap($channelingModel->room,$channeling);
                    $nurseOverlaps=$channelingModel->checkNurseOverlap($_POST['emp_ID'],$channeling);
                    $channelingModel->loadData($request->getBody());
                    if($nurseOverlaps || $roomOverlaps){
                        return $this->render('administrator/update-channeling',[
                        'model'=>$channelingModel,
                        'rooms'=>$Room,
                        'nurses'=>$Nurses,
                        'employeemodel'=>$employeeModel,
                        'id'=>Application::$app->session->get('selected_channeling'),
                        'openedchannelings'=>$channelingModel->getOpenedChannelings(Application::$app->session->get('selected_channeling')),
                        'channelings'=>$channelingModel->fetchAssocAll(['channeling_ID'=>Application::$app->session->get('selected_channeling')])[0], 
                        'roomOverlaps'=>$roomOverlaps,
                        'nurseOverlaps'=>$nurseOverlaps
                        ]);    
                    }
                    if( $channelingModel->updateChannelingRecord(Application::$app->session->get('selected_channeling'))){    
                        $employeeModel->removeNurse(Application::$app->session->get('selected_channeling'));
                        $employeeModel->addNurse('emp_ID',Application::$app->session->get('selected_channeling'));
                        //redirect on success
                    }
                    return $this->render('administrator/update-channeling',[
                        'model'=>$channelingModel,
                        'rooms'=>$Room,
                        'nurses'=>$Nurses,
                        'employeemodel'=>$employeeModel,
                        'id'=>Application::$app->session->get('selected_channeling'),
                        'openedchannelings'=>$channelingModel->getOpenedChannelings(Application::$app->session->get('selected_channeling')),
                        'channelings'=>$channelingModel->fetchAssocAll(['channeling_ID'=>Application::$app->session->get('selected_channeling')])[0] 
                    ]);
                }
            }
        
            
    }
    public function test(){
        return $this->render('administrator/test');
    }
}
