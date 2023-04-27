<?php
namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\User;
use app\core\DbModel;
use app\core\Response;
use app\models\Appointment;
use app\models\Channeling;
use app\models\Employee;
use app\models\Medicine;
use app\models\OpenedChanneling;
use app\models\Patient;
use app\models\PreChanneilngTestAloc;
use app\models\PreChannelingTest;
use app\models\PreChanneilngTestsValue;

class NurseController extends Controller{
    
    //view user details
    public function viewUserDetails(){
        $this->setLayout("nurse",['select'=>'My Detail']);
        $userDetailModel=new Employee();
        $user=$userDetailModel->customFetchAll("SELECT * FROM employee WHERE email=".'"'.Application::$app->session->get('user').'"');
        return $this->render('nurse/my-details',[
            "user" => $user[0]
        ]);
      
    }

    // //view all clinics
    // public function viewAllClinics(){
    //     $this->setLayout("nurse",['select'=>'All Channelings']);
    //     $allChanneling=new Channeling();
    //     $clinics=$allChanneling->customFetchAll("SELECT * FROM `channeling` INNER JOIN `employee` ON channeling.doctor = employee.nic;");
    //     var_dump($clinics);exit;
    //     return $this->render('nurse/all-channelings',[
    //         "clinics" => $clinics
    //     ]);
    // }

    //view today clinics
    public function todayClinics(){
        $this->setLayout("nurse",['select'=>'Today Channelings']);
        $OpenedChanneling=new OpenedChanneling();
        $userDetailModel=new Employee();
        $user=$userDetailModel->customFetchAll("SELECT * FROM employee WHERE email=".'"'.Application::$app->session->get('user').'"');
        $eID = $user[0]['emp_ID'];
        // var_dump($eID);exit;

        $date = date("Y-m-d");
        // var_dump($date);exit;
        
        $openedChanneling=$OpenedChanneling->customFetchAll("SELECT * FROM `opened_channeling` INNER JOIN `channeling` ON opened_channeling.channeling_ID = channeling.channeling_ID INNER JOIN `employee` ON channeling.doctor = employee.nic INNER JOIN `nurse_channeling_allocataion` ON channeling.channeling_ID = nurse_channeling_allocataion.channeling_ID WHERE channeling_date = '$date' AND nurse_channeling_allocataion.emp_ID = $eID;");
        // var_dump($openedChanneling);exit;
        return $this->render('nurse/today-channelings',[
            "openedChanneling" => $openedChanneling
        ]);
    }


    public function viewChanneling(Request $request){
        $Channeling=new Channeling();
        $OpenedChanneling=new OpenedChanneling();
        $Doctor=new Employee();
        $apointment=new Appointment();
        $this->setLayout('normal');
        $parameters=[];
        if($request->getParameters()){
            $parameters=$request->getParameters();
            $OpenedChanneling=$OpenedChanneling->findOne(["opened_channeling_ID"=>$parameters[0]['channeling']]); 
            $Employee=new Employee();
            $Channeling=$Channeling->findOne(["Channeling_ID"=>$OpenedChanneling->channeling_ID]);
            $Doctor=$Doctor->customFetchAll("Select * from employee left join  doctor on doctor.nic=employee.nic where doctor.nic=".$Channeling->doctor);
            $tApointment = $apointment->getTotoalPatient($OpenedChanneling->channeling_ID);//var_dump($tApointment);exit;
            $rApointment = $apointment->getUsedPatient($OpenedChanneling->channeling_ID);//var_dump($rApointment);exit;
            // var_dump($OpenedChanneling->channeling_ID);
            $Nurses=$Employee->customFetchAll("Select * from employee right join nurse_channeling_allocataion on employee.emp_ID=nurse_channeling_allocataion.emp_ID  left join channeling on channeling.channeling_ID=nurse_channeling_allocataion.channeling_ID  where nurse_channeling_allocataion.channeling_ID=".$OpenedChanneling->channeling_ID);
            
     
        }

        return $this->render('nurse/all-channeling-session',[
            'openedchanneling'=>$OpenedChanneling,
            'channeling'=>$Channeling,
            'doctor'=>$Doctor,
            'nurse'=>$Nurses
            

        ]);
    }



    //view all clinics more
    public function viewAllClinicsMore(Request $request){
        
        $this->setLayout("normal");
        $Channeling=new Channeling();
        $OpenedChanneling=new OpenedChanneling();
        $Doctor=new Employee();
        $Nurse=new Employee();

        if($request->getParameters()){
            $parameters=$request->getParameters();
            $id = $parameters[0]['id'];
            // echo($id);exit();
        }
        $channeling=$Channeling->customFetchAll("SELECT * FROM `channeling` INNER JOIN `employee` ON channeling.doctor = employee.nic WHERE channeling_ID=$id;");
        $openedChanneling=$OpenedChanneling->customFetchAll("SELECT * FROM `opened_channeling` WHERE channeling_ID=$id AND status != 'not open' AND status != 'end';");
        $docNIC = $channeling[0]['doctor'];
        $doctor = $Doctor->customFetchAll("SELECT * FROM `employee` WHERE nic=$docNIC;");
        $nurse = $Nurse->customFetchAll("SELECT * FROM `nurse_channeling_allocataion` INNER JOIN `employee` ON nurse_channeling_allocataion.emp_ID = employee.emp_ID WHERE channeling_ID=$id;");
        // var_dump($channeling[0]['doctor']);exit();
        // var_dump($nurse);exit();
        return $this->render('nurse/all-channeling-more',[
            "channeling" => $channeling,
            "openedchanneling" => $openedChanneling,
            "doctor" => $doctor,
            "nurse" => $nurse
        ]);
    }


    public function channelingCategoriesView(Request $request){
        $this->setLayout("nurse",['select'=>'All Channelings']);
        
        $parameters=$request->getParameters();
        $speciality=$parameters[0]['spec']??'';
        // var_dump($parameters);exit;
        // $day=$parameters[1]['day']??'';
        // var_dump($day);exit;
        
        $ChannelingModel=new Channeling();
        
        $Channeling=$ChannelingModel->customFetchAll("SELECT * FROM `channeling` INNER JOIN `employee` ON channeling.doctor = employee.nic INNER JOIN `opened_channeling` ON channeling.channeling_ID = opened_channeling.channeling_ID  where opened_channeling.status != 'end' AND opened_channeling.status != 'not open' AND channeling.speciality='$speciality' GROUP BY opened_channeling.channeling_ID ORDER BY channeling.time; ");

        if($speciality){ //var_dump($speciality);exit;
            Application::$app->session->set('channelings',$Channeling);
            return $this->render('nurse/nurse-all-channeling-category-list',[
                
                'channelings'=>$Channeling,
                'speciality'=>$speciality
               
               
            ]);
        }
       
        $ChannelingModel=new Channeling();
        $specialities=$ChannelingModel->customFetchAll("Select distinct channeling.speciality from opened_channeling left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID");
        
        return $this->render('nurse/all-channeling-categories',[

            'specialities'=>$specialities, 
            'app'=>$ChannelingModel
            
        
        ]);
    }




    public function viewSessionPatients(Request $request){
        $this->setLayout("normal"); 

        $parameters=$request->getParameters();
        $id = $parameters[0]['id'];
        $view1 = $parameters[1]['view']??"";
        $num = $parameters[1]['num']??"";
        $pre = $parameters[2]['p']??"";
        $nex = $parameters[2]['n']??"";
        $view2 = $parameters[3]['view']??"";
        $number = (int)$num;

        // var_dump($parameters);exit;
        $Channeling=new Channeling();
        $OpenedChanneling=new OpenedChanneling();
        $Doctor=new Employee();
        $Patient=new Employee();
        $Test=new PreChanneilngTestAloc();
        $preChannelingTestsValueModel=new PreChanneilngTestsValue();

        
        $openedChanneling=$OpenedChanneling->customFetchAll("SELECT * FROM `opened_channeling` WHERE opened_channeling_ID=$id;");
        // var_dump($openedChanneling);exit;
        $cid=$openedChanneling[0]['channeling_ID'];
        
        $channeling=$Channeling->customFetchAll("SELECT * FROM `channeling` INNER JOIN `employee` ON channeling.doctor = employee.nic WHERE channeling_ID=$cid;");
        $docNIC = $channeling[0]['doctor'];
        $doctor = $Doctor->customFetchAll("SELECT * FROM `employee` WHERE nic=$docNIC;");

        $patient = $Patient->customFetchAll("SELECT * FROM `opened_channeling` INNER JOIN `appointment` ON opened_channeling.opened_channeling_ID = appointment.opened_channeling_ID INNER JOIN `patient` ON appointment.patient_ID = patient.patient_ID WHERE opened_channeling.opened_channeling_ID=$id;");
        // var_dump($patient);exit;

        // $nurse = $Nurse->customFetchAll("SELECT * FROM `nurse_channeling_allocataion` INNER JOIN `employee` ON nurse_channeling_allocataion.emp_ID = employee.emp_ID WHERE channeling_ID=$id;");
        
        $reApp = count($patient);
        // var_dump($reApp);exit;
        if($pre){
            if(0<$number && $number<$reApp){
                $newNumber = $number-1;
            }
            else{
                $newNumber = 0;
            }
        }
        else if($nex){
            if(-1<$number && $number<$reApp-1){
                $newNumber = $number+1;
            }
            else if($number >= $reApp-1){
                $newNumber = $reApp-1;
            }
            else if($number < 0){
                $newNumber = 0;
            }
        }
        else{
            $newNumber = 0;
        }
        // var_dump($newNumber);exit;

        $tests = $Test->customFetchAll("SELECT * FROM `pre_channeilng_test_aloc` INNER JOIN `pre_channeling_tests` ON pre_channeilng_test_aloc.test_ID = pre_channeling_tests.test_ID WHERE pre_channeilng_test_aloc.channeling_ID=$cid;");
        // var_dump($tests);exit;

        $apoid = $patient[$newNumber]['appointment_ID']??'';
        if($apoid){
            $testValue = $preChannelingTestsValueModel->customFetchAll("SELECT value FROM `pre_channeling_tests_values` WHERE appointment_ID=$apoid");
        }
        // var_dump($testValue);exit;

        if($view1 or $view2){
            return $this->render('nurse/all-channeling-patient-list',[
                "channeling" => $channeling[0],
                "doctor" => $doctor[0],
                "patient" => $patient,
                "number" => $newNumber,
                "id" => $id,
                "tests" => $tests,
                "testValues" => $testValue
            ]);
        }
        

        return $this->render('nurse/nurse-list-patient',[
            "channeling" => $channeling[0],
            "doctor" => $doctor[0],
            "patient" => $patient,
            "number" => $newNumber,
            "id" => $id,
            "tests" => $tests,
            "testValues" => $testValue
        ]);
    }





    public function addTestValue(Request $request){

        $preChannelingTestsValueModel=new PreChanneilngTestsValue();
        $Tests=new PreChanneilngTestAloc();

        if($request->isPost()){

            // get input data
            $id = (int)$_POST['id'];
            $p_ID = (int)$_POST['pid'];
            $number = (int)$_POST['number'];
            $c_ID = (int)$_POST['cid'];
            $apo_ID = (int)$_POST['apoid'];
            $valus = $_POST;

            $tests = $Tests->customFetchAll("SELECT test_ID FROM `pre_channeilng_test_aloc` WHERE channeling_ID = $c_ID");
            // var_dump($tests);exit;

            $saveData = $preChannelingTestsValueModel->addChannelingTestValues($valus,$tests,$apo_ID);
            header("location: nurse-list-patient?id=$id&num=$number&n=1");

        }
    }


    public function editTestValueView(Request $request){
        $this->setLayout("normal"); 

        $parameters=$request->getParameters();
        $id = $parameters[0]['id'];
        $num = $parameters[1]['num'];
        $number = (int)$num;

        $Channeling=new Channeling();
        $OpenedChanneling=new OpenedChanneling();
        $Doctor=new Employee();
        $Patient=new Employee();
        $Test=new PreChanneilngTestAloc();
        $preChannelingTestsValueModel=new PreChanneilngTestsValue();

        $openedChanneling=$OpenedChanneling->customFetchAll("SELECT * FROM `opened_channeling` WHERE opened_channeling_ID=$id;");
        // var_dump($openedChanneling);exit;
        $cid=$openedChanneling[0]['channeling_ID'];
        $channeling=$Channeling->customFetchAll("SELECT * FROM `channeling` INNER JOIN `employee` ON channeling.doctor = employee.nic WHERE channeling_ID=$cid;");

        $docNIC = $channeling[0]['doctor'];
        $doctor = $Doctor->customFetchAll("SELECT * FROM `employee` WHERE nic=$docNIC;");

        $patient = $Patient->customFetchAll("SELECT * FROM `opened_channeling` INNER JOIN `appointment` ON opened_channeling.opened_channeling_ID = appointment.opened_channeling_ID INNER JOIN `patient` ON appointment.patient_ID = patient.patient_ID WHERE opened_channeling.opened_channeling_ID=$id;");

        $tests = $Test->customFetchAll("SELECT * FROM `pre_channeilng_test_aloc` INNER JOIN `pre_channeling_tests` ON pre_channeilng_test_aloc.test_ID = pre_channeling_tests.test_ID WHERE pre_channeilng_test_aloc.channeling_ID=$cid;");

        $apoid = $patient[$number]['appointment_ID'];
        $testValue = $preChannelingTestsValueModel->customFetchAll("SELECT value FROM `pre_channeling_tests_values` WHERE appointment_ID=$apoid");
        

        return $this->render('nurse/nurse-patient-test-value-edit',[
            "channeling" => $channeling[0],
            "doctor" => $doctor[0],
            "patient" => $patient,
            "number" => $number,
            "id" => $id,
            "tests" => $tests,
            "testValues" => $testValue
        ]);
    }


    public function editTestValueUpdate(Request $request){

        $preChannelingTestsValueModel=new PreChanneilngTestsValue();
        $Tests=new PreChanneilngTestAloc();

        if($request->isPost()){

            // get input data
            $id = (int)$_POST['id'];
            $p_ID = (int)$_POST['pid'];
            $number = (int)$_POST['number'];
            $c_ID = (int)$_POST['cid'];
            $apo_ID = (int)$_POST['apoid'];
            $valus = $_POST;

            $tests = $Tests->customFetchAll("SELECT test_ID FROM `pre_channeilng_test_aloc` WHERE channeling_ID = $c_ID");
            // var_dump($tests);exit;

            $saveData = $preChannelingTestsValueModel->updateChannelingTestValues($valus,$tests,$apo_ID);
            header("location: nurse-list-patient?id=$id&num=$number&n=1");

        }
    }



   

    public function viewChannelingAllocation(Request $request){
        $this->setLayout('nurse',['select'=>'Channeling Allocations']);

        $Employee=new Employee();
        $OpenedChanneling=new OpenedChanneling();

        // set current date
        $date = date("Y-m-d");
        // parse about any English textual datetime description into a Unix timestamp 
        $ts = strtotime($date);
        // find the year (ISO-8601 year number) and the current week
        $year = date('o', $ts);
        $week = date('W', $ts);
        $datesArray = array();
        // print week for the current date
        for($i = 1; $i <= 7; $i++) {
            // timestamp from ISO week date format
            $ts = strtotime($year.'W'.$week.$i);
            // print date("Y-m-d l", $ts);
            $date = explode(" ", date("Y-m-d l", $ts));
            array_push($datesArray, $date[0]);
        }
        // var_dump($datesArray); exit;

        $nurse = $Employee->customFetchAll("SELECT emp_ID FROM `employee` WHERE email=".'"'.Application::$app->session->get('user').'"');
        $n_ID = $nurse[0]['emp_ID']; 

        $monday = $OpenedChanneling->channelingsForDate($datesArray[0], $n_ID);
        $tuesday = $OpenedChanneling->channelingsForDate($datesArray[1], $n_ID);
        $wednesday = $OpenedChanneling->channelingsForDate($datesArray[2], $n_ID);
        $thursday = $OpenedChanneling->channelingsForDate($datesArray[3], $n_ID);
        $friday = $OpenedChanneling->channelingsForDate($datesArray[4], $n_ID);
        $saturday = $OpenedChanneling->channelingsForDate($datesArray[5], $n_ID);
        // var_dump($tuesday); exit;

        return $this->render('nurse/nurse-channeling-allocations',[
            'monday'=>$monday,
            'tuesday'=>$tuesday,
            'wednesday'=>$wednesday,
            'thursday'=>$thursday,
            'friday'=>$friday,
            'saturday'=>$saturday
        ]);
    }

}

