<?php

namespace app\controllers;

use app\controllers\PatientAuthController;
use app\core\Application;
use app\core\Controller;
use app\core\form\Select;
use app\core\Request;
use app\core\Response;
use app\models\Appointment;
use app\models\Channeling;
use app\models\Employee;
use app\models\Patient;
use app\models\Payment;
use app\models\Referral;
use app\models\OpenedChanneling;
use app\models\Doctor;

class ReceptionistController extends Controller
{

    // view all patient go here
    public function handlePatient(Request $request, Response $response)
    {
        $parameters = $request->getParameters();
        $patientModel = new Patient();
        $paymentModel= new Payment();
        $appoinmentModel= new Appointment();
        $referralModel= new Referral();
        $this->setLayout('receptionist', ['select' => 'Patients']);
        if (isset($parameters[0]['mod']) && $parameters[0]['mod'] == 'view') {
            if (isset($parameters[1]['id'])) {
                $patient = $patientModel->customFetchAll("Select * from patient where patient_ID=" . $parameters[1]['id']);
                $paymentinfoArray=$paymentModel->customFetchAll("SELECT * from payment where patient_ID=" . $parameters[1]['id']);
                $referrals= $referralModel->customFetchAll("SELECT * from referrel where patient=".$parameters[1]['id']);

                $labPayment=[];
                $appointmentPayment=[];

                foreach ( $paymentinfoArray as $paymentinfo ){
                    if($paymentinfo["request_ID"] !== NULL){
                        $labPayment[$paymentinfo["payment_ID"]] =$paymentinfo=$paymentModel->customFetchAll("SELECT payment.amount,payment.payment_ID,employee.name as ename,payment.payment_ID,payment.generated_timestamp as date ,payment.generated_time as time,payment.payment_status,doctor.career_speciality as speciality from payment join lab_request on payment.request_ID=lab_request.request_ID
                        join doctor on doctor.nic=lab_request.doctor 
                        join employee on employee.nic=doctor.nic where payment_ID=".$paymentinfo["payment_ID"])[0]; 
                         
                    } elseif ($paymentinfo["appointment_ID"] !== NULL) {
                        $appointmentPayment[$paymentinfo["payment_ID"]] = $paymentinfo=$paymentModel->customFetchAll("SELECT payment.amount,payment.payment_ID,employee.name as ename ,payment.generated_timestamp as date ,payment.generated_time as time,payment.payment_status,channeling.speciality from payment join appointment on payment.appointment_ID=appointment.appointment_ID
                        join opened_channeling on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID
                        join channeling on channeling.channeling_ID=opened_channeling.channeling_ID
                        join employee on employee.nic=channeling.doctor where payment_ID=".$paymentinfo["payment_ID"])[0]; // inner join with appointment,doctor name, date
                    }
                }


                return $this->render("receptionist/patient-detail", [
                    'patient' => $patient[0],
                    'labPayment' => $labPayment,
                    'appointmentPayment' => $appointmentPayment,
                    'referrals'=>$referrals
                ]);
            }
            $patients = $patientModel->customFetchall("Select * from patient");
            return $this->render("receptionist/all-patient", [
                'patients' => $patients
            ]);

        } else if ($request->isPost()) {
            // update patient
            $patientModel->loadData($request->getBody());
            //to pass validation of password
            $patientModel->password="DummyPassword1!";
            $patientModel->cpassword=$patientModel->password;
            $patientModel->loadFiles($_FILES);
            // if(isset($_POST['Payment'])){
            //     $Payment ="done";
            //     $pay1=$paymentModel->customFetchAll("update payment set payment_status='done' ");   
            // }
            
            // if(isset($_POST['APayment'])){
            //     $APayment ="done";
            //     $apay1=$appoinmentModel->customFetchAll("update appointment set payment_status='done' ");   

            // }
            // else{
            //     $Payment ="pending";
            //     $pay2=$appoinmentModel->customFetchAll("update appointment set payment_status='pending' ");   
            //     $apay2=$appoinmentModel->customFetchAll("update payment set payment_status='pending' ");   

            // }
            if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'update') {
                if ($patientModel->updateRecord(['patient_ID' => $parameters[1]['id']])) {
                    Application::$app->session->setFlash('success', "Patient successfully updated ");
                    Application::$app->response->redirect('/ctest/receptionist-handle-patient?mod=view');
                } else {
                    return $this->render('receptionist/update-patient', [
                        'model' => $patientModel,
                    ]);
                }
            } 
            else if ($patientModel->validate() && $patientModel->register_non_session()) {
                Application::$app->session->setFlash('success', "Patient successfully added ");
                Application::$app->response->redirect('/ctest/receptionist-handle-patient?mod=view');
            } 
            else {
                return $this->render("receptionist/add-patient", [
                    'model' => $patientModel
                ]);
            }
        } else if (isset($parameters[0]['mod']) && $parameters[0]['mod'] == 'add') {
            return $this->render("receptionist/add-patient", [
                'model' => $patientModel
            ]);
        } else if (isset($parameters[0]['mod']) && $parameters[0]['mod'] == 'update') {
            $patient = $patientModel->customFetchAll("Select * from patient where patient_ID=" . $parameters[1]['id']);
            $patientModel->updateData($patient, $patientModel->fileDestination());
            Application::$app->session->set('patient', $parameters[1]['id']);
            return $this->render('receptionist/update-patient', [
                'model' => $patientModel,
            ]);
        }
    }


    public function allPaymentDone(Request $request){
        $paymentModel= new Payment();
        $parameters = $request->getParameters();
        $pay1=$paymentModel->customFetchAll("update payment set payment_status='done' where payment_ID=" . $parameters[0]['pay_ID'] );
        $appoinmentModelID=$paymentModel->customFetchAll("select appointment_ID from payment where payment_ID=".$parameters[0]['pay_ID'] );
        $paymentModel->customFetchAll("update appointment set payment_status='done' where appointment_ID=".$appoinmentModelID[0]['appointment_ID']);
       
        return json_encode(['status'=>true,'message'=>'user online']);
    }

    public function allPaymentNotdone(Request $request){
        $paymentModel= new Payment();
        $parameters = $request->getParameters();
        $pay1=$paymentModel->customFetchAll("update payment set payment_status='pending' where payment_ID=" . $parameters[0]['pay_ID'] );
        $appoinmentModelID=$paymentModel->customFetchAll("select appointment_ID from payment where payment_ID=".$parameters[0]['pay_ID'] );
        $paymentModel->customFetchAll("update appointment set payment_status='pending' where appointment_ID=".$appoinmentModelID[0]['appointment_ID']);
        return json_encode(['status'=>true,'message'=>'user online']);
    }

    public function handleAppointments(Request $request, Response $response)
    {
        $this->setLayout('receptionist', ['select' => 'Patients']);
        $parameters = $request->getParameters();
        $AppointmentModel = new Appointment();
        $ChannelingModel = new Channeling();
        $PatientModel = new Patient();
        $OpenedChannelingModel = new OpenedChanneling();
        $PaymentModel = new Payment();
        $opened_channeling_id = $parameters[1]['id'] ?? '';
        $appointment_id = '';
        if (isset($parameters[0]['mod']) && $parameters[0]['mod'] == 'view') {
            $channelings = $ChannelingModel->getRecentlyOpenedChanneling();
            Application::$app->session->set('patient', $parameters[1]['id']);
            $patient = $PatientModel->customFetchAll("Select * from patient where patient_ID=" . $parameters[1]['id']);
            return $this->render('receptionist/receptionist-patient-channeling-search', [
                'channelings' => $channelings,
                'patient' => $patient
            ]);
        }
        if ($parameters[0]['mod'] ?? '' == 'referral') {

            $ReferralModel = new Referral();
            
            $channelings = $ChannelingModel->customFetchAll("Select * from appointment  left join opened_channeling on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join doctor on  doctor.nic=channeling.doctor left join employee on employee.nic=doctor.nic where appointment.appointment_ID=" . $parameters[1]['id']);
            if ($request->isPost()) {
                if($_FILES['name']['size']){

                    $ReferralModel->loadFiles($_FILES);
                    $ReferralModel->type='softcopy';
    
                    $ReferralModel->setter($channelings[0]['nic'], Application::$app->session->get('patient'), $channelings[0]['speciality'], '', 'softcopy', $channelings[0]['name'],$parameters[1]['id']);
                    $ReferralModel->addReferral($parameters[1]['id']);
                }
                Application::$app->session->setFlash('success', "Appointment Successfuly Created");
                Application::$app->response->redirect('/ctest/receptionist-patient-information?mod=view&id=' . Application::$app->session->get('patient'));
            }
            $ChannelingModel = new Channeling();
            $appointment = $AppointmentModel->findOne(['Appointment_ID' => $parameters[1]['id']]);
            $Channeling = $ChannelingModel->customFetchAll("select * from appointment left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join doctor on doctor.nic=channeling.doctor where appointment_ID=" . $parameters[1]['id'])[0];
            //Update query

            return $this->render('receptionist/receptionist-add-referral', [
                'channeling' => $Channeling,
                'channelings' => Application::$app->session->get('channelings'),
                //unset session
                'model' => $ReferralModel,
                'appointment' => $appointment
            ]);
        }
        if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'delete') {
            $id = $AppointmentModel->customFetchAll("select opened_channeling_ID from appointment where appointment_ID=" . $parameters[1]['id']);
            $AppointmentModel->deleteRecord(['Appointment_ID' => $parameters[1]['id']]);
            Application::$app->session->setFlash('success', "Appointment successfully cancelled ");
            $OpenedChannelingModel->decreasePatientNumber($id[0]['opened_channeling_ID']);
            $OpenedChannelingModel->fixAppointmentNumbers($id[0]['opened_channeling_ID']);
            $response->redirect('/ctest/receptionist-patient-information?mod=view&id=' . $parameters[2]['patient']);
            return true;
        }
            if( isset($parameters[0]['cmd']) && $parameters[0]['cmd']=='add'){
            $patient=Application::$app->session->get('patient');
                $AppointmentModel= new Appointment();
                $number=$AppointmentModel->customFetchAll("select max(queue_no) from appointment where opened_channeling_ID=".$parameters[1]['id']);
                if($number[0]['max(queue_no)']>0){
                    $number=$number[0]['max(queue_no)']+1;
                }
                else{
                    $number=1;
                }
                if(isSet($parameters[2]['type']) && $parameters[2]['type']=='consultation'){
                    $appointment_id=$AppointmentModel->setAppointment([$opened_channeling_id,$patient,$number,"pending",'consultation']);
                    $OpenedChannelingModel->decreasePatientNumber($opened_channeling_id);
                    $PaymentModel->createAppointmenPay(Application::$app->session->get('patient'),'appointment',$AppointmentModel->getFee($appointment_id[0]['last_insert_id()']),$appointment_id[0]['last_insert_id()'],'pending');
                    Application::$app->response->redirect("receptionist-patient-appointment?mod=referral&id=".$appointment_id[0]['last_insert_id()']);
                }
                else if(isSet($parameters[2]['type']) && $parameters[2]['type']??''=='labtest'){
                    $appointment_id=$AppointmentModel->setAppointment([$opened_channeling_id,$patient,$number,"pending",'labtest']);
                    $OpenedChannelingModel->decreasePatientNumber($opened_channeling_id);
                    Application::$app->response->redirect("receptionist-patient-appointment?mod=referral&id=".$appointment_id[0]['last_insert_id()']);
                }
                else{
                    $appointment_id=$AppointmentModel->setAppointment([$opened_channeling_id,$patient,$number,"pending",'consultation']);
                    $OpenedChannelingModel->decreasePatientNumber($opened_channeling_id);
                    $PaymentModel->createAppointmenPay(Application::$app->session->get('patient'),'appointment',$AppointmentModel->getFee($appointment_id[0]['last_insert_id()']),$appointment_id[0]['last_insert_id()'],'pending');
                    Application::$app->response->redirect("receptionist-patient-appointment?mod=referral&id=".$appointment_id[0]['last_insert_id()']);
                }
            }
        

    }

    public function patientInformation(Request $request)
    {
        $this->setLayout('receptionist', ['select' => 'Patients']);
        $parameters = $request->getParameters();
        $AppointmentModel = new Appointment();
        $appointments = $AppointmentModel->customFetchAll("SELECT * from appointment left join opened_channeling on opened_channeling.opened_channeling_ID=appointment.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join doctor on doctor.nic=channeling.doctor left join employee on employee.nic=doctor.nic where appointment.patient_ID=" . $parameters[1]['id']);
        if (isset($parameters[0]['mod']) && $parameters[0]['mod'] == 'view') {
            return $this->render("receptionist/patient-appointment-list", [
                'appointments' => $appointments
            ]);
        }
    }

    public function viewPersonalDetails()
    {
        $this->setLayout("receptionist", ['select' => 'My Detail']);
        $userModel = new Employee();
        $userinfo = $userModel->customFetchAll("SELECT * FROM employee WHERE email=" . '"' . Application::$app->session->get('user') . '"');
        return $this->render('receptionist/receptionist-view-personal-details', [
            'userinfo' => $userinfo[0]
        ]);
    }


    public function handleReceptionist(Request $request, Response $response)
    {
        $this->setLayout("receptionist", ['select' => 'My Detail']);
        $parameters = $request->getParameters();
        $userModel = new Employee();

        if (isset($parameters[0]['mod']) && $parameters[0]['mod'] == 'update') {
            $userinfo = $userModel->customFetchAll("SELECT * from employee where email=" . "'" . $parameters[1]['id'] . "'");
            // var_dump($userinfo);
            // exit;
            $userModel->updateData($userinfo, $userModel->fileDestination());
            Application::$app->session->set('userinfo', $parameters[1]['id']);
            return $this->render('receptionist/receptionist-personal-detail-update', [
                'model' => $userModel,
                'userinfo' => $userinfo[0]
            ]);
        }
        if ($request->isPost()) {
            // update employee
            $userModel->loadData($request->getBody());
            $userModel->loadFiles($_FILES);
            if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'update') {
                if ($userModel->validate() && $userModel->updateRecord(['email' => $parameters[1]['id']])) {
                    $response->redirect('/ctest/receptionist-view-personal-details');
                    Application::$app->session->setFlash('success', "Receptionist Personal Detail successfully updated ");
                    $response->redirect('/ctest/receptionist-view-personal-details');
                    exit;
                };
            }
        }
        return $this->render('receptionist/receptionist-view-personal-details', [
            'model' => $userModel,
        ]);
    }


 
    public function allChannelingType(Request $request)
    {
        $channelingModel = new Channeling();
        $parameters = $request->getParameters();
        $this->setLayout("receptionist", ['select' => 'All Channelings']);
        $channelings = $channelingModel->customFetchAll("SELECT employee.age,employee.name,employee.emp_ID,employee.img,doctor.description,doctor.career_speciality from employee  join doctor on employee.nic = doctor.nic where doctor.career_speciality=" . "'" . $parameters[0]['id'] . "' ");
        $channelingSp = $channelingModel->customFetchAll("SELECT * from doctor where career_speciality=" . "'" . $parameters[0]['id'] . "' ");
        ;
        return $this->render('receptionist/receptionist-all-channeling-type', [
            'channelings' => $channelings,
            'channelingSp' => $channelingSp[0]
        ]);
    }

    public function allChannelings(Request $request, Response $response)
    {
        $this->setLayout("receptionist", ['select' => 'All Channelings']);

        $channelingModel = new Channeling();
        $parameters = $request->getParameters();
        $channelingmore = $channelingModel->customFetchAll("SELECT distinct career_speciality from doctor ");        //pass the variable value
        // var_dump($channelingmore);
        // exit;
        return $this->render('receptionist/receptionist-all-channelings', [
            'channelingmore' => $channelingmore
        ]);
    }

    public function todayChannelings(Request $request)
    {
        // echo 'test';
        $channelingModel = new Channeling();
        $parameters = $request->getParameters();

        $this->setLayout("receptionist", ['select' => 'Today Channelings']);
        $channelings = $channelingModel->customFetchAll("SELECT * from employee  inner join channeling on employee.nic = channeling.doctor inner 
join doctor  on channeling.doctor=doctor.nic 
left join opened_channeling on channeling.channeling_ID=opened_channeling.channeling_ID where opened_channeling.channeling_date=CURRENT_DATE ");

        return $this->render('receptionist/receptionist-today-channelings', [
            'channelings' => $channelings
        ]);
    }


    // all session detail
    public function sessionDetail(Request $request, Response $response)
    {
        $channelingModel = new channeling();
        $PatientModel = new patient();
        $parameters = $request->getParameters();
        $this->setLayout("receptionist", ['select' => 'All Channelings']);


        $channelingSession = $channelingModel->customFetchAll("SELECT * from employee  left join channeling on employee.nic = channeling.doctor left join opened_channeling on channeling.channeling_ID=opened_channeling.channeling_ID where employee.emp_ID=" . $parameters[0]['id']);
        $channelingPatient = $PatientModel->customFetchAll("SELECT patient.patient_ID,patient.name,patient.age from employee
        join channeling on employee.nic=channeling.doctor
        join opened_channeling on opened_channeling.channeling_ID=channeling.channeling_ID
        join appointment on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID
        join patient on patient.patient_ID=appointment.patient_ID where employee.emp_ID=" . $parameters[0]['id']);
        return $this->render('receptionist/receptionist-channeling-session-detail', [
            'channelingSession' => $channelingSession[0],
            'channelingPatient' => $channelingPatient,

        ]);
    }
    //today session detail
    public function todaysessionDetail(Request $request, Response $response)
    {
        $channelingModel = new channeling();
        $PatientModel = new patient();
        $parameters = $request->getParameters();
        $this->setLayout("receptionist", ['select' => 'Today Channelings']);


        $channelingSession = $channelingModel->customFetchAll("SELECT * from employee  inner join channeling on employee.nic = channeling.doctor inner join opened_channeling on channeling.channeling_ID=opened_channeling.channeling_ID where employee.emp_ID=" . $parameters[0]['id']);
        $channelingPatient = $PatientModel->customFetchAll("SELECT patient.patient_ID,patient.name,patient.age,opened_channeling.remaining_free_appointments from employee
        join channeling on employee.nic=channeling.doctor
        join opened_channeling on opened_channeling.channeling_ID=channeling.channeling_ID
        join appointment on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID
        join patient on patient.patient_ID=appointment.patient_ID where employee.emp_ID=" . $parameters[0]['id']);
        return $this->render('receptionist/receptionist-todays-channeling-session-detail', [
            'channelingSession' => $channelingSession[0],
            'channelingPatient' => $channelingPatient,


        ]);
    }
    // all channeling
    public function channelingMore(Request $request, Response $response)
    {
        $this->setLayout("receptionist", ['select' => 'All Channelings']);

        $channelingModel = new Channeling();
        $parameters = $request->getParameters();
        $channelingmore = $channelingModel->customFetchAll("SELECT * from employee left join doctor on employee.nic = doctor.nic left join channeling on doctor.nic=channeling.doctor where employee.emp_ID=" . $parameters[0]['id']);        //pass the variable value
        return $this->render('receptionist/receptionist-channeling-more', [
            'channelingmore' => $channelingmore
        ]);
    }
    // todays channeling
    public function todayschannelingMore(Request $request, Response $response)
    {
        $this->setLayout("receptionist", ['select' => 'Today Channelings']);

        $channelingModel = new Channeling();
        $parameters = $request->getParameters();
        $channelingmore = $channelingModel->customFetchAll("SELECT * from employee inner join doctor on employee.nic = doctor.nic inner join channeling on doctor.nic=channeling.doctor where employee.emp_ID=" . $parameters[0]['id']);        //pass the variable value
        return $this->render('receptionist/receptionist-todays-channeling-more', [
            'channelingmore' => $channelingmore
        ]);
    }
  



    // patient detail in all channeling
    public function patientMoreDetail(Request $request, Response $response)
    {
        $this->setLayout("receptionist", ['select' => 'All Channelings']);

        $channelingModel = new Channeling();
        $PatientModel = new patient();
        $parameters = $request->getParameters();


        if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'delete') {

            $channelingModel->deleteRecord(['patient_ID' => $parameters[1]['id']]);
            // echo 'done';
            // exit;
            Application::$app->session->setFlash('success', "Patient appointmet successfully deleted ");
            $response->redirect('/ctest/receptionist-channeling-session-patient-detail-more');
            return true;
        }

        $channelings = $channelingModel->customFetchAll("SELECT patient.patient_ID,doctor.career_speciality,employee.name,channeling.day,channeling.time,channeling.fee from employee
        join doctor on doctor.nic=employee.nic
        join channeling on doctor.nic=channeling.doctor
        join opened_channeling on opened_channeling.channeling_ID=channeling.channeling_ID
        join appointment on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID
        join patient on patient.patient_ID=appointment.patient_ID where patient.patient_ID=" . $parameters[0]['id']);
        $PatientDetail = $PatientModel->customFetchAll("SELECT * from patient where patient_ID=" . $parameters[0]['id']);
        return $this->render('receptionist/receptionist-channeling-session-patient-detail-more', [
            'channelings' => $channelings,
            'PatientDetail' => $PatientDetail[0]
        ]);
    }

    public function todayspatientMoreDetail(Request $request, Response $response)
    {
        $channelingModel = new Channeling();
        $PatientModel = new patient();
        $parameters = $request->getParameters();


        if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'delete') {

            $channelingModel->deleteRecord(['patient_ID' => $parameters[1]['id']]);
            // echo 'done';
            // exit;
            Application::$app->session->setFlash('success', "Patient appointmet successfully deleted ");
            $response->redirect('/ctest/receptionist-channeling-session-patient-detail-more');
            return true;
        }

        $channelings = $channelingModel->customFetchAll("SELECT patient.patient_ID,doctor.career_speciality,employee.name,channeling.day,channeling.time,channeling.fee from employee
        join doctor on doctor.nic=employee.nic
        join channeling on doctor.nic=channeling.doctor
        join opened_channeling on opened_channeling.channeling_ID=channeling.channeling_ID
        join appointment on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID
        join patient on patient.patient_ID=appointment.patient_ID where patient.patient_ID=" . $parameters[0]['id']);
        $PatientDetail = $PatientModel->customFetchAll("SELECT * from patient where patient_ID=" . $parameters[0]['id']);
        return $this->render('receptionist/receptionist-channeling-todays-session-patient-detail-more', [
            'channelings' => $channelings,
            'PatientDetail' => $PatientDetail[0]
        ]);
    }

    public function setAppointment(Request $request)
    {
        $channelingModel = new Channeling();
        // $PatientModel = new patient();

        $parameters = $request->getParameters();
        $channelingset = $channelingModel->customFetchAll("SELECT patient.patient_ID,patient.age,doctor.speciality,patient.name from employee
        join doctor on doctor.nic=employee.nic
        join channeling on doctor.nic=channeling.doctor
        join opened_channeling on opened_channeling.channeling_ID=channeling.channeling_ID
        join appointment on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID
        join patient on patient.patient_ID=appointment.patient_ID where patient.patient_ID=" . $parameters[0]['id']);
        // $PatientDetail = $PatientModel->customFetchAll("SELECT * from patient where patient_ID=" . $parameters[0]['id']);


        return $this->render('receptionist/receptionist-channeling-set-appointment', [
            'channelingset' => $channelingset[0],
            // 'PatientDetail'=>$PatientDetail
        ]);
    }
 
    public function handlePayment(Request $request)
    {
        $channelingModel = new Channeling();
        $parameters = $request->getParameters();


        return $this->render('receptionist/receptionist-channeling-payment', []);
    }
   
}