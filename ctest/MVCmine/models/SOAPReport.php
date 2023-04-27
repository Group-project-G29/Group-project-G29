<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\PDF;
use app\core\UserModel;

class SOAPReport extends DbModel{
    public string $subjective='';
    public string $objective='';
    public string $assessment = "";
    public string $plan = '';
    public string $additional_note = '';
    public string $report_ID='';
    
    
   
    public function addReport($report_model){
          if($this->subjective!='' || $this->objective || $this->assessment || $this->plan ||$this->additional_note  ){
            $report_model->fixName();
            $id=$report_model->save();
            $this->report_ID=$id[0]['last_insert_id()'];
            return parent::save();
        }
        else return false;return parent::save();
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
        return 'soap_report';
    }
    public function primaryKey(): string
    {
        return 'report_ID';
    }
    public function tableRecords(): array{
        return ['soap_report'=>['subjective','objective','assessment','plan','additional_note','report_ID']];
    }

    public function attributes(): array
    {
        return  ['subjective','objective','assessment','plan','additional_note','report_ID'];
    }

    
    public function SOAPreportToPDF($reportID){
        $reports=$this->customFetchAll("Select s.subjective, s.objective,s.assessment,s.plan,s.additional_note,s.report_ID,m.uploaded_date,m.type,m.name as report_name,m.label,p.name,p.age,p.gender,p.type  from soap_report as s right join medical_report as m on m.report_ID=s.report_ID right join patient  as p on p.patient_ID=m.patient
        where s.report_ID=".$reportID);
        $report_doctor=$this->customFetchAll("Select e.name from soap_report as s right join medical_report as m on m.report_ID=s.report_ID right join employee as e on e.nic=m.doctor  where s.report_ID=".$reportID);
        $pdfModel=new PDF();
        $addstr='';
        if($reports['subjective'] && $reports['subjective']!=''){
            $addstr.="<div>Subjective</div>
                    <div>".$reports['subjective']."</div>";

        }
        if(isset($reports['obejctive']) && $reports['objective']!=''){
            $addstr.="<div>Objective</div>
            <div>".$reports['objective']."</div>";
        }
        if(isset($reports['assessment']) && $reports['assessment']!=''){
            $addstr.="<div>Assessment</div>
            <div>".$reports['assessment']."</div>";
        }
        if(isset($reports['plan']) && $reports['plan']!=''){
            $addstr.="<div>Plan</div>
            <div>".$reports['plan']."</div>";
        }
        if(isset($reports['additional_note']) && $reports['additional_note']!=''){
            $addstr.="<div>Additional Notes</div> <div>".$reports['additional_note']."</div>
            <div>".$reports['additional_note']."</div>";

        }
        $str="
            <html>
                <head>
                <style>
                    .show{    
                      background-color:red;
                    }
                </style>
                </head>
                <body>
                    <section class='show'>
                        <div>Patient Name :".$reports['name']."</div>
                        <div>Patient Gender :".$reports['gender']."</div><br>
                        <div>Patient Age :".$reports['age']."</div><br>

                        <div>Issued Doctor :".$report_doctor['name']."</div>"."
                        <div>Issued date :".$reports['uploaded_date']."</div>
                        
                    </section>
                    <section>
                        ".$addstr."
                    </section>
                </body>
            <html>
        ";
        $pdfModel->createPDF($str,'reports-'.$reports['date']);


    } 
}   



?>