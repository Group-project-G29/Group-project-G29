<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\Patient;
use app\models\User;
use app\core\DbModel;
use app\core\form\Select;
use app\core\Model;
use app\core\Response;
use app\models\Channeling;
use app\models\LabTest;
use app\models\Template;

use app\models\Medicine;
use app\models\Employee;
use app\models\TemplateContent;
use app\models\LabAdvertisement;
use app\models\LabContentAllocation;
use app\models\LabReport;
use app\models\LabTestRequest;

class LabController extends Controller
{
    //------------------test handle------------//
    public function handleTest(Request $request, Response $response)
    {
        $parameters = $request->getParameters();
        $LabTestModel = new Labtest();
        $contentModel = new TemplateContent();
        $TemplateModel = new Template();
        $template = '';
        $contents = '';

        //show labtest after test is created
        if (isset($parameters[0]['spec']) && $parameters[0]['spec'] == 'lab-test-template') {
            Application::$app->session->set('testname', urldecode($parameters[1]['id']));
            $this->setLayout("lab", ['select' => 'Tests']);
            $labtest = $LabTestModel->customFetchAll("SELECT * FROM lab_report_template join lab_tests on lab_report_template.template_ID=lab_tests.template_ID");
            $testDetail = $LabTestModel->customFetchAll("SELECT * from lab_tests where name=" . "'" . Application::$app->session->get('testname') . "'");

            return $this->render('lab/lab-add-new-template', [
                'model' => $LabTestModel,
                'labtest' => $labtest,
                'labtestmodel' => $LabTestModel,
                'templatemodel' => $TemplateModel,
                'testDetail' => $testDetail
            ]);
        }
        //Delete lab test
        if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'delete') {
            $LabTestModel->deleteRecord(['name' => $parameters[1]['id']]);
            Application::$app->session->setFlash('success', "Lab Test successfully deleted ");
            $response->redirect('/ctest/lab-view-all-test');
            return true;
        }
        //Go to update page for update lab test
        if (isset($parameters[0]['mod']) && $parameters[0]['mod'] == 'update') {
            $this->setLayout("lab", ['select' => 'Tests']);
            $LabTestModel = $LabTestModel->findOne(['name' => urldecode($parameters[1]['id'])]);
            Application::$app->session->set('labtest', urldecode($parameters[1]['id']));
            return $this->render('lab/lab-test-update', [
                'model' => $LabTestModel,
            ]);
        }

        if ($request->isPost()) {
            $LabTestModel->loadData($request->getBody());
            // update lab test
            if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'update') {

                $LabTestModel->loadData($request->getBody());
                $TemplateModel->loadData($request->getBody());
                $template_name_list = $TemplateModel->customFetchAll("SELECT title,template_ID from lab_report_template");
                $this->setLayout("lab", ['select' => 'Tests']);
                $template = $LabTestModel->customFetchAll("SELECT * from lab_tests ");

                if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'update') {
                    $curr_template_ID = $LabTestModel->customFetchAll("SELECT * FROM lab_tests WHERE name=" . "'" . urldecode($parameters[1]['id']) . "'");
                    $LabTestModel->template_ID = $curr_template_ID[0]["template_ID"];

                    if ($LabTestModel->validate()) {

                        $LabTestModel->updateLabtest($curr_template_ID[0]['name']);
                        Application::$app->session->setFlash('success', "lab test successfully updated ");
                        $response->redirect('/ctest/lab-view-all-test');
                        exit;
                    };
                }
            }
            //add lab test to the system;
            if ($LabTestModel->validate()) {

                if ($LabTestModel->addTest()) {
                    Application::$app->session->set('testname', $LabTestModel->name);
                    $this->setLayout("lab", ['select' => 'Tests']);
                    $labtest = $LabTestModel->customFetchAll("SELECT * FROM lab_report_template join lab_tests on lab_report_template.template_ID=lab_tests.template_ID");
                    $testDetail = $LabTestModel->customFetchAll("SELECT * from lab_tests where name=" . "'" . Application::$app->session->get('testname') . "'");
                    Application::$app->session->setFlash('success', "Lab Test successfully added ");
                    return $this->render('lab/lab-add-new-template', [
                        'model' => $LabTestModel,
                        'labtest' => $labtest,
                        'labtestmodel' => $LabTestModel,
                        'templatemodel' => $TemplateModel,
                        'testDetail' => $testDetail
                    ]);
                }
            } else {
                $LabTestModel->customFetchAll("SELECT * from lab_tests where name=" . "'" . Application::$app->session->get('testname') . "'");
                return $this->render('lab/lab-add-new-test', [
                    'labtestmodel' => $LabTestModel,
                    'tempmodel' => $TemplateModel,
                    'template' => $template,
                    'templatemodel' => $TemplateModel,
                    'template_name_list' => $template_name_list,


                ]);
            }
        }

        $this->setLayout("lab", ['select' => 'Tests']);
        $LabTestModel = new LabTest();
        $TemplateModel = new Template();
        $template = $LabTestModel->customFetchAll("SELECT * from lab_tests ");
        $template_name_list = $TemplateModel->customFetchAll("SELECT title,template_ID from lab_report_template");

        return $this->render('lab/lab-add-new-test', [
            'labtestmodel' => $LabTestModel,
            'tempmodel' => $TemplateModel,
            'template' => $template,
            'template_name_list' => $template_name_list,
            'templatemodel' => $TemplateModel,


        ]);
    }

    // ----------------handling template-------------------//
    public function handleTemplate(Request $request, Response $response)
    {

        $this->setLayout("lab", ['select' => 'Tests']);
        $LabTestModel = new LabTest();
        $TemplateModel = new Template();
        $parameters = $request->getParameters();
        $LabTestModel->loadData($request->getBody());
        $TemplateModel->loadData($request->getBody());
        $testDetail = $LabTestModel->customFetchAll("SELECT * from lab_tests where name='200' ");

        // add template to the test
        if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'tmp') {
            $TemplateModel->validate();
            var_dump($TemplateModel->errors);
            if ($TemplateModel->validate()) {

                $template = '';
                $this->setLayout("lab", ['select' => 'Tests']);
                $template = $TemplateModel->addTemplate()[0]['last_insert_id()'];
                $prev_template = $LabTestModel->get_prev_temp_ID();
                $update_temp_ID = $LabTestModel->update_temp_ID_on_test(urldecode($parameters[1]['id']), $prev_template[0]["template_ID"]);
                Application::$app->session->set('template', $template);
                Application::$app->session->setFlash('success', "new template created ");
                Application::$app->response->redirect('/ctest/lab-test-template');
                exit;
            }
        }

        return $this->render('lab/lab-add-new-template', [
            'labtestmodel' => $LabTestModel,
            'templatemodel' => $TemplateModel,
            'testDetail' => $testDetail



        ]);
    }


    //--------------view all lab test that already created-------------//
    public function viewTest()
    {
        $this->setLayout("lab", ['select' => 'Tests']);
        $labTestModel = new LabTest();
        $reportModel = new LabReport();
        $reportDetail = $reportModel->customFetchAll("SELECT * FROM lab_report");
        $tests = $labTestModel->customFetchAll("SELECT * FROM lab_report right join lab_tests on lab_report.template_ID=lab_tests.template_ID GROUP by lab_tests.template_ID");

        return $this->render('lab/lab-view-all-test', [
            'tests' => $tests,
            'reportDetail' => $reportDetail
        ]);
    }

    // ----------------------view test request-----------//

    public function testRequest()
    {
        $this->setLayout("lab", ['select' => 'Requests']);
        $labTestModel = new LabTest();
        $tests = $labTestModel->customFetchAll("SELECT lab_request.request_ID, patient.patient_ID,lab_request.name as test_name ,lab_request.requested_date_time , patient.name as patient_name,employee.name as doc_name 
        from lab_request join employee on employee.nic=lab_request.doctor join patient on patient.patient_ID=lab_request.patient_ID");

        return $this->render('lab/lab-test-request', [
            'tests' => $tests
        ]);
    }
    // ---------------------------wrire test result---------------------//
    public function writeResult(Request $request, Response $response)
    {
        // echo 'mhbsa';exit;
        $parameters = $request->getParameters();
        $contentModel = new TemplateContent();
        $reportmodel = new LabReport();
        $labtest = new LabTest();

        $this->setLayout("lab", ['select' => 'Requests']);
        $contents = $contentModel->customFetchAll("SELECT lab_request.request_ID,lab_request.name as tname,lab_request.note,lab_report_content.content_ID,patient.name as pname,employee.name as ename,patient.age,patient.gender,patient.patient_ID,lab_report_content.name as cname,lab_report_content.type,lab_report_content.metric from patient 
        left join lab_request on patient.patient_ID=lab_request.patient_ID 
        left join lab_tests on lab_tests.name=lab_request.name 
        left join employee on lab_request.doctor= employee.nic 
        left join lab_report_content on lab_report_content.template_ID=lab_tests.template_ID where lab_request.request_ID=" . $parameters[0]['id']);
       
        $reports = $labtest->customFetchAll("SELECT sum(lab_tests.hospital_fee+lab_tests.test_fee)as fee,lab_tests.template_ID,lab_request.request_ID from lab_tests join lab_request on lab_tests.name=lab_request.name join doctor on doctor.nic=lab_request.doctor where lab_request.request_ID=" . $parameters[0]['id']);
       
        $reportallocation = $reportmodel->customFetchAll("SELECT lab_request.patient_ID,lab_request.doctor,lab_report.report_ID from lab_report join lab_request on lab_request.request_ID=lab_report.request_ID order by lab_report.report_ID desc");

        if ($request->isPost()) {
            $AllocationModel = new LabContentAllocation();
            $reportmodel->loadData($request->getBody());
            $reportmodel->loadFiles($_FILES);
            $requst_reports = $reportmodel->get_report_by_ID($parameters[0]['id']);

          //create report for request if report is not created
            if (!$requst_reports) {
                $createReport = $reportmodel->create_new_report($reports[0]['fee'], ' ', ' ', $reports[0]['template_ID'], ' ', $parameters[0]['id']);
                $setPayment = $reportmodel->payment($contents[0]['patient_ID'], $reports[0]['fee'], $parameters[0]['id']);
                $requst_reports = $reportmodel->get_report_by_ID($parameters[0]['id']);
            }

            $setreport = $reportmodel->customFetchAll("SELECT report_ID from lab_report where request_ID=" . $parameters[0]['id']);
            
            foreach ($_POST as $content_ID => $value) {
                if ($content_ID != "Add") {
                    //add content value
                    if ($AllocationModel->get_types($content_ID)[0]['type'] === 'field') {
                        $AllocationModel->add_field_allocation($setreport[0]['report_ID'], $content_ID, $value);
                    } elseif ($AllocationModel->get_types($content_ID)[0]['type'] === 'text') {
                        $AllocationModel->add_text_allocation($setreport[0]['report_ID'], $content_ID, $value);
                    } else {
                        $AllocationModel->add_image_allocation($setreport[0]['report_ID'], $content_ID, $value);
                    }
                }
            }
            //print report as pdf
            echo $reportmodel->labreporttoPDF($setreport[0]['report_ID']);

            Application::$app->session->setFlash('success', "Lab Report successfully added ");
            Application::$app->response->redirect('/ctest/lab-view-all-report');
            exit;
        }
        return $this->render('lab/lab-write-test-result', [
            'contentmodel' => $contentModel,
            'contents' => $contents
        ]);
    }
    //-----------------------view report that we created-------------------//
    public function viewReport(Request $request, Response $response)
    {
        $reportmodel = new LabReport();
        $parameters = $request->getParameters();
        $this->setLayout("lab", ['select' => 'Lab Reports']);
        $reports = $reportmodel->customFetchAll("SELECT lab_report.report_ID,patient.name as pname, employee.name as dname,lab_request.name as tname,lab_request.requested_date_time as date from lab_report join lab_request on lab_report.request_ID= lab_request.request_ID join doctor on doctor.nic=lab_request.doctor join employee on employee.nic=doctor.nic join patient on patient.patient_ID=lab_request.patient_ID");
        return $this->render('lab/lab-view-all-report', [
            'reports' => $reports
        ]);
    }

//---------------------handle report-------------------//
    public function editReport(Request $request, Response $response)
    {
        $reportmodel = new LabReport();
        $parameters = $request->getParameters();
        $this->setLayout("lab", ['select' => 'Lab Reports']);

        //Delete report from system
        if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'delete') {
            $reportmodel->deleteRecord(['report_ID' => $parameters[1]['id']]);
            Application::$app->session->setFlash('success', "Lab Report successfully deleted ");
            $response->redirect('/ctest/lab-view-all-report');
            return true;
        }

        //go to edit page of report
        if (isset($parameters[0]['mod']) && $parameters[0]['mod'] == 'update') {
            $patients = $reportmodel->customFetchAll("SELECT lab_request.request_ID,lab_request.name as tname,lab_request.note,lab_report_content.content_ID,patient.name as pname,patient.age,patient.gender,patient.patient_ID,lab_report_content.name as cname,employee.name as ename,lab_report_content.type,lab_report_content.metric from patient join lab_request on patient.patient_ID=lab_request.patient_ID
            join lab_tests on lab_tests.name=lab_request.name
            join employee on lab_request.doctor= employee.nic
            join lab_report_content on lab_report_content.template_ID=lab_tests.template_ID
            join lab_report on lab_report.request_ID=lab_request.request_ID where lab_report.report_ID=" . $parameters[1]['id']);
            $imageReport=$reportmodel->customFetchAll("SELECT * FROM lab_report where report_ID=". $parameters[1]['id']);
            $reportDetail = $reportmodel->customFetchAll("SELECT * FROM lab_report_content_allocation left join lab_report_content on lab_report_content_allocation.content_ID=lab_report_content.content_ID WHERE lab_report_content_allocation.report_ID=" . $parameters[1]['id']);
            return $this->render('lab/lab-edit-report-detail', [
                'reportDetail' => $reportDetail,
                'parameters' => $parameters,
                'patients' => $patients,
                'imageReport'=>$imageReport

            ]);
        }

        if ($request->isPost()) {
            //update report
            if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'update') {
                $reportEdit = $reportmodel->updateReportValue($parameters[1]['id']);
                $response->redirect('lab-view-report-detail?id=' . $parameters[1]['id']);
                exit;
            }
        }
    }

    
    public function ReportDetail(Request $request, Response $response)
    {
        $reportmodel = new LabReport();
        $parameters = $request->getParameters();
        $this->setLayout("lab", ['select' => 'Lab Reports']);
        $report = $reportmodel->customFetchAll("Select * from lab_report where report_ID=" . $parameters[0]['id']);
        if ($report[0]['type'] == 'softcopy') {
            $response->redirect('/ctest/MVC/public/media/patient/labreports/' . $report[0]['location']);
        }
        $reports = $reportmodel->customFetchAll("SELECT * from lab_report left join lab_report_content_allocation   on lab_report_content_allocation.report_ID=lab_report.report_ID join lab_report_content on lab_report_content.content_ID=lab_report_content_allocation.content_ID where lab_report.report_ID=" . $parameters[0]['id']);
        echo $reportmodel->labreporttoPDF($reports[0]['report_ID']);
        return $this->render('lab/lab-view-report-detail', [
            'reports' => $reports
        ]);
    }

    //------------------upload report as text input--------------------//
    public function reportUpload(Request $request, Response $response)
    {
        $labTestModel = new LabTest();
        $reportmodel = new LabReport();
        $parameters = $request->getParameters();
        $this->setLayout("lab", ['select' => 'Requests']);

        $tests = $labTestModel->customFetchAll("SELECT lab_request.requested_date_time,employee.name as ename,lab_request.note,lab_request.request_ID,lab_report_content.content_ID,patient.name as pname,patient.age,patient.gender,patient.patient_ID,lab_report_content.name as cname,lab_report_content.type from patient join lab_request on patient.patient_ID=lab_request.patient_ID
        join lab_tests on lab_tests.name=lab_request.name
        join employee on lab_request.doctor= employee.nic
        join lab_report_content on lab_report_content.template_ID=lab_tests.template_ID where lab_request.request_ID=" . $parameters[0]['id']);


        if ($request->isPost()) {

            $reportmodel->loadData($request->getBody());
            $reportmodel->loadFiles($_FILES);
            $AllocationModel = new LabContentAllocation();
            $requst_reports = $reportmodel->get_report_by_ID($parameters[0]['id']);
            if (!$requst_reports) {
                $createReport = $reportmodel->create_new_report($tests[0]['fee'], $tests[0]['career_speciality'], ' ', $tests[0]['template_ID'], ' ', $parameters[0]['id']);
                $requst_reports = $reportmodel->get_report_by_ID($parameters[0]['id']);
            }
            $setreport = $reportmodel->customFetchAll("SELECT report_ID from lab_report where request_ID=" . $parameters[0]['id']);
            foreach ($_POST as $content_ID => $value) {
                if ($content_ID != "Add") {
                    $AllocationModel->add_image_allocation($setreport[0]['report_ID'], $content_ID, $value);
                }
            }
            Application::$app->session->setFlash('success', "Lab Report successfully added ");
            Application::$app->response->redirect('/ctest/lab-test-request');
            var_dump($createReport);
            exit;
        }
        return $this->render('lab/lab-report-upload', [
            'tests' => $tests,
            'model' => $labTestModel
        ]);
    }

//-----------------upload report as softcopy--------------------//
    public function uploadSReport(Request $request, Response $response)
    {
        $labTestModel = new LabTest();
        $reportmodel = new LabReport();
        $parameters = $request->getParameters();
        $this->setLayout("lab", ['select' => 'Requests']);
        $reports = $labTestModel->customFetchAll("SELECT sum(lab_tests.hospital_fee+lab_tests.test_fee)as fee,lab_tests.template_ID,lab_request.request_ID from lab_tests join lab_request on lab_tests.name=lab_request.name
    join doctor on doctor.nic=lab_request.doctor where lab_request.request_ID=" . $parameters[0]['id']);
        $tests = $labTestModel->customFetchAll("SELECT lab_report_content.template_ID,employee.name as ename,lab_request.note,lab_request.request_ID,lab_report_content.content_ID,patient.name as pname,patient.age,patient.gender,patient.patient_ID,lab_report_content.name as cname,lab_report_content.type from patient join lab_request on patient.patient_ID=lab_request.patient_ID
    join lab_tests on lab_tests.name=lab_request.name
    join employee on lab_request.doctor= employee.nic
    join lab_report_content on lab_report_content.template_ID=lab_tests.template_ID where lab_request.request_ID=" . $parameters[0]['id']);
        $reportallocation = $reportmodel->customFetchAll("SELECT lab_request.patient_ID,lab_request.doctor,lab_report.report_ID from lab_report join lab_request on lab_request.request_ID=lab_report.request_ID order by lab_report.report_ID desc");


        if ($request->isPost()) {

            $AllocationModel = new LabContentAllocation();
            $reportmodel->loadData($request->getBody());
            $reportmodel->loadFiles($_FILES);


            $reportmodel->fee = $reports[0]['fee'];
            $reportmodel->type = 'softcopy';
            $reportmodel->template_ID = $reports[0]['template_ID'];
            $reportmodel->request_ID = $parameters[0]['id'];

            $report_ID = $reportmodel->save();

            if ($report_ID) {
                $labRequestModel = new LabTestRequest();

                //get information from lab request table
                $request = $labRequestModel->fetchAssocAll(['request_ID' => $parameters[0]['id']])[0];
                $doctor = $request['doctor'];
                $patient = $request['patient_ID'];
                $report = $report_ID[0]['last_insert_id()'];
                $reportmodel->customFetchAll("INSERT INTO lab_report_allocation (report_ID,patient_ID,doctor) values($report,$patient,'$doctor')");
                Application::$app->session->setFlash('success', "Lab Report successfully added ");
                Application::$app->response->redirect('/ctest/lab-test-request');
                exit;
            }
            return $this->render('lab/lab-report-upload', [
                'reports' => $reports,
                'reportmodel' => "This field is required",
                'tests' => $tests


            ]);
        }
    }

    // --------------------- view personal detail---------------------------
    public function viewPersonalDetails()
    {
        $this->setLayout("lab", ['select' => 'My Detail']);
        $employeeModel = new Employee();
        $user = $employeeModel->customFetchAll("SELECT * FROM employee WHERE email=" . '"' . Application::$app->session->get('user') . '"');
        return $this->render('lab/lab-view-personal-details', [
            'user' => $user[0]
        ]);
    }

    //--------------------- edit personal details------------------//
    public function handleLab(Request $request, Response $response)
    {
        $this->setLayout("lab", ['select' => 'My Detail']);
        $parameters = $request->getParameters();
        $employeeModel = new Employee();

        if (isset($parameters[0]['mod']) && $parameters[0]['mod'] == 'update') {
            $user=$employeeModel->get_employee_details($parameters[1]['id']);

            $employeeModel->updateData($user, $employeeModel->fileDestination());
            Application::$app->session->set('user', $parameters[1]['id']);
            return $this->render('lab/lab-personal-detail-update', [
                'model' => $employeeModel,
                'user' => $user[0]
            ]);
        }
        if ($request->isPost()) {
          
            $employeeModel->loadData($request->getBody());
            $employeeModel->loadFiles($_FILES);
            // var_dump($userModel);exit;

            if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'update') {
                $curr_employee = $employeeModel->get_employee_details($parameters[1]['id']);
                
                if(!isset($_POST['img'])){
                    $employeeModel->img = $curr_employee[0]['img'];
                }
                if($_POST['gender'] === 'select'){
                    $employeeModel->gender = $curr_employee[0]['gender'];
                }
                $employeeModel->emp_ID = $curr_employee[0]['emp_ID'];
                $employeeModel->role = $curr_employee[0]['role'];
                $employeeModel->password = $curr_employee[0]['password'];
                $employeeModel->cpassword = $curr_employee[0]['password'];

                if ( $employeeModel->updateRecord(['emp_ID' => $parameters[1]['id']])) {
                    $response->redirect('/ctest/lab-view-personal-details');
                    Application::$app->session->setFlash('success', "Lab Personal Detail successfully updated ");
                    Application::$app->$response->redirect('/ctest/lab-view-personal-details');
                    exit;
                };
            }
        }
    
    
        return $this->render('lab/lab-view-personal-details', [
            'model' => $employeeModel,
        ]);
    }

    // -----------------------create template------------------//
    public function createTemplate(Request $request, Response $response)
    {
        $parameters = $request->getParameters();
        $contentModel = new TemplateContent();
        $TemplateModel = new Template();
        $template = '';
        $contents = '';
        $this->setLayout("lab", ['select' => 'Tests']);

        //Delete operation
        if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'delete') {
            $deleting_content = $contentModel->select_deleted_content($parameters[1]['id']);
            $contentModel->deleteRecord(['content_ID' => $parameters[1]['id']]);
            Application::$app->session->setFlash('success', "template successfully deleted ");
            $response->redirect('/ctest/lab-test-template');
            return true;
        }


        //go to edit page
        if (isset($parameters[0]['mod']) && $parameters[0]['mod'] == 'update') {
            $contentlist = $contentModel->customFetchAll("SELECT * FROM lab_report_content join lab_report_template on 
            lab_report_content.template_ID= lab_report_template.template_ID where content_ID=" . $parameters[1]['id']);
            $contentModel->updateData($contentlist, $contentModel->fileDestination());
            Application::$app->session->set('contentlist', $parameters[1]['id']);
            return $this->render('lab/lab-test-template', [
                'model' => $contentModel,
                'contentlist' => $contentlist[0]
            ]);
        }

        if ($request->isPost()) {
            $TemplateModel->loadData($request->getBody());
            $contentModel->loadData($request->getBody());
            $contentModel->loadFiles($_FILES);

            $updated_template_ID = $parameters[0]['id'] ??0;
            
            $newly_created_temp_ID = $contentModel->select_last_ID();
            if ($updated_template_ID) {
                $newly_created_temp_ID = $parameters[0]['id'];
                $last_position = $contentModel->select_last_content_ID($newly_created_temp_ID);

            }
            else{

                $last_position = $contentModel->select_last_content_ID($newly_created_temp_ID[0]['template_ID']);
            }


             // set position of template content
   

             
          
            $new_position = 1;

            //update template content
            if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'update') {
                if ($contentModel->validate() && $contentModel->updateRecord(['content_ID' => $parameters[1]['id']])) {
                    Application::$app->session->setFlash('success', "lab template content successfully updated ");
                    $response->redirect('/ctest/lab-test-template');
                    exit;
                };
            }

            // add content value
            if ($last_position) {
                $new_position = $last_position[0]['position'] + 1;
            }
            if(!$updated_template_ID){
                if ($_POST["type"] === 'text') {
                    $contents = $contentModel->add_text_type($_POST["name"], $new_position, $newly_created_temp_ID[0]['template_ID']);  //pass template id from above created new template
                } else if ($_POST["type"] === 'field') {
                    $contents = $contentModel->add_field_type($_POST["name"], $_POST["reference_ranges"], $new_position, $_POST["metric"], $newly_created_temp_ID[0]['template_ID']);  //pass template id from above created new template
                } else if ($_POST["type"] === 'image') {
                    $contents = $contentModel->add_image_type($_POST["name"], $new_position, $newly_created_temp_ID[0]['template_ID']);  //pass template id from above created new template
                }
            }
            else{
                if ($_POST["type"] === 'text') {
                    $contents = $contentModel->add_text_type($_POST["name"], $new_position, $newly_created_temp_ID);  //pass template id from above created new template
                } else if ($_POST["type"] === 'field') {
                    $contents = $contentModel->add_field_type($_POST["name"], $_POST["reference_ranges"], $new_position, $_POST["metric"], $newly_created_temp_ID);  //pass template id from above created new template
                } else if ($_POST["type"] === 'image') {
                    $contents = $contentModel->add_image_type($_POST["name"], $new_position, $newly_created_temp_ID);  //pass template id from above created new template
                }
            }
        }


        $updated_template_ID = $parameters[0]['id'] ?? 0;
        $newly_created_temp_ID = $contentModel->select_last_ID();
        if ($updated_template_ID) {
            $curr_template_ID = $parameters[0]['id'];
            $templteModle = new Template();
            $titles = $templteModle->fetchAssocAll(['template_ID' => $parameters[0]['id']])[0];
            $newly_created_temp_ID[0] = ['title' => $titles['title'], 'subtitle' => $titles['subtitle']];
        } else {

            $newly_created_temp_ID = $contentModel->select_last_ID();
            $curr_template_ID = $newly_created_temp_ID[0]['template_ID'];
        }

        $contents = $contentModel->customFetchAll("SELECT * from lab_report_content join lab_report_template on lab_report_content.template_ID=lab_report_template.template_ID WHERE lab_report_content.template_ID=$curr_template_ID ORDER BY lab_report_template.template_ID DESC");
        return $this->render('lab/lab-test-template', [
            'temp_title_sub' => $newly_created_temp_ID[0],
            'templatemodel' => $TemplateModel,
            'template' => $template,
            'contents' => $contents,
            'contentmodel' => $contentModel,
            'model' => $contentModel


        ]);
    }
    //-----------------------view all created templates--------------------//
    public function viewTemplate(Request $request, Response $response)
    {
        $parameters = $request->getParameters();

        $this->setLayout('lab', ['select' => 'Templates']);
        $contentModel = new TemplateContent();
        $templates = $contentModel->customFetchAll("SELECT * from lab_tests join lab_report_template on lab_report_template.template_ID=lab_tests.template_ID");
        return $this->render('lab/lab-view-all-template', [
            'templates' => $templates,
            'model' => $contentModel
        ]);
    }
    // -------------------view more about templates----------------//
    public function viewTemplateMore(Request $request, Response $response)
    {
        $parameters = $request->getParameters();
        $this->setLayout('lab', ['select' => 'Templates']);
        $contentModel = new TemplateContent();
        $templateModel = new Template();
        $contentModel = new TemplateContent();

        $templates = $contentModel->customFetchAll("SELECT * from lab_report_content where template_ID= " . (isset($parameters[1]['id']) ? $parameters[1]['id'] : $parameters[0]['id']));
        $tempID = $contentModel->customFetchAll("SELECT * from lab_report_template where template_ID= " . (isset($parameters[1]['id']) ? $parameters[1]['id'] : $parameters[0]['id']));
        $detail = $templateModel->customFetchAll("SELECT * from lab_report_template where template_ID= " . (isset($parameters[1]['id']) ? $parameters[1]['id'] : $parameters[0]['id']));


        //delete content
        if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'delete') {
            $deleting_content = $contentModel->select_deleted_content($parameters[1]['id']);
            $contentModel->deleteRecord(['content_ID' => $parameters[1]['id']]);

            Application::$app->session->setFlash('success', "Content Successfully Deleted ");
            $response->redirect('/ctest/lab-view-all-template-more?id=' . Application::$app->session->get('templateid'));

            return true;
        } else if (isset($parameters[0]['mod']) && $parameters[0]['mod'] == 'update') {
            $contentlist = $contentModel->customFetchAll("SELECT * FROM lab_report_content join lab_report_template on 
            lab_report_content.template_ID= lab_report_template.template_ID where content_ID=" . $parameters[1]['id']);
            $contentModel->updateData($contentlist, $contentModel->fileDestination());
            Application::$app->session->set('contentlist', $parameters[1]['id']);
            return $this->render('lab/lab-test-template', [
                'model' => $contentModel,
                'contentlist' => $contentlist[0]
            ]);
        } else {

            Application::$app->session->set('templateid', (isset($parameters[1]['id']) ? $parameters[1]['id'] : $parameters[0]['id']));
        }
        return $this->render('lab/lab-view-all-template-more', [
            'templates' => $templates,
            'detail' => $detail[0],
            'model' => $contentModel,
            'tempID'=>$tempID[0],

        ]);
    }
  
}
