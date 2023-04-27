<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\Calendar;
use app\core\Date;
use app\core\PDF;
use app\core\Request;
use app\core\Response;
use app\core\UserModel;
use Time;

class MedicalReport extends DbModel{
    public string $type = '';
    public string $name = '';
    public string $label = '';
    public string $patient='';
    public string  $doctor=''; 
    
    public function setReport($type,$label,$patient,$doctor,$name='e-report'){
        
        $this->type = $type;
        $this->name = $name;
        $this->label = $label;
        $this->patient=$patient;
        $this->doctor=$doctor;
        
    }
    public function addReport(){
        return parent::save();
    }
 
    public function rules(): array
    {
        return [];
    }
    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'medical_report';
    }
    public function primaryKey(): string
    {
        return 'report_ID';
    }
    public function tableRecords(): array{
        return ['medical_report'=> ['type','patient','doctor','name','label']];
    }

    public function attributes(): array
    {
        return  ['type','name','label','patient','doctor'];
    }
    public function fixName(){
        $str=$this->label;
        $this->label=join('-',explode(' ',$str));
        
    }
    //fucntion which returns patient medical report during last week 
    public function getRecentReports($patient,$doctor){
        $calendar=new Calendar();
        //date before a week
        echo date('Y-m-d');
        $date_before=$calendar->addDaysToDate(date('Y-m-d'),-8);
        var_dump($date_before);
        return $this->customFetchAll("Select medical_report.report_ID,medical_report.uploaded_date,medical_report.type,medical_report.label from medical_report left join Medical_history on medical_report.report_ID=Medical_history.report_ID left join soap_report on soap_report.report_ID=medical_report.report_ID left join consultation_report on consultation_report.report_ID=medical_report.report_ID where medical_report.uploaded_date>'".$date_before."' and patient=".$patient." and doctor=".$doctor);
    
        

    }
    public function updateReport(Request $request,Response $response){
        $parameters=$request->getParameters();
        if($parameters[0]['spec']='consultation-report'){
            $ReportModel=new ConsultationReport();
            $ReportModel->loadData($request->getBody());
            $ReportModel->updateRecord(['report_ID'=>$parameters[2]['id']]);
            $response->redirect("doctor-report?spec=consultation-report"); 
        }
        if($parameters[0]['spec']='medical-history-report'){
            $ReportModel=new MedicalHistory();
            $ReportModel->loadData($request->getBody());
            $ReportModel->updateRecord(['report_ID'=>$parameters[2]['id']]);
            $response->redirect("doctor-report?spec=medical-history-report"); 
        }
        if($parameters[0]['spec']='soap-report'){
            $ReportModel=new SOAPReport();
            $ReportModel->loadData($request->getBody());
            $ReportModel->updateRecord(['report_ID'=>$parameters[2]['id']]);
            $response->redirect("doctor-report?spec=soap-report"); 
        }
        
    }
    public function addLabReportSoftCopy(Request $request){
        //$_FILE=>array(1) { ["file"]=> array(6) { ["name"]=> array(2) { [0]=> string(8) "ref1.jpg" [1]=> string(8) "ref2.png" } ["full_path"]=> array(2) { [0]=> string(8) "ref1.jpg" [1]=> string(8) "ref2.png" } ["type"]=> array(2) { [0]=> string(10) "image/jpeg" [1]=> string(9) "image/png" } ["tmp_name"]=> array(2) { [0]=> string(25) "/opt/lampp/temp/phplH2iAl" [1]=> string(25) "/opt/lampp/temp/phpSwHtAo" } ["error"]=> array(2) { [0]=> int(0) [1]=> int(0) } ["size"]=> array(2) { [0]=> int(76085) [1]=> int(67004) } } } 
        // files in $_FILE array
        $filearray=$_FILES;
        if($filearray['report']['name'][0]==''){
            return true;
        }
        $counter=0;
        
        //save each file in the system hard disk
        foreach($filearray['report']['name'] as $file){
            $filename=uniqid().$file;
            $filePath="media/patient/medicalreports/".$filename;
            $fileTempPath=$filearray['report']['tmp_name'][$counter];
            move_uploaded_file($fileTempPath,$filePath);
            //save in database
            $medicalreport=new MedicalReport();
            $consultationreport=new ConsultationReport();
            $medicalreport->loadData($request->getBody());
            $medicalreport->name='e-report';
            $medicalreport->label=$filename;
            $medicalreport->patient=Application::$app->session->get('cur_patient');
            $medicalreport->doctor=Application::$app->session->get('userObject')->nic;
            $medicalreport->savenofiles();
            $counter++;



        }
    }
    public function getReportsByPatient(){
        return $this->customFetchAll("Select medical_report.report_ID,medical_report.uploaded_date,medical_report.type,medical_report.label from medical_report left join Medical_history on medical_report.report_ID=Medical_history.report_ID left join soap_report on soap_report.report_ID=medical_report.report_ID left join consultation_report on consultation_report.report_ID=medical_report.report_ID where medical_report.patient=".Application::$app->session->get('user')." order by medical_report.uploaded_date desc");
    }
    public function getAllReportsDoctor(){
        return $this->customFetchAll("Select medical_report.report_ID,medical_report.uploaded_date,medical_report.type,medical_report.label from medical_report left join Medical_history on medical_report.report_ID=Medical_history.report_ID left join soap_report on soap_report.report_ID=medical_report.report_ID left join consultation_report on consultation_report.report_ID=medical_report.report_ID where medical_report.patient=".Application::$app->session->get('cur_patient')." and medical_report.doctor=".Application::$app->session->get('userObject')->nic." order by medical_report.uploaded_date desc");
    }
}