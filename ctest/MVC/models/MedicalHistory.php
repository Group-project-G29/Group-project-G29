<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\PDF;
use app\core\UserModel;

class MedicalHistory extends DbModel{
    public string $medication='';
    public string $allergies='';
    public string $note = "";
    public string $report_ID="";
    
    
    public function addReport($report_model){
        if(trim($this->medication) || trim($this->note) || trim($this->allergies)){
            $report_model->fixName();
            $id=$report_model->save();
            $this->report_ID=$id[0]['last_insert_id()'];
            var_dump($this);
            return parent::save();
        }
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
        return 'Medical_history';
    }
    public function primaryKey(): string
    {
        return 'report_ID';
    }
    public function tableRecords(): array{
        return ['Medical_history'=>['allergies','medication','note','report_ID']];
    }

    public function attributes(): array
    {
        return  ['allergies','medication','note','report_ID'];
    }
    public function HistoryreportToPDF($reportID){
        $reports=$this->customFetchAll("Select c.medication, c.allergies,c.note,m.uploaded_date,m.report_ID as report_name,p.name,p.age,p.gender  from  Medical_history as c right join medical_report as m on m.report_ID=c.report_ID right join patient  as p on p.patient_ID=m.patient where m.report_ID=".$reportID)[0];
        $report_doctor=$this->customFetchAll("Select e.name from Medical_history as c right join medical_report as m on m.report_ID=c.report_ID right join employee as e on e.nic=m.doctor  where c.report_ID=".$reportID)[0];
        $pdfModel=new PDF(); 
        $addstr='';
        
        if(isset($reports['medication']) && $reports['medication']!=''){
            $addstr.="<div>Medication</div>
                    <div>".$reports['medication']."</div>";

        }
        if(isset($reports['allergies']) && $reports['allergies']!=''){
            $addstr.="<div>Allergies</div>
            <div>".$reports['allergies']."</div>";
        }
        if(isset($reports['note']) && $reports['note']!=''){
            $addstr.="<div>Note</div>
            <div>".$reports['note']."</div>";
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
                    <sesction>
                        <span>
                        
                            <h2 style='color:#38B6FF; font-size:32px;'>Anspaugh<font style='color:#1746A2;  font-size:32px;'>Care</font><br>
                            <font style='color:#1746A2;  font-size:22px;' > Channeling Center</font></h2>
                        

                        
                        <span>
                        
                    </section>
                    
                    <section  style='border:1px solid #38B6FF; padding:10px; border-radius:5px; '>
                        <div>Patient examination :".$reports['name']."</div>
                        <div>Patient Gender :".$reports['gender']."</div>
                        <div>Patient Age :".$reports['age']."</div><br>
                        
                        <div>Issued Doctor :".$report_doctor['name']."</div>"."
                        <div>Issued date :".$reports['uploaded_date']."</div>
                        
                    </section>
                    <div><center><h2>General Medical History</h2></center> </div>
                    <section><br><br>
                    ".$addstr."
                    </section>
                </body>
            <html>
        ";
        $pdfModel->createPDF($str,'reports-'.$reports['uploaded_date']);
        
    }

    
}   



?>