<?php 
namespace app\models;
use app\core\DbModel;
use app\core\Application;
use app\core\PDF;
use app\core\UserModel;

class ConsultationReport extends DbModel{
    public string $consultation='';
    public string $examination='';
    public string $recommendation='';
    public string $report_ID='';

    
    public function addReport($report_model){
        if($this->consultation!='' || $this->examination || $this->recommendation  ){
            $report_model->fixName();
            $id=$report_model->save();
            $this->report_ID=$id[0]['last_insert_id()'];
            return parent::save();
        }
        else return false;
    }
 
    public function rules(): array
    {
        return [];
    }
    public function fileDestination(): array
    {
        return [];
    }
    public function tablename(): string
    {
        return 'consultation_report';
    }
    public function primaryKey(): string
    {
        return 'report_ID';
    }
    public function tableRecords(): array{
        return ['consultation_report'=> ['report_ID','recommendation','examination','consultation']];
    }
    public function attributes(): array
    {
        return  ['report_ID','consultation','examintation','recommendation'];
    }

    public function getReports($patient,$doctor):array
    {
        return $this->customFetchAll(" SELECT m.report_ID,m.uploaded_date,m.report_ID,m.name,m.patient,m.doctor,m.type,c.examination,c.consultation,c.recommendation,h.note,h.medication,h.allergies,s.subjective,s.objective,s.assessment,s.plan,s.additional_note from medical_report as m left join consultation_report as c on c.report_ID=m.report_ID left join Medical_history as h on h.report_ID=m.report_ID left join soap_report as s on s.report_ID=m.report_ID where m.doctor=".$doctor." and m.patient=".$patient);
    }
    public function getAllReports($aptient){
        return $this->customFetchAll("  SELECT m.report_ID,m.uploaded_date,m.report_ID,m.name,m.patient,m.doctor,m.type,c.examination,c.consultation,c.recommendation,h.note,h.medication,h.allergies,s.subjective,s.objective,s.assessment,s.plan,s.additional_note from medical_report as m left join consultation_report as c on c.report_ID=m.report_ID left join Medical_history as h on h.report_ID=m.report_ID left join soap_report as s on s.report_ID=m.report_ID ");
    }
    public function ConsultationreportToPDF($reportID){
        $reports=$this->customFetchAll("Select c.examination, c.consultation,c.recommendation,m.uploaded_date,m.report_ID as report_name,p.name,p.age,p.gender  from consultation_report as c right join medical_report as m on m.report_ID=c.report_ID right join patient  as p on p.patient_ID=m.patient where m.report_ID=".$reportID)[0];
        $report_doctor=$this->customFetchAll("Select e.name from consultation_report as c right join medical_report as m on m.report_ID=c.report_ID right join employee as e on e.nic=m.doctor  where c.report_ID=".$reportID)[0];
        $pdfModel=new PDF();
        $addstr='';
        
        if(isset($reports['examination']) && $reports['examination']!=''){
            $addstr.="<div>Examination</div>
                    <div>".$reports['examination']."</div>";

        }
        if(isset($reports['consultation']) && $reports['consultation']!=''){
            $addstr.="<div>Consultation</div>
            <div>".$reports['consultation']."</div>";
        }
        if(isset($reports['recommendation']) && $reports['recommendation']!=''){
            $addstr.="<div>Recommendation</div>
            <div>".$reports['recommendation']."</div>";
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
                    <section><br><br>
                    ".$addstr."
                    </section>
                </body>
            <html>
        ";
        $pdfModel->createPDF($str,'reports-'.$reports['uploaded_date']);


    } 
    // <section style=\"background-image:url('.media/images/logo-1.png')\">
    //                     <img src='./logo-1.png'>
    //                 </section>
    
}   



?>