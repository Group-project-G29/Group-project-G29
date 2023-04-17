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

class LabController extends Controller
{
    //delete update insert test
    public function handleTest(Request $request, Response $response)
    {
        // %20 must be converted to a space

        $parameters = $request->getParameters();
        // var_dump($parameters);
        // exit;

        $LabTestModel = new Labtest();
        $contentModel = new TemplateContent();
        $TemplateModel = new Template();
        $template = '';
        $contents = '';

        //Delete operation
        if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'delete') {
            $LabTestModel->deleteRecord(['name' => $parameters[1]['id']]);
            Application::$app->session->setFlash('success', "Lab Test successfully deleted ");
            $response->redirect('/ctest/lab-view-all-test');
            return true;
        }
        //Go to update page
        if (isset($parameters[0]['mod']) && $parameters[0]['mod'] == 'update') {
            $labtest = $LabTestModel->customFetchAll("Select * from lab_tests where name=" . "'" . $parameters[1]['id'] . "'");

            $LabTestModel->updateData($labtest, $LabTestModel->fileDestination());
            Application::$app->session->set('labtest', $parameters[1]['id']);
            return $this->render('lab/lab-test-update', [
                'model' => $LabTestModel,
                // 'labtest'=>$labtest
            ]);
        }
        if ($request->isPost()) {
            // update test
            $LabTestModel->loadData($request->getBody());
            $TemplateModel->loadData($request->getBody());
            $template_name_list = $TemplateModel->customFetchAll("SELECT title,template_ID from lab_report_template");
            $this->setLayout("lab", ['select' => 'Tests']);
            $template = $LabTestModel->customFetchAll("SELECT * from lab_tests ");
            // $template_name_list = $TemplateModel->customFetchAll("SELECT title,template_ID from lab_report_template");

            // $result=mysqli_query($connection,$template_name_list)

            // var_dump($_POST);
            // var_dump($parameters);

            $curr_template_ID = $LabTestModel->customFetchAll("SELECT * FROM lab_tests WHERE name=" . "'" . $parameters[1]['id'] . "'");
// var_dump($curr_template_ID);
// exit;
            if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'update') {
                $LabTestModel->template_ID = $curr_template_ID[0]["template_ID"];
                // exit;
                // if ($LabTestModel->validate() && $LabTestModel->updateRecord(['name' => $parameters[1]["id"]])) {
                if ($LabTestModel->validate()) {
                    // echo 'before update';

                    $LabTestModel->updateLabtest($curr_template_ID[0]['name']);
                    // $response->redirect('/ctest/lab-view-all-test');
                    // echo 'validated';
                    // exit;
                    Application::$app->session->setFlash('success', "lab test successfully updated ");
                    $response->redirect('/ctest/lab-view-all-test');
                    exit;
                };
                // echo 'wrong';
                // exit;
            }

            //add test;
            if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'tst') {

                if ($LabTestModel->validate() && $LabTestModel->addTest()) {

                    $this->setLayout("lab", ['select' => 'Tests']);
                    $labtest = $LabTestModel->customFetchAll("SELECT * FROM lab_report_template join lab_tests on lab_report_template.template_ID=lab_tests.template_ID");
                    Application::$app->session->setFlash('success', "Lab Test successfully added ");
                    Application::$app->response->redirect('/ctest/lab-add-new-test');
                    // return $this->render('lab/lab-add-new-test', [
                    //     'model' => $LabTestModel,
                    //     'labtest' => $labtest
                    // ]);
                } else {
                    return $this->render('lab/lab-add-new-test', [
                        'labtestmodel' => $LabTestModel,
                        'tempmodel' => $TemplateModel,
                        'template' => $template,
                        'templatemodel' => $TemplateModel,
                        'template_name_list' => $template_name_list,


                    ]);
                }
            }
            
            // add template
            if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'tmp') {
                if ($TemplateModel->validate() && $TemplateModel->addTemplate()) {
                    $template = '';
                    $this->setLayout("lab", ['select' => 'Tests']);
                    $template = $TemplateModel->addTemplate()[0]['last_insert_id()'];
                    Application::$app->session->set('template', $template);

                    Application::$app->response->redirect('/ctest/lab-test-template');
                    Application::$app->session->setFlash('success', "new template created ");
                }
                // else{
                //     return $this->render('lab/lab-add-new-test', [
                //         'labtestmodel' => $LabTestModel,
                //         'tempmodel' => $TemplateModel,
                //         'template' => $template,
                //         'templatemodel' => $TemplateModel,
                //         'template_name_list' => $template_name_list,

                //     ]);
                // }
            }
        }

        $this->setLayout("lab", ['select' => 'Tests']);
        $LabTestModel = new LabTest();
        $TemplateModel = new Template();
        $template = $LabTestModel->customFetchAll("SELECT * from lab_tests ");
        $template_name_list = $TemplateModel->customFetchAll("SELECT title,template_ID from lab_report_template");

        // $result=mysqli_query($connection,$template_name_list);
        return $this->render('lab/lab-add-new-test', [
            'labtestmodel' => $LabTestModel,
            'tempmodel' => $TemplateModel,
            'template' => $template,
            'template_name_list' => $template_name_list,
            'templatemodel' => $TemplateModel,


        ]);
    }
    //view test
    public function viewTest()
    {
        $this->setLayout("lab", ['select' => 'Tests']);
        $labTestModel = new LabTest();
        $tests = $labTestModel->customFetchAll("SELECT * FROM lab_tests");
        return $this->render('lab/lab-view-all-test', [
            'tests' => $tests
        ]);
    }

    // ------------------------------view test request

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
    // ---------------------------wrire test reult
    public function writeResult(Request $request, Response $response)
    {
        $parameters = $request->getParameters();
        $contentModel = new TemplateContent();
        $reportmodel = new LabReport();
        $labtest = new LabTest();

        $this->setLayout("lab", ['select' => 'Requests']);

        $contents = $contentModel->customFetchAll("SELECT lab_report_content.content_ID,patient.name as pname,patient.age,patient.gender,patient.patient_ID,lab_report_content.name as cname,lab_report_content.type from patient join lab_request on patient.patient_ID=lab_request.patient_ID
        join lab_tests on lab_tests.name=lab_request.name
        join lab_report_content on lab_report_content.template_ID=lab_tests.template_ID where lab_request.request_ID=" . $parameters[0]['id']);

        $reports = $labtest->customFetchAll("SELECT sum(lab_tests.hospital_fee+lab_tests.test_fee)as fee,lab_tests.template_ID,doctor.career_speciality,lab_request.request_ID from lab_tests join lab_request on lab_tests.name=lab_request.name
        join doctor on doctor.nic=lab_request.doctor where lab_request.request_ID=" . $parameters[0]['id']);

        if ($request->isPost()) {
            $reportmodel->loadData($request->getBody());
            $reportmodel->loadFiles($_FILES);

            // $reportmodel->fee = $reports[0]['fee'] ;
            // $reportmodel->template_ID = $reports[0]['template_ID'] ;
            // $reportmodel->type = $reports[0]['type'] ;
            // $reportmodel->request_ID = $reports[0]['request_ID'] ;
            $AllocationModel = new LabContentAllocation();
            $requst_reports = $reportmodel->get_report_by_ID($parameters[0]['id']);
            if (!$requst_reports) {
                $createReport = $reportmodel->create_new_report($reports[0]['fee'], $reports[0]['career_speciality'], ' ', $reports[0]['template_ID'], ' ', $parameters[0]['id']);
                $requst_reports = $reportmodel->get_report_by_ID($parameters[0]['id']);
            }

            $setreport = $reportmodel->customFetchAll("SELECT report_ID from lab_report where request_ID=" . $parameters[0]['id']);
            foreach ($_POST as $content_ID => $value) {
                if ($content_ID != "Add") {
                    if ($AllocationModel->get_types($content_ID)[0]['type'] === 'field') {
                        $AllocationModel->add_field_allocation($setreport[0]['report_ID'], $content_ID, $value);
                    } elseif ($AllocationModel->get_types($content_ID)[0]['type'] === 'text') {
                        $AllocationModel->add_text_allocation($setreport[0]['report_ID'], $content_ID, $value);
                    } else {
                        $AllocationModel->add_image_allocation($setreport[0]['report_ID'], $content_ID, $value);
                    }
                }
            }
            Application::$app->session->setFlash('success', "Lab Report successfully added ");
            Application::$app->response->redirect('/ctest/lab-test-request');
        }
        // $AllocationModel = new LabContentAllocation();

        return $this->render('lab/lab-write-test-result', [
            'contentmodel' => $contentModel,
            'allocationmodel' => $AllocationModel,
            'contents' => $contents
        ]);
    }

    public function viewReport(Request $request, Response $response){
        $reportmodel = new LabReport();
        $parameters = $request->getParameters();
        $reports=$reportmodel->customFetchAll("SELECT report_ID from lab_report");
        $requst_reports = $reportmodel->get_report_by_ID($parameters[0]['id']);
        $reportmodel->labreporttoPDF($requst_reports[0]['report_ID']);
        // var_dump($requst_reports[0]['report_ID']);
        // exit;

        return $this->render('lab/lab-view-all-report', [
            'reports' => $reports
        ]);

    }

    public function reportUpload(Request $request, Response $response)
    {
        $labTestModel = new LabTest();
        $reportmodel = new LabReport();
        $parameters = $request->getParameters();
        $this->setLayout("lab", ['select' => 'Requests']);
        $tests = $labTestModel->customFetchAll("SELECT lab_request.requested_date_time,employee.name as ename,lab_request.request_ID,lab_report_content.content_ID,patient.name as pname,patient.age,patient.gender,patient.patient_ID,lab_report_content.name as cname,lab_report_content.type from patient join lab_request on patient.patient_ID=lab_request.patient_ID
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
        return $this->render('lab/lab-report-upload',[
            'tests' => $tests,
            'model'=>$labTestModel
        ]);
    }


    // --------------------- view personal detail---------------------------


    public function viewPersonalDetails()
    {
        $this->setLayout("lab", ['select' => 'My Detail']);
        $userModel = new Employee();
        $user = $userModel->customFetchAll("SELECT * FROM employee WHERE email=" . '"' . Application::$app->session->get('user') . '"');
        return $this->render('lab/lab-view-personal-details', [
            'user' => $user[0]
        ]);
    }

    public function handleLab(Request $request, Response $response)
    {
        $this->setLayout("lab", ['select' => 'My Detail']);
        $parameters = $request->getParameters();
        $userModel = new Employee();

        if (isset($parameters[0]['mod']) && $parameters[0]['mod'] == 'update') {
            $user = $userModel->customFetchAll("SELECT * from employee where email=" . "'" . $parameters[1]['id'] . "'");
            // var_dump($user);
            // exit;
            $userModel->updateData($user, $userModel->fileDestination());
            Application::$app->session->set('user', $parameters[1]['id']);
            return $this->render('lab/lab-personal-detail-update', [
                'model' => $userModel,
                'user' => $user[0]
            ]);
        }
        if ($request->isPost()) {
            // update employee
            $userModel->loadData($request->getBody());
            $userModel->loadFiles($_FILES);
            if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'update') {
                if ($userModel->validate() && $userModel->updateRecord(['email' => $parameters[1]['id']])) {
                    $response->redirect('/ctest/lab-view-personal-details');
                    Application::$app->session->setFlash('success', "Lab Personal Detail successfully updated ");
                    $response->redirect('/ctest/lab-view-personal-details');
                    exit;
                };
            }
        }
        return $this->render('lab/lab-view-personal-details', [
            'model' => $userModel,


        ]);
    }

    // public function writeReport()
    // {
    //     $this->setLayout("lab", ['select' => 'Requests']);
    //     // $userModel = new Employee();
    //     // $user = $userModel->customFetchAll("SELECT * FROM employee WHERE email=" . '"' . Application::$app->session->get('user') . '"');
    //     return $this->render('lab/lab-write-test-report', [
    //         // 'user' => $user[0]
    //     ]);
    // }


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
            $newly_created_temp_ID = $contentModel->select_last_ID();
            $last_position = $contentModel->select_last_content_ID($newly_created_temp_ID[0]['template_ID']);
            $new_position = 1;

            //update template content
            if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'update') {
                if ($contentModel->validate() && $contentModel->updateRecord(['content_ID' => $parameters[1]['id']])) {
                    // $response->redirect('/ctest/lab-test-template');
                    Application::$app->session->setFlash('success', "lab template content successfully updated ");
                    $response->redirect('/ctest/lab-test-template');
                    exit;
                };
            }
            //call the function
            
            if ($last_position) {
                $new_position = $last_position[0]['position'] + 1;
            }
            if ($_POST["type"] === 'text') {
                $contents = $contentModel->add_text_type($_POST["name"], $new_position, $newly_created_temp_ID[0]['template_ID']);  //pass template id from above created new template
            } else if ($_POST["type"] === 'field') {
                $contents = $contentModel->add_field_type($_POST["name"], $_POST["reference_ranges"], $new_position, $_POST["metric"], $newly_created_temp_ID[0]['template_ID']);  //pass template id from above created new template
            } else if ($_POST["type"] === 'image') {
                $contents = $contentModel->add_image_type($_POST["name"], $new_position, $newly_created_temp_ID[0]['template_ID']);  //pass template id from above created new template
            }
            Application::$app->session->setFlash('success', "new template created ");
        }
        
        $newly_created_temp_ID = $contentModel->select_last_ID();
        $curr_template_ID = $newly_created_temp_ID[0]['template_ID'];
        $contents = $contentModel->customFetchAll("SELECT * from lab_report_content join lab_report_template on lab_report_content.template_ID=lab_report_template.template_ID WHERE lab_report_content.template_ID=$curr_template_ID ORDER BY lab_report_template.template_ID DESC");
    //    var_dump($contents[0]["type"]);
    //    exit;
        
        return $this->render('lab/lab-test-template', [
            'temp_title_sub' => $newly_created_temp_ID[0],
            'templatemodel' => $TemplateModel,
            'template' => $template,
            'contents' => $contents,
            'contentmodel' => $contentModel,
            'model' => $contentModel


        ]);
    }
    //-----------------------handle advertistment-----------------//
    public function handleAdvertisement(Request $request, Response $response)
    {
        // echo 'handleAdvertisement';

        $parameters = $request->getParameters();

        $this->setLayout('lab', ['select' => 'Advertisement']);
        $advertisementModel = new LabAdvertisement();

        //Delete operation
        if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'delete') {
            $advertisementModel->deleteRecord(['ad_ID' => $parameters[1]['id']]);
            Application::$app->session->setFlash('success', "Advertisement successfully deleted ");
            $response->redirect('/ctest/lab-view-advertisement');
            return true;
        }

        //Go to update page of a advertisement
        if (isset($parameters[0]['mod']) && $parameters[0]['mod'] == 'update') {
            $advertisement = $advertisementModel->customFetchAll("Select * from advertisement where ad_ID=" . $parameters[1]['id']);
            $advertisementModel->updateData($advertisement, $advertisementModel->fileDestination());
            Application::$app->session->set('advertisement', $parameters[1]['id']);
            return $this->render('lab/lab-update-advertisement', [
                'model' => $advertisementModel,
            ]);
        }

        if ($request->isPost()) {
            // update advertisement
            echo 'lab';
            $advertisementModel->loadData($request->getBody());
            $advertisementModel->loadFiles($_FILES);
            if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'update') {


                if ($advertisementModel->validate() && $advertisementModel->updateRecord(['ad_ID' => $parameters[1]['id']])) {
                    $response->redirect('/ctest/lab-view-advertisement');
                    Application::$app->session->setFlash('success', "Advertisement successfully updated ");
                    Application::$app->response->redirect('/ctest/lab-view-advertisement');
                    exit;
                };
            }

            // add new advertisement
            if ($advertisementModel->validate() && $advertisementModel->addLabAdvertisement()) {
                Application::$app->session->setFlash('success', "Advertisement successfully added ");
                Application::$app->response->redirect('/ctest/lab-view-advertisement');
                $this->setLayout("lab", ['select' => 'Advertisement']);
                $advertisementModel = new LabAdvertisement();
                $advertisements = $advertisementModel->customFetchAll("Select * from advertisement order by name asc");
                return $this->render('lab/lab-view-advertisement', [
                    'advertisements' => $advertisements,
                    'model' => $advertisementModel
                ]);
            }
        }

        return $this->render('lab/lab-add-advertisement', [
            'model' => $advertisementModel,
        ]);
    }


    //view advertisement
    public function viewAdvertisement()
    {
        $this->setLayout("lab", ['select' => 'Advertisement']);
        $advertisementModel = new LabAdvertisement();
        $advertisements = $advertisementModel->customFetchAll("Select * from advertisement order by title asc");
        return $this->render('lab/lab-view-advertisement', [
            'advertisements' => $advertisements,
            'model' => $advertisementModel
        ]);
    }
}
