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
use app\controllers\NurseController;

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
// $app->router->get('/ctest/nurse',[SiteController::class, 'doctor']);
$app->router->get('/ctest/nurse',[NurseController::class, 'viewAllClinics']);
$app->router->get('/ctest/my-detail',[NurseController::class, 'viewUserDetails']);
$app->router->get('/ctest/all-channelings',[NurseController::class, 'viewAllClinics']);
$app->router->get('/ctest/all-channeling-more',[NurseController::class, 'viewAllClinicsMore']);
$app->router->get('/ctest/today-channelings',[NurseController::class, 'todayClinics']);
$app->router->get('/ctest/all-channeling-session',[NurseController::class,'viewChanneling']);
$app->router->post('/ctest/all-channeling-session',[NurseController::class,'viewChanneling']);


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
$app->router->get('/ctest/main-adds', [AdminController::class, 'viewAdvertisement']);
$app->router->get('/ctest/handle-advertisement', [AdminController::class, 'handleAdvertisement']);
$app->router->post('/ctest/handle-advertisement', [AdminController::class, 'handleAdvertisement']);
$app->router->get('/ctest/update-advertisement', [AdminController::class, 'handleAdvertisement']);
$app->router->post('/ctest/update-advertisement', [AdminController::class, 'handleAdvertisement']);
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

// $app->router->post('/ctest/patient',[PatientAuthController::class,'mainPage']);


$app->run();