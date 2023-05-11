<?php 
    namespace app\models;

    use app\core\DbModel;
use app\core\PDF;

    class LabReport extends DbModel{
        public string $fee='';
        public string $type='';
        public string $label='';
        public string $template_ID='';
        public string $location='';
        public string $request_ID='';
    
        public function addReport()
        {
            parent::save();
        }
    
        public function rules(): array
        {
            return [
                
          
            'fee'=>[self::RULE_REQUIRED,self::RULE_NUMBERS,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>100000000000]],



        
    
            ];
        }
        public function fileDestination(): array
        {
        
            return ['location'=>'media/patient/labreports/'.$this->location];
        }
        public function tableName(): string
        {
            return 'lab_report';
        }
        public function primaryKey(): string
        {
            return 'report_ID';
        }
        public function tableRecords(): array{
            return ['lab_report'=> ['type','fee','label','template_ID','location','request_ID']];
        }

        public function attributes(): array
        {
            return  ['type','fee','label','template_ID','location','request_ID'];
        }
        public function getPatientReport($patient){
            return $this->customFetchAll("SELECT * from lab_report_allocation left join lab_report on lab_report_allocation.report_ID=lab_report.report_ID where lab_report_allocation.patient_ID=".$patient);
        }
        public function getReport($reportID){
            return $this->customFetchAll("select * from  lab_report_content left join lab_report_template on lab_report_content.template_ID=lab_report_template.template_ID left join  lab_report_content_allocation as l on l.content_ID=lab_report_content.content_ID where l.report_ID=".$reportID." order by lab_report_content.position asc");
        }
        public function getReportByRequest($request_ID){
           return  $this->customFetchAll("SELECT * from lab_request left join lab_report on lab_request.request_ID=lab_report.request_ID left join lab_report_template on lab_report_template.template_ID=lab_report.template_ID where lab_request.request_ID=".$request_ID);
        }
        public function distinctPatientTests($patient,$template_ID){
            return $this->customFetchAll("SELECT distinct(l.content_ID),c.name FROM  lab_report_content_allocation  as l left join lab_report_content as c  on c.content_ID=l.content_ID left join lab_report_allocation as a on a.report_ID=l.report_ID left join lab_report as r on  r.report_ID=l.report_ID where a.patient_ID=".$patient." and r.template_ID=".$template_ID);
        }
        public function getTestValue($patient,$testID){
            return $this->customFetchAll("SELECT * FROM  lab_report_content_allocation  as l left join lab_report_content as c  on c.content_ID=l.content_ID left join lab_report_allocation as a on a.report_ID=l.report_ID left join lab_report as r on  r.report_ID=l.report_ID where a.patient_id=".$patient." and l.content_ID=".$testID);
        }
  
        //changed
        public function getAllParameterValue($patient,$template_ID){
            $contents=$this->distinctPatientTests($patient,$template_ID);
            $contentArray=[];
            $patientModel=new Patient();
            foreach($contents as $content){
                if($patientModel->isValidParam($content['content_ID'])){
                    $contentArray[$content['name']]=[$this->getTestValue($patient,$content['content_ID'])];

                }
            }
        
            return $contentArray;
        }
        public function makeChartInputs($array){
            $labels=[];
            $values=[];
            $mainarray=[];
            foreach($array[0] as $element){
                array_push($labels,$element['upload_date']);
                array_push($values,$element['int_value']);
                
            }
            $mainarray['labels']=$labels;
            $mainarray['values']=$values;
            return $mainarray;
        }
        public function getAllLabReports($patient){
            $reports=$this->getPatientReport($patient);
            $result=[];
            foreach($reports as $report){
                $result[$report['report_ID']]=$this->getReport($report['report_ID']);
            }
            return $result;
        }
        public function getTitle($report_ID){
            return $this->customFetchAll("select lab_report_template.title from lab_report left join lab_report_template on lab_report.template_ID=lab_report_template.template_ID where lab_report.report_ID=".$report_ID)[0]['title'];
        }
        public function getUploadedDate($report_ID){
            return $this->fetchAssocAll(['report_ID'=>$report_ID])[0]['upload_date'];
        }
        public function getCreatedDate($report_ID){
            return $this->customFetchAll("select upload_date from lab_report  where lab_report.report_ID=".$report_ID)[0]['upload_date'];
        }
        public function get_report_by_ID($request_ID){
            return $this->customFetchAll(" SELECT * FROM lab_report where request_ID=$request_ID");

    
        } 
        public function create_new_report($fee, $type, $label, $template_ID, $location, $request_ID){
            $labTestModel=new LabTest();
            //get total fee 
            $values=$labTestModel->fetchAssocAll(['template_ID'=>$template_ID])[0];
            $fee=$values['hospital_fee']+$values['test_fee'];
            $this->customFetchAll("INSERT INTO lab_report ( fee, type,label,template_ID,location,request_ID) VALUES ( $fee, 'e-report', '$label', $template_ID, '$location', $request_ID); ");
            $report_ID=$this->customFetchAll("select last_insert_id()")[0]['last_insert_id()'];
            //create record in  test allocation table
            $labRequestModel=new LabTestRequest();
            //get information from lab request table
            $request=$labRequestModel->fetchAssocAll(['request_ID'=>$request_ID])[0];
            $doctor=$request['doctor'];
            $patient=$request['patient_ID'];
            $report=$report_ID;
            $this->customFetchAll("INSERT INTO lab_report_allocation (report_ID,patient_ID,doctor) values($report,$patient,'$doctor')");
            return $report_ID;
        }
        public function payment($patient_ID,$amount,$request_ID){
            //get patient and doctor from request table and send data to lab_report_alloction $this->customFetchAll("select last_insert_id()"[0]['last_insert_id']
            return $this->customFetchAll("INSERT INTO payment (patient_ID,amount,type,payment_status,request_ID) VALUES ( $patient_ID,$amount,'lab', 'pending', $request_ID); ");
        }

        public function create_report_allocation($report_ID,$patient_ID,$doctor){
            return $this->customFetchAll("INSERT INTO lab_report_allocation ( report_ID,patient_ID,doctor) VALUES ( $report_ID, $patient_ID,$doctor) ");

        }


        public function isreport($request_ID){
            return $this->customFetchAll("SELECT report_ID from lab_report where request_ID=$request_ID");
        }
        public function isAParameter($template_ID,$content_ID){
            $result=$this->fetchAssocAllByName(['template_ID'=>$template_ID,'content_ID'=>$content_ID],' lab_report_content_allocation ');
            if($result){
                return true;
            }
            else return false;
        }

        //show any labreport in PDF format
        public function labreporttoPDF($reportID){
            $valuerows=$this->getReport($reportID);
            $request=$this->customFetchAll("select * from lab_report left join lab_request on lab_report.request_ID=lab_request.request_ID where lab_report.report_ID=".$reportID)[0];
            $doctor=$this->customFetchAll("select * from lab_report_allocation left join employee on employee.nic=lab_report_allocation.doctor  where lab_report_allocation.report_ID=".$reportID)[0];
            $patient=$this->customFetchAll("select * from lab_report_allocation  left join patient on patient.patient_ID=lab_report_allocation.patient_ID where lab_report_allocation.report_ID=".$reportID)[0];
            $doctorname=$doctor['name'];
            $requestdate=explode(" ",$request['requested_date_time'])[0];
            $patientname=$patient['name'];
            $patientage=$patient['age'];
            $patientgender=$patient['gender'];
            $issued_date=$this->fetchAssocAll(['report_ID'=>$reportID])[0]['upload_date'];
            $addstr='<tr><td>Parameter</td><td>Test Value</td><td>Metric</td><td>Reference Range</td></tr>';
            $title='';
            $subtitle='';
            $date='';
            foreach($valuerows as $row){
                $title=$row['title'];
                $subtitle='';
                if($row['subtitle']){

                    $subtitle=$row['subtitle'];
                }
                $date=$row['created_date'];
                if($row['type']=='field'){
                    $addstr.="<tr><td>".$row['name']."</td><td>".$row['int_value']."</td><td> ".$row['metric']."</td><td> ".$row['reference_ranges']." ".$row['metric']."</td></tr>";
                }
                elseif($row['type']=='text'){
                    $addstr.="<tr colspan='3'><td>".$row['name']."<br>".$row['text_value']."</td></tr>";
                }
                else{
                    $addstr.="<tr colspan='3'><td>".$row['name']."<br>"."<img src='media/images/patient/labreportcontent/".$row['location']."'>"."</td></tr>";
                }
            }
            $pdfModel=new PDF();
                    
            $str="
                <html>
                    <head>
                    <style>
                        .show{    
                        background-color:red;
                        }
                        td{
                            width:150px;
                            height:30px;
                        }
                        .tab1 td{
                            width:130px;
                            height:10px;
                        }
                        .tab1 td{
                            width:130px;
                        }
                        .tab2 td{
                            width:180px;
                            margin-top:-30px;   
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
                            <table class='tab2'>
                                <tr>
                                <td>
                                <table class='tab1'>
                                <tr><td>"."Patient Name :"."</td><td>".$patientname."</td></tr>
                                <tr><td>"."Age :"."</td><td>".$patientage."</td></tr>
                                <tr><td>"."Gender :"."</td><td>".$patientgender."</td></tr>
                                <tr><td></td><td></td></tr>
                                <tr><td>"."Issued Date :"."</td><td>".$issued_date."</td></tr>
                                </table>
                                </td>
                                <td>
                                    <table class='tab1'>
                                        <tr><th></th><th></th>
                                        <tr><td>"."As per Request By :"."</td><td>Dr.".$doctorname."</td></tr>
                                        <tr><td>"."Requested Date :"."</td><td>".$requestdate."</td></tr>
                                    </table>
                                </td>
                                </tr>
                                </table>
                                </section>
                                <center><h3>".$title." ".$subtitle."</h3></center>
                        <section><br><br>
                        <table>
                        ".$addstr."
                        </table>
                        </section>
                    </body>
                <html>";
                $pdfModel->createPDF($str,'reports-'.$date);
            }
        }   



?>