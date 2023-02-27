<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\User;
use app\core\DbModel;
use app\core\Model;
use app\core\Response;
use app\models\Channeling;
use app\models\LabTest;
use app\models\Template;

use app\models\Medicine;
use app\models\Employee;
use app\models\TemplateContent;

class LabController extends Controller
{
    //delete update insert  medicine
    public function handleTest(Request $request, Response $response)
    {
        $parameters = $request->getParameters();

        $LabTestModel = new Labtest();

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
            echo 'ispost';

            // update medicine
            $LabTestModel->loadData($request->getBody());
            $LabTestModel->loadFiles($_FILES);

            if (isset($parameters[0]['cmd']) && $parameters[0]['cmd'] == 'update') {
                if ($LabTestModel->validate() && $LabTestModel->updateRecord(['name' => $parameters[1]['id']])) {
                    $response->redirect('/ctest/lab-view-all-test');
                    Application::$app->session->setFlash('success', "lab test successfully updated ");
                    $response->redirect('/ctest/lab-view-all-test');
                    exit;
                };
            }

            //add test
            if ($LabTestModel->validate() && $LabTestModel->addTest()) {
                echo 'add';
                Application::$app->session->setFlash('success', "Lab Test successfully added ");
                Application::$app->response->redirect('/ctest/lab-view-all-test');
                $this->setLayout("lab", ['select' => 'Tests']);
                $labtest = $LabTestModel->customFetchAll("SELECT * FROM lab_report_template join lab_tests on lab_report_template.template_ID=lab_tests.template_ID");
                return $this->render('lab/lab-view-all-test', [
                    'model' => $LabTestModel,
                    'labtest' => $labtest
                ]);
            };
        }


        $this->setLayout("lab", ['select' => 'Tests']);
        $LabTestModel = new LabTest();
        $template_name_list = $LabTestModel->customFetchAll("SELECT * FROM lab_report_template join lab_tests on lab_report_template.template_ID=lab_tests.template_ID");

        return $this->render('lab/lab-add-new-test', [
            'model' => $LabTestModel,
            'template_name_list' => $template_name_list,
            //03\7
            //template [0], [1]

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





    public function testRequest()
    {
        $this->setLayout("lab", ['select' => 'Requests']);
        $labTestModel = new LabTest();
        $tests = $labTestModel->customFetchAll("SELECT lab_request.test_name as test_name ,lab_request.requested_date_time , patient.name as patient_name,employee.name as doc_name from lab_request join employee on employee.nic=lab_request.doctor join patient on patient.patient_ID=lab_request.patient_ID");
        return $this->render('lab/lab-test-request', [
            'tests' => $tests
        ]);
    }
    public function reportUpload()
    {
        $labTestModel = new LabTest();
        $tests = $labTestModel->customFetchAll("SELECT lab_request.requested_date_time , patient.name as patient_name,employee.name as doc_name from lab_request join employee on employee.nic=lab_request.doctor join patient on patient.patient_ID=lab_request.patient_ID");
        return $this->render('lab/lab-report-upload', [
            'tests' => $tests[0]
        ]);
    }





    public function viewPersonalDetails()
    {
        $this->setLayout("lab", ['select' => 'My Detail']);
        $userModel = new Employee();
        $user = $userModel->customFetchAll("SELECT * FROM employee WHERE email=" . '"' . Application::$app->session->get('user') . '"');
        return $this->render('lab/lab-view-personal-details', [
            'user' => $user[0]
        ]);
    }

    public function writeReport()
    {
        $this->setLayout("lab", ['select' => 'Requests']);
        // $userModel = new Employee();
        // $user = $userModel->customFetchAll("SELECT * FROM employee WHERE email=" . '"' . Application::$app->session->get('user') . '"');
        return $this->render('lab/lab-write-test-report', [
            // 'user' => $user[0]
        ]);
    }

    public function createTemplate(Request $request, Response $response)
    {
        $parameters = $request->getParameters();

        $contentModel = new TemplateContent();
        $TemplateModel = new Template();
        $template = '';
        $content='';


        $this->setLayout("lab", ['select' => 'Tests']);

        if ($request->isPost()) {

            $TemplateModel->loadData($request->getBody());

            $contentModel->loadData($request->getBody());
            $contentModel->loadFiles($_FILES);

            if (isset($parameters[0]['spec']) && $parameters[0]['spec'] == 'template') {

                if ($TemplateModel->validate()) {

                    $template = $TemplateModel->addTemplate()[0]['last_insert_id()'];
                    Application::$app->session->set('template', $template);
                    Application::$app->session->setFlash('success', "new template created ");
                }
            }
            if (isset($parameters[0]['spec']) && $parameters[0]['spec'] == 'content') {

                if ($TemplateModel->validate()) {

                    $content = $contentModel->addContent()[0]['last_insert_id()'];
                    Application::$app->session->set('content', $content);
                    Application::$app->session->setFlash('success', "new template created ");
                }
            }
            
        }
        return $this->render('lab/lab-test-template', [
            'templatemodel' => $TemplateModel,
            'template' => $template,
            'content' =>$content,
            'contentmodel'=>$contentModel

        ]);
       
    }
    public function handleTemplate(Request $request, Response $response)
    {
        $TemplateModel = new TemplateContent();
    }
}
