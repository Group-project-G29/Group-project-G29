<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Colombo");
require_once __DIR__.'/../vendor/autoload.php';


require_once 'dompdf/autoload.inc.php';
require_once 'notify-php-master/autoload.php';
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
use app\controllers\Advertisement;
use app\controllers\DeliveryController;
use app\controllers\NurseController;
use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Initialize application
$app =new Application(dirname(__DIR__));

// Routers
//patient

$app->router->get('/ctest/contact-us',[PatientAuthController::class,'contact_us']);
$app->router->get('/ctest/patient-lab-main',[PatientAuthController::class,'labPage']);
$app->router->post('/ctest/patient-lab-main',[PatientAuthController::class,'labPage']);



$app->router->get('/ctest/patient-registration',[PatientAuthController::class,'register']);
$app->router->post('/ctest/patient-registration',[PatientAuthController::class,'register']);
$app->router->get('/ctest/pediatric-registration',[PatientAuthController::class,'registerPediatric']);
$app->router->post('/ctest/pediatric-registration',[PatientAuthController::class,'registerPediatric']);
$app->router->get('/ctest/',[PatientAuthController::class,'login']);
$app->router->post('/ctest/',[PatientAuthController::class,'login']);
$app->router->get('/ctest/pediatric',[PatientAuthController::class,'pedlogin']);
$app->router->post('/ctest/pediatric',[PatientAuthController::class,'pedlogin']);
$app->router->get('/ctest/patient-main',[PatientAuthController::class,'mainPage']);
$app->router->get('/ctest/patient-appointment',[PatientAuthController::class,'handleAppointments']);
$app->router->get('/ctest/patient-channeling-category-view',[PatientAuthController::class,'channelingView']);
$app->router->get('/ctest/patient-all-appointment',[PatientAuthController::class,'viewAppointments']);
$app->router->get('/ctest/handle-referral',[PatientAuthController::class,'handleReferral']);
$app->router->post('/ctest/handle-referral',[PatientAuthController::class,'handleReferral']);
$app->router->get('/ctest/handle-appointment',[PatientAuthController::class,'handleAppointments']);
$app->router->get('/ctest/logout',[PatientAuthController::class,'logout']);
$app->router->get('/ctest/doctor-patient-appointment', [PatientAuthController::class, 'doctorAppointment']);
$app->router->get('/ctest/patient-pharmacy',[PatientAuthController::class,'medicineOrder']);
$app->router->post('/ctest/patient-pharmacy',[PatientAuthController::class,'medicineOrder']);
$app->router->get('/ctest/patient-medicine-order',[PatientAuthController::class,'orderMedicine']);
$app->router->post('/ctest/patient-medicine-order',[PatientAuthController::class,'orderMedicine']);
$app->router->get('/ctest/patient-dashboard',[PatientAuthController::class,'patientDashboard']);
$app->router->post('/ctest/patient-dashboard',[PatientAuthController::class,'patientDashboard']);
$app->router->get('/ctest/patient-payment',[PatientAuthController::class,'patientPayment']);
$app->router->post('/ctest/patient-payment',[PatientAuthController::class,'patientPayment']);
$app->router->get('/ctest/register', [PatientAuthController::class, 'register']);
$app->router->post('/ctest/register', [PatientAuthController::class, 'register']);
$app->router->get('/ctest/handle-documentation', [PatientAuthController::class, 'handleDocuments']);
$app->router->get('/ctest/handle-labreports', [PatientAuthController::class, 'handelLabReports']);
$app->router->get('/ctest/patient-my-detail', [PatientAuthController::class,'accountHandle']);
$app->router->post('/ctest/patient-my-detail', [PatientAuthController::class,'accountHandle']);
$app->router->get('/ctest/nic', [PatientAuthController::class,'getNIC']);
$app->router->post('/ctest/nic', [PatientAuthController::class,'getNIC']);
$app->router->get('/ctest/otp', [PatientAuthController::class,'OTP']);
$app->router->post('/ctest/otp', [PatientAuthController::class,'OTP']);
$app->router->get('/ctest/testme', [PatientAuthController::class,'test']);



//--------------------employee routers--------------------------------------
$app->router->get('/ctest/login',[EmployeeAuthController::class, 'login']);
$app->router->post('/ctest/login',[EmployeeAuthController::class, 'login']);
$app->router->get('/ctest/login',[EmployeeAuthController::class, 'login']);
$app->router->post('/ctest/login',[EmployeeAuthController::class, 'login']);
$app->router->get('/ctest/employee-logout',[EmployeeAuthController::class,'logout']);
$app->router->get('/ctest/employee-nic', [EmployeeAuthController::class,'getNIC']);
$app->router->post('/ctest/employee-nic', [EmployeeAuthController::class,'getNIC']);
$app->router->get('/ctest/employee-otp', [EmployeeAuthController::class,'OTP']);
$app->router->post('/ctest/employee-otp', [EmployeeAuthController::class,'OTP']);

// -----------------------nurse routers-------------------------------------
// $app->router->get('/ctest/nurse',[SiteController::class, 'doctor']);
$app->router->get('/ctest/nurse',[NurseController::class, 'todayClinics']);
$app->router->get('/ctest/nurse',[NurseController::class, 'channelingCategoriesView']);
$app->router->get('/ctest/my-details',[NurseController::class, 'viewUserDetails']);
$app->router->get('/ctest/all-channelings',[NurseController::class, 'channelingCategoriesView']);
$app->router->get('/ctest/all-channeling-more',[NurseController::class, 'viewAllClinicsMore']);
$app->router->get('/ctest/today-channelings',[NurseController::class, 'todayClinics']);
$app->router->get('/ctest/all-channeling-session',[NurseController::class,'viewChanneling']);
$app->router->post('/ctest/all-channeling-session',[NurseController::class,'viewChanneling']);
$app->router->get('/ctest/nurse-list-patient',[NurseController::class,'viewSessionPatients']);
$app->router->post('/ctest/nurse-list-patient',[NurseController::class,'viewSessionPatients']);
$app->router->get('/ctest/nurse-channeling-allocation',[NurseController::class,'viewChannelingAllocation']);
$app->router->post('/ctest/nurse-patient-test-value-save',[NurseController::class,'addTestValue']);
$app->router->get('/ctest/nurse-patient-test-value-save',[NurseController::class,'addTestValue']);
$app->router->post('/ctest/nurse-patient-test-value-edit',[NurseController::class,'editTestValueUpdate']);
$app->router->get('/ctest/nurse-patient-test-value-edit',[NurseController::class,'editTestValueView']);
$app->router->get('/ctest/nurse-patient',[NurseController::class,'viewPatient']);
$app->router->get('/ctest/update-my-details',[NurseController::class, 'updateUserDetails']);
$app->router->post('/ctest/update-my-details',[NurseController::class, 'updateUserDetails']);




//-------------------doctor routers----------------------------------------
$app->router->get('/ctest/doctor',[DoctorController::class, 'todayChannelings']);
$app->router->get('/ctest/channeling',[DoctorController::class,'viewChanneling']);
$app->router->get('/ctest/channeling-assistance',[DoctorController::class,'sessionAssistance']);
$app->router->get('/ctest/doctor-report',[DoctorController::class,'handleReports']);
$app->router->post('/ctest/doctor-report',[DoctorController::class,'handleReports']);
$app->router->get('/ctest/doctor-prescription',[DoctorController::class,'handlePrescription']);
$app->router->post('/ctest/doctor-prescription',[DoctorController::class,'handlePrescription']);
$app->router->get('/ctest/recent-patients',[DoctorController::class,'viewPatient']);
$app->router->get('/ctest/doctor-labtest',[DoctorController::class,'labTestRequestHandle']);
$app->router->post('/ctest/doctor-labtest',[DoctorController::class,'labTestRequestHandle']);
$app->router->get('/ctest/summary-reports',[DoctorController::class,'summaryReports']);
$app->router->post('/ctest/upload-reports',[DoctorController::class,'handleLabReports']);
$app->router->get('/ctest/notification',[DoctorController::class,'handleNotis']);
$app->router->post('/ctest/notification',[DoctorController::class,'handleNotis']);
$app->router->get('/ctest/doctor-my-detail',[DoctorController::class,'myDetail']);
$app->router->post('/ctest/doctor-my-detail',[DoctorController::class,'myDetail']);
$app->router->get('/ctest/doctor-move',[DoctorController::class,'mover']);


//-------------------pharmacy routers-----------------------------------------
$app->router->post('/ctest/handle-medicine',[PharmacyController::class,'handleMedicine']);
$app->router->get('/ctest/handle-medicine',[PharmacyController::class,'handleMedicine']);
$app->router->get('/ctest/pharmacy-view-medicine',[PharmacyController::class,'viewMedicine']);
$app->router->post('/ctest/pharmacist',[PharmacyController::class,'viewMedicine']);
$app->router->get('/ctest/pharmacist',[PharmacyController::class,'viewMedicine']);
$app->router->post('/ctest/update-medicine',[PharmacyController::class,'handleMedicine']);
$app->router->get('/ctest/update-medicine',[PharmacyController::class,'handleMedicine']);
$app->router->get('/ctest/pharmacy-view-personal-details',[PharmacyController::class,'viewPersonalDetails']);
$app->router->post('/ctest/pharmacy-view-personal-details',[PharmacyController::class,'viewPersonalDetails']);
$app->router->get('/ctest/pharmacy-update-personal-details',[PharmacyController::class,'editPersonalDetails']);
$app->router->post('/ctest/pharmacy-update-personal-details',[PharmacyController::class,'editPersonalDetails']);
$app->router->get('/ctest/pharmacy-view-advertisement',[PharmacyController::class,'viewAdvertisement']);
$app->router->post('/ctest/pharmacy-view-advertisement',[PharmacyController::class,'viewAdvertisement']);
$app->router->post('/ctest/pharmacy-update-advertisement',[PharmacyController::class,'handleAdvertisement']);
$app->router->get('/ctest/pharmacy-update-advertisement',[PharmacyController::class,'handleAdvertisement']);
$app->router->post('/ctest/pharmacy-handle-advertisement',[PharmacyController::class,'handleAdvertisement']);
$app->router->get('/ctest/pharmacy-handle-advertisement',[PharmacyController::class,'handleAdvertisement']);

$app->router->get('/ctest/pharmacy-front-orders-pending',[PharmacyController::class,'viewFrontdeskPendingOrder']);
$app->router->get('/ctest/pharmacy-front-orders-packed',[PharmacyController::class,'viewFrontdeskPackedOrder']);
$app->router->get('/ctest/pharmacy-front-orders-finished',[PharmacyController::class,'viewFrontdeskFinishedOrder']);
$app->router->get('/ctest/pharmacy-view-front-orders-pending',[PharmacyController::class,'detailsFrontdeskPending']);
$app->router->get('/ctest/pharmacy-view-front-orders-packed',[PharmacyController::class,'detailsFrontdeskPacked']);
$app->router->get('/ctest/pharmacy-view-front-orders-finished',[PharmacyController::class,'detailsFrontdeskFinished']);

$app->router->get('/ctest/pharmacy-new-front-items',[PharmacyController::class,'addNewFrontItem']);
$app->router->post('/ctest/pharmacy-new-front-items',[PharmacyController::class,'addNewFrontItem']);
$app->router->get('/ctest/pharmacy-finish-front-processing-order',[PharmacyController::class,'finishFrontdeskOrder']);
$app->router->get('/ctest/pharmacy-delete-front-processing-order',[PharmacyController::class,'deleteFrontdeskOrder']);
$app->router->get('/ctest/pharmacy-frontdesk-cancle-order',[PharmacyController::class,'cancleFrontdeskOrder']);
$app->router->get('/ctest/pharmacy-frontdesk-pickup-order',[PharmacyController::class,'pickupFrontdeskOrder']);

$app->router->get('/ctest/pharmacy-new-order',[PharmacyController::class,'createNewFrontdeskOrder']);
$app->router->post('/ctest/pharmacy-new-order',[PharmacyController::class,'createNewFrontdeskOrder']);

$app->router->get('/ctest/pharmacy-view-previous-order',[PharmacyController::class,'DetailsPreviousOrder']);
$app->router->get('/ctest/pharmacy-orders-previous',[PharmacyController::class,'viewPreviousOrder']);
$app->router->get('/ctest/pharmacy-orders-pending',[PharmacyController::class,'viewPendingOrder']);
$app->router->get('/ctest/pharmacy-view-pending-order',[PharmacyController::class,'DetailsPendingOrder']);
$app->router->get('/ctest/pharmacy-take-pending-order',[PharmacyController::class,'TakePendingOrder']);
$app->router->get('/ctest/pharmacy-delete-rejected',[PharmacyController::class,'deleteRejectedOrder']);
// $app->router->post('/ctest/pharmacy-pharmacy-delete-rejected',[PharmacyController::class,'deleteRejectedOrder']);
// $app->router->post('/ctest/pharmacy-view-pending-order',[PharmacyController::class,'TakePendingOrder']);
// $app->router->post('/ctest/pharmacy-take-pending-order',[PharmacyController::class,'TakePendingOrder']);
$app->router->get('/ctest/pharmacy-orders-processing',[PharmacyController::class,'viewProcessingOrder']);
$app->router->get('/ctest/pharmacy-view-processing-order',[PharmacyController::class,'DetailsProcessingOrder']);
$app->router->get('/ctest/pharmacy-notify-processing-order',[PharmacyController::class,'notifyProcessingOrder']);
$app->router->get('/ctest/pharmacy-finish-processing-order',[PharmacyController::class,'finishProcessingOrder']);
$app->router->get('/ctest/pharmacy-cancle-processing-order',[PharmacyController::class,'cancleProcessingOrder']);
$app->router->get('/ctest/pharmacy-add-medicine-processing-order',[PharmacyController::class,'addMedicineProcessingOrder']);
$app->router->get('/ctest/pharmacy-orders-delivering',[PharmacyController::class,'viewDeliveringOrder']);
$app->router->get('/ctest/pharmacy-track-order',[PharmacyController::class,'trackOrder']);
$app->router->post('/ctest/pharmacy-track-order',[PharmacyController::class,'trackOrder']);
$app->router->get('/ctest/pharmacy-go-to-process-order',[PharmacyController::class,'processOrderAgain']);
$app->router->get('/ctest/pharmacy-picked-up-order',[PharmacyController::class,'pickupOrder']);
$app->router->post('/ctest/pharmacy-picked-up-order',[PharmacyController::class,'pickupOrder']);

$app->router->get('/ctest/pharmacy-new-order-items',[PharmacyController::class,'addNewOrderItem']);
$app->router->post('/ctest/pharmacy-new-order-items',[PharmacyController::class,'addNewOrderItem']);

$app->router->get('/ctest/view-softcopy',[PharmacyController::class,'viewSoftcopy']);
$app->router->get('/ctest/pharmacy-delete-pres-med',[PharmacyController::class,'deleteOrderItem']);
$app->router->get('/ctest/pharmacy-delete-front-med',[PharmacyController::class,'deleteFrontOrderItem']);

$app->router->get('/ctest/pharmacy-view-report',[PharmacyController::class,'viewReports']);
$app->router->get('/ctest/pharmacy-view-personal-details',[PharmacyController::class,'viewPersonalDetails']);


// ------------------delivery rider routers----------------------------------------
$app->router->get('/ctest/delivery-view-personal-details',[DeliveryController::class,'viewPersonalDetails']);
$app->router->post('/ctest/delivery-view-personal-details',[DeliveryController::class,'viewPersonalDetails']);
$app->router->get('/ctest/delivery-my-deliveries',[DeliveryController::class,'viewMyDeliveries']);
$app->router->get('/ctest/delivery-pending-deliveries',[DeliveryController::class,'viewPendingDeliveries']);
$app->router->get('/ctest/delivery-all-deliveries',[DeliveryController::class,'viewAllDeliveries']);
$app->router->get('/ctest/delivery-view-delivery',[DeliveryController::class,'viewDeliveryDetails']);
// $app->router->post('/ctest/delivery-view-delivery',[DeliveryController::class,'completeDelivery']);
$app->router->get('/ctest/delivery-complete',[DeliveryController::class,'completeDelivery']);
$app->router->post('/ctest/delivery-complete',[DeliveryController::class,'completeDelivery']);
$app->router->get('/ctest/delivery-pass-delivery',[DeliveryController::class,'passDelivery']);
$app->router->get('/ctest/delivery-get-delivery',[DeliveryController::class,'getDelivery']);
$app->router->get('/ctest/delivery-update-personal-details',[DeliveryController::class,'editPersonalDetails']);
$app->router->post('/ctest/delivery-update-personal-details',[DeliveryController::class,'editPersonalDetails']);

// active status
$app->router->get('/ctest/delivery-online',[DeliveryController::class,'makeOnline']);
$app->router->get('/ctest/delivery-offline',[DeliveryController::class,'makeOffline']);


// ------------------laboratorist routers----------------------------------------
$app->router->get('/ctest/test',[LabController::class,'viewTest']);



//--------------------receptionist routers----------------------------------------
$app->router->get('/ctest/patient-detail',[ReceptionistController::class,'handlePatient']);
$app->router->post('/ctest/patient-detail',[ReceptionistController::class,'handlePatient']);


$app->router->get('/ctest/receptionist-handle-patient',[ReceptionistController::class,'handlePatient']);
$app->router->post('/ctest/receptionist-handle-patient',[ReceptionistController::class,'handlePatient']);
$app->router->get('/ctest/receptionist-patient-appointment',[ReceptionistController::class,'handleAppointments']);
$app->router->post('/ctest/receptionist-patient-appointment',[ReceptionistController::class,'handleAppointments']);
$app->router->get('/ctest/receptionist-patient-information',[ReceptionistController::class,'patientInformation']);
$app->router->post('/ctest/receptionist-view-personal-details',[ReceptionistController::class,'viewPersonalDetails']);
$app->router->get('/ctest/receptionist-view-personal-details',[ReceptionistController::class,'viewPersonalDetails']);
$app->router->get('/ctest/receptionist-view-personal-details',[ReceptionistController::class,'viewPersonalDetails']);
$app->router->post('/ctest/receptionist-view-personal-details',[ReceptionistController::class,'viewPersonalDetails']);
$app->router->get('/ctest/receptionist-personal-detail-update',[ReceptionistController::class,'handleReceptionist']);
$app->router->post('/ctest/receptionist-personal-detail-update',[ReceptionistController::class,'handleReceptionist']);


$app->router->get('/ctest/receptionist-all-channelings',[ReceptionistController::class,'allChannelings']);
$app->router->get('/ctest/receptionist-all-channeling-type',[ReceptionistController::class,'allChannelingType']);

$app->router->get('/ctest/receptionist-all-channelings',[ReceptionistController::class,'allChannelings']);
$app->router->get('/ctest/receptionist-channeling-more',[ReceptionistController::class,'channelingMore']);
$app->router->get('/ctest/receptionist-todays-channeling-more',[ReceptionistController::class,'todayschannelingMore']);

$app->router->get('/ctest/receptionist-channeling-session-detail',[ReceptionistController::class,'sessionDetail']);
$app->router->post('/ctest/receptionist-channeling-session-detail',[ReceptionistController::class,'sessionDetail']);

$app->router->get('/ctest/receptionist-todays-channeling-session-detail',[ReceptionistController::class,'todaysessionDetail']);
$app->router->post('/ctest/receptionist-todays-channeling-session-detail',[ReceptionistController::class,'todaysessionDetail']);
$app->router->get('/ctest/receptionist-channeling-session-patient-detail',[ReceptionistController::class,'patientDetail']);
$app->router->post('/ctest/receptionist-channeling-session-patient-detail',[ReceptionistController::class,'patientDetail']);
$app->router->get('/ctest/receptionist-channeling-session-patient-detail-more',[ReceptionistController::class,'patientMoreDetail']);
$app->router->post('/ctest/receptionist-channeling-session-patient-detail-more',[ReceptionistController::class,'patientMoreDetail']);
$app->router->get('/ctest/receptionist-channeling-todays-session-patient-detail-more',[ReceptionistController::class,'todayspatientMoreDetail']);
$app->router->post('/ctest/receptionist-channeling-todays-session-patient-detail-more',[ReceptionistController::class,'todayspatientMoreDetail']);
$app->router->get('/ctest/receptionist-channeling-set-appointment',[ReceptionistController::class,'setAppointment']);
$app->router->get('/ctest/receptionist-channeling-payment',[ReceptionistController::class,'handlePayment']);
$app->router->get('/ctest/receptionist-today-channelings',[ReceptionistController::class,'todayChannelings']);

$app->router->get('/ctest/receptionist-all-payment-done',[ReceptionistController::class,'allPaymentDone']);
$app->router->get('/ctest/receptionist-all-payment-notdone',[ReceptionistController::class,'allPaymentNotdone']);

// --------------------------------administrator controllers-----------------------------------------
$app->router->get('/ctest/main-adds', [AdminController::class, 'viewAdvertisement']);
$app->router->get('/ctest/admin-handle-advertisement', [AdminController::class, 'handleAdvertisement']);
$app->router->post('/ctest/admin-handle-advertisement', [AdminController::class, 'handleAdvertisement']);
$app->router->get('/ctest/admin-update-advertisement', [AdminController::class, 'handleAdvertisement']);
$app->router->post('/ctest/admin-update-advertisement', [AdminController::class, 'handleAdvertisement']);
$app->router->get('/ctest/admin-all-channelings',[AdminController::class,'channelingSessionsView']);
$app->router->get('/ctest/schedule-channeling',[AdminController::class,'schedulingChanneling']);
$app->router->post('/ctest/schedule-channeling',[AdminController::class,'schedulingChanneling']);
$app->router->get('/ctest/admin-notification',[AdminController::class,'handleNotifications']);
$app->router->get('/ctest/update-channeling',[AdminController::class,'changeChanneling']);
$app->router->post('/ctest/update-channeling',[AdminController::class,'changeChanneling']);
$app->router->get('/ctest/admin-reports',[AdminController::class,'viewReports']);
$app->router->get('/ctest/test1',[AdminController::class,'test']);


//-------------------administrator routers--------------------------------------
$app->router->get('/ctest/admin',[AdminController::class,'registerAccounts']);
$app->router->post('/ctest/admin',[AdminController::class,'registerAccounts']);
$app->router->get('/ctest/schedule-channeling',[AdminController::class,'schedulingChanneling']);
$app->router->post('/ctest/schedule-channeling',[AdminController::class,'schedulingChanneling']);


// ---------------------------------lab routes-----------------------------------------
$app->router->get('/ctest/lab-view-personal-details',[LabController::class,'viewPersonalDetails']);
$app->router->post('/ctest/lab-view-personal-details',[LabController::class,'viewPersonalDetails']);
$app->router->get('/ctest/lab-personal-detail-update',[LabController::class,'handleLab']);
$app->router->post('/ctest/lab-personal-detail-update',[LabController::class,'handleLab']);
$app->router->get('/ctest/lab-view-all-test',[LabController::class,'viewTest']);
$app->router->post('/ctest/lab-view-all-test',[LabController::class,'viewTest']);
$app->router->get('/ctest/lab-add-new-test',[LabController::class,'handleTest']);
$app->router->post('/ctest/lab-add-new-test',[LabController::class,'handleTest']);
$app->router->get('/ctest/lab-test-update',[LabController::class,'handleTest']);
$app->router->post('/ctest/lab-test-update',[LabController::class,'handleTest']);
$app->router->get('/ctest/lab-test-delete',[LabController::class,'handleTest']);
$app->router->post('/ctest/lab-test-delete',[LabController::class,'handleTest']);
$app->router->get('/ctest/lab-add-new-template',[LabController::class,'handleTemplate']);
$app->router->post('/ctest/lab-add-new-template',[LabController::class,'handleTemplate']);

$app->router->get('/ctest/lab-test-request',[LabController::class,'testRequest']);
$app->router->post('/ctest/lab-test-request',[LabController::class,'testRequest']);
$app->router->get('/ctest/lab-write-test-result',[LabController::class,'writeResult']);
$app->router->post('/ctest/lab-write-test-result',[LabController::class,'writeResult']);
$app->router->get('/ctest/lab-view-all-report',[LabController::class,'viewReport']);
$app->router->post('/ctest/lab-view-all-report',[LabController::class,'viewReport']);
$app->router->get('/ctest/lab-edit-report-detail',[LabController::class,'editReport']);
$app->router->post('/ctest/lab-edit-report-detail',[LabController::class,'editReport']);

$app->router->get('/ctest/lab-test-request',[LabController::class,'testRequest']);
$app->router->post('/ctest/lab-test-request',[LabController::class,'testRequest']);
$app->router->get('/ctest/lab-write-test-result',[LabController::class,'writeResult']);
$app->router->post('/ctest/lab-write-test-result',[LabController::class,'writeResult']);
$app->router->get('/ctest/lab-view-all-report',[LabController::class,'viewReport']);
$app->router->post('/ctest/lab-view-all-report',[LabController::class,'viewReport']);
$app->router->get('/ctest/lab-view-report-detail',[LabController::class,'ReportDetail']);
$app->router->post('/ctest/lab-view-report-detail',[LabController::class,'ReportDetail']);
$app->router->get('/ctest/lab-report-upload',[LabController::class,'reportUpload']);
$app->router->post('/ctest/lab-report-upload',[LabController::class,'reportUpload']);
$app->router->get('/ctest/lab-write-test-report',[LabController::class,'writeReport']);
$app->router->post('/ctest/lab-write-test-report',[LabController::class,'writeReport']);
$app->router->get('/ctest/lab-test-template',[LabController::class,'createTemplate']);
$app->router->post('/ctest/lab-test-template',[LabController::class,'createTemplate']);
$app->router->get('/ctest/lab-template-content-edit',[LabController::class,'createTemplate']);
$app->router->post('/ctest/lab-template-content-edit',[LabController::class,'createTemplate']);
$app->router->get('/ctest/lab-view-all-template-more',[LabController::class,'viewTemplateMore']);
$app->router->post('/ctest/lab-view-all-template-more',[LabController::class,'viewTemplateMore']);
$app->router->get('/ctest/lab-view-all-template',[LabController::class,'viewTemplate']);
$app->router->post('/ctest/lab-view-all-template',[LabController::class,'viewTemplate']);
$app->router->get('/ctest/lab-view-advertisement',[LabController::class,'viewAdvertisement']);
$app->router->post('/ctest/lab-view-advertisement',[LabController::class,'viewAdvertisement']);
$app->router->post('/ctest/lab-update-advertisement',[LabController::class,'handleAdvertisement']);
$app->router->get('/ctest/lab-update-advertisement',[LabController::class,'handleAdvertisement']);
$app->router->post('/ctest/lab-handle-advertisement',[LabController::class,'handleAdvertisement']);
$app->router->get('/ctest/lab-handle-advertisement',[LabController::class,'handleAdvertisement']);
$app->router->get('/ctest/lab-S-report-upload',[LabController::class,'uploadSReport']);
$app->router->post('/ctest/lab-S-report-upload',[LabController::class,'uploadSReport']);


$app->run();