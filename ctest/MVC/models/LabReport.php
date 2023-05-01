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
                
                'location'=>[self::RULE_REQUIRED]
    
            ];
        }
        public function fileDestination(): array
        {
            return ['location'=>'/media/patient/labreports'];
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
            return $this->fetchAssocAllByName(['patient_ID'=>$patient],'lab_report_allocation');
        }
        public function getReport($reportID){
            return $this->customFetchAll("select * from  lab_report_content left join lab_report_template on lab_report_content.template_ID=lab_report_template.template_ID left join  lab_report_content_allocation as l on l.content_ID=lab_report_content.content_ID where l.report_ID=".$reportID." order by lab_report_content.position asc");
        }
        public function distinctPatientTests($patient){
            return $this->customFetchAll("SELECT distinct(l.content_ID),c.name FROM  lab_report_content_allocation  as l left join lab_report_content as c  on c.content_ID=l.content_ID left join lab_report_allocation as a on a.report_ID=l.report_ID left join lab_report as r on  r.report_ID=l.report_ID where a.patient_ID=".$patient);
        }
        public function getTestValue($patient,$testID){
            return $this->customFetchAll("SELECT * FROM  lab_report_content_allocation  as l left join lab_report_content as c  on c.content_ID=l.content_ID left join lab_report_allocation as a on a.report_ID=l.report_ID left join lab_report as r on  r.report_ID=l.report_ID where a.patient_id=".$patient." and l.content_ID=".$testID);
        }
        public function getAllParameterValue($patient){
            $contents=$this->distinctPatientTests($patient);
            $contentArray=[];
            foreach($contents as $content){
                $contentArray[$content['name']]=$this->getTestValue($patient,$content['content_ID']);
            }
            return $contentArray;
        }
        public function makeChartInputs($array){
            $labels=[];
            $values=[];
            $mainarray=[];
            foreach($array as $element){
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
            //get patient and doctor from request table and send data to lab_report_alloction $this->customFetchAll("select last_insert_id()"[0]['last_insert_id']
 /*change*/ $this->customFetchAll("INSERT INTO lab_report ( fee, type,label,template_ID,location,request_ID) VALUES ( $fee, 'e-report', '$label', $template_ID, '$location', $request_ID); ");
            return $this->customFetchAll("select last_insert_id()")[0]['last_insert_id()'];
        }
        public function payment($patient_ID,$amount,$generated_timestamp, $type,$name,$payement_status,$order_ID,$appointment_ID){
            //get patient and doctor from request table and send data to lab_report_alloction $this->customFetchAll("select last_insert_id()"[0]['last_insert_id']
            return $this->customFetchAll("INSERT INTO payment (patient_ID,amount,generated_timestamp, type,name,payement_status,order_ID,appointment_ID) VALUES ( $patient_ID,$amount,current_timestamp(), 'labreport', '$name', NONE, NONE, NONE); ");
        }

        public function create_report_allocation($report_ID,$patient_ID,$doctor){
            return $this->customFetchAll("INSERT INTO lab_report_allocation ( report_ID,patient_ID,doctor) VALUES ( $report_ID, $patient_ID,$doctor) ");

        }


        public function isreport($request_ID){
            return $this->customFetchAll("SELECT report_ID from lab_report where request_ID=$request_ID");
        }

        public function labreporttoPDF($reportID){
            $valuerows=$this->getReport($reportID);
            $addstr='<tr><td>Parameter</td><td>Test Value</td><td>Reference Range</td></tr>';
            $title='';
            $subtitle='';
            $date='';
            foreach($valuerows as $row){
                $title=$row['title'];
                $subtitle=$row['subtitle'];
                $date=$row['created_date'];
                if($row['type']=='field'){
                    $addstr.="<tr><td>".$row['name']."</td><td>".$row['int_value']."</td><td> ".$row['metric']."</td></tr>";
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
                           <h1>".$title."</h1>
                           <h2>".$subtitle."</h2>
                            
                        </section>
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