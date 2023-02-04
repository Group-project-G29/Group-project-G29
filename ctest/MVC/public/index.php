<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/../vendor/autoload.php';

use app\controllers\AdminController;
use \app\core\Application;
use \app\controllers\SiteController;
use \app\controllers\EmployeeAuthController;
use \app\controllers\DoctorController;
use app\controllers\LabController;
use app\controllers\PatientAuthController;
use app\controllers\PharmacyController;
use app\models\LabTest;
use app\controllers\ReceptionistController;

// Initialize application
$app =new Application(dirname(__DIR__));


// Routers
$app->router->get('/ctest/', [SiteController::class, 'home']);
$app->router->post('/ctest/', [SiteController::class, 'home']);
$app->router->get('/ctest/register', [PatientAuthController::class, 'register']);
$app->router->post('/ctest/register', [PatientAuthController::class, 'register']);
$app->router->get('/ctest/login',[EmployeeAuthController::class, 'login']);
$app->router->post('/ctest/login',[EmployeeAuthController::class, 'login']);
$app->router->get('/ctest/doctor',[DoctorController::class, 'todayChannelings']);
$app->router->post('/ctest/doctor',[SiteController::class, 'doctor']);
$app->router->get('/ctest/channeling',[DoctorController::class,'viewChanneling']);
$app->router->post('/ctest/channeling',[DoctorController::class,'viewChanneling']);
$app->router->get('/ctest/patient-registration',[PatientAuthController::class,'register']);
$app->router->post('/ctest/patient-registration',[PatientAuthController::class,'register']);
$app->router->get('/ctest/',[PatientAuthController::class,'login']);
$app->router->post('/ctest/',[PatientAuthController::class,'login']);
$app->router->get('/ctest/patient-main',[PatientAuthController::class,'mainPage']);
$app->router->get('/ctest/patient-appointment',[PatientAuthController::class,'handleAppointments']);
$app->router->get('/ctest/patient-channeling-category-view',[PatientAuthController::class,'channelingView']);
$app->router->get('/ctest/patient-all-appointment',[PatientAuthController::class,'viewAppointments']);
$app->router->post('/ctest/handle-medicine',[PharmacyController::class,'handleMedicine']);
$app->router->get('/ctest/handle-medicine',[PharmacyController::class,'handleMedicine']);
$app->router->get('/ctest/admin',[AdminController::class,'registerAccounts']);
$app->router->post('/ctest/admin',[AdminController::class,'registerAccounts']);
$app->router->get('/ctest/schedule-channeling',[AdminController::class,'schedulingChanneling']);
$app->router->post('/ctest/schedule-channeling',[AdminController::class,'schedulingChanneling']);
$app->router->post('/ctest/pharmacist',[PharmacyController::class,'viewMedicine']);
$app->router->get('/ctest/pharmacist',[PharmacyController::class,'viewMedicine']);
$app->router->post('/ctest/update-medicine',[PharmacyController::class,'handleMedicine']);
$app->router->get('/ctest/update-medicine',[PharmacyController::class,'handleMedicine']);
$app->router->get('/ctest/handle-appointment',[PatientAuthController::class,'handleAppointments']);
$app->router->get('/ctest/logout',[PatientAuthController::class,'logout']);
$app->router->get('/ctest/employee-logout',[EmployeeAuthController::class,'logout']);
$app->router->get('/ctest/test',[LabController::class,'viewTest']);
$app->router->get('/ctest/handle-referral',[PatientAuthController::class,'handleReferral']);
$app->router->post('/ctest/handle-referral',[PatientAuthController::class,'handleReferral']);
$app->router->get('/ctest/channeling-assistance',[DoctorController::class,'sessionAssistance']);
$app->router->get('/ctest/receptionist-handle-patient',[ReceptionistController::class,'handlePatient']);
$app->router->post('/ctest/receptionist-handle-patient',[ReceptionistController::class,'handlePatient']);
$app->router->get('/ctest/receptionist-patient-appointment',[ReceptionistController::class,'handleAppointments']);
$app->router->post('/ctest/receptionist-patient-appointment',[ReceptionistController::class,'handleAppointments']);
$app->router->get('/ctest/receptionist-patient-information',[ReceptionistController::class,'patientInformation']);
$app->router->post('/ctest/receptionist-patient-information',[ReceptionistController::class,'patientInformaton']);

$app->router->get('/ctest/receptionist-view-personal-details',[ReceptionistController::class,'viewPersonalDetails']);
$app->router->post('/ctest/receptionist-view-personal-details',[ReceptionistController::class,'viewPersonalDetails']);

$app->router->get('/ctest/receptionist-all-channelings',[ReceptionistController::class,'allChannelings']);
$app->router->get('/ctest/receptionist-channeling-more',[ReceptionistController::class,'channelingMore']);

$app->router->get('/ctest/receptionist-channeling-session-detail',[ReceptionistController::class,'sessionDetail']);
$app->router->post('/ctest/receptionist-channeling-session-detail',[ReceptionistController::class,'sessionDetail']);
$app->router->get('/ctest/receptionist-channeling-session-patient-detail',[ReceptionistController::class,'patientDetail']);
$app->router->post('/ctest/receptionist-channeling-session-patient-detail',[ReceptionistController::class,'patientDetail']);
$app->router->get('/ctest/receptionist-channeling-session-patient-detail-more',[ReceptionistController::class,'patientMoreDetail']);
$app->router->post('/ctest/receptionist-channeling-session-patient-detail-more',[ReceptionistController::class,'patientMoreDetail']);
$app->router->get('/ctest/receptionist-channeling-set-appointment',[ReceptionistController::class,'setAppointment']);
$app->router->get('/ctest/receptionist-channeling-payment',[ReceptionistController::class,'handlePayment']);
$app->router->get('/ctest/receptionist-today-channelings',[ReceptionistController::class,'todayChannelings']);

$app->router->get('/ctest/lab-view-personal-details',[LabController::class,'viewPersonalDetails']);
$app->router->get('/ctest/lab-view-all-test',[LabController::class,'viewTest']);
$app->router->post('/ctest/lab-view-all-test',[LabController::class,'viewTest']);
$app->router->get('/ctest/lab-add-new-test',[LabController::class,'handleTest']);
$app->router->post('/ctest/lab-add-new-test',[LabController::class,'handleTest']);
$app->router->get('/ctest/lab-test-update',[LabController::class,'handleTest']);
$app->router->post('/ctest/lab-test-update',[LabController::class,'handleTest']);
$app->router->get('/ctest/lab-test-delete',[LabController::class,'handleTest']);
$app->router->post('/ctest/lab-test-delete',[LabController::class,'handleTest']);
$app->router->get('/ctest/lab-test-request',[LabController::class,'testRequest']);
$app->router->post('/ctest/lab-test-request',[LabController::class,'testRequest']);
$app->router->get('/ctest/lab-report-upload',[LabController::class,'reportUpload']);
$app->router->post('/ctest/lab-report-upload',[LabController::class,'reportUpload']);







$app->router->get('/ctest/pharmacy-view-personal-details',[PharmacyController::class,'viewPersonalDetails']);

// $app->router->post('/ctest/patient',[PatientAuthController::class,'mainPage']);


$app->run();