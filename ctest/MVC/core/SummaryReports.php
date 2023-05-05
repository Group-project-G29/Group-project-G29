<?php
namespace app\core;

use app\models\PastChanneling;

// class to handle time formats
class SummaryReports{

    public function pastChanneling($doctor){
        $past=new PastChanneling();
        $pdfModel=new PDF();
        $str="";
        $count=0;
        $channelings=$past->customFetchAll("select * from past_channeling left join opened_channeling on opened_channeling.opened_channeling_ID=past_channeling.opened_channeling_ID left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join employee on employee.nic=channeling.doctor where channeling.doctor=".$doctor);
        foreach($channelings as $channeling){
            $count=$count+1;
            if($count==13) break;
            $date=$channeling['channeling_date'];
            $time=$channeling['time'];
            $patientcount=$channeling['no_of_patient']+$channeling['free_appointments'];
            $total_income=$channeling['total_income'];
            $free_appointments=$channeling['free_appointments'];
            $end_time=$channeling['end_time'];
            $str.="<tr><td>$date</td><td>$patientcount</td><td>LKR ".number_format($total_income,2,'.','')."</td><td>$free_appointments</td><td>".substr($time,0,5).((substr($time,0,5)>='12:00')?"PM":"AM")."-".substr($end_time,0,5).((substr($end_time,0,5)>='12:00')?"PM":"AM")."</td><tr>";
        }
        $html="<html>
        <head>
        <style>
            .show{    
              background-color:red;
            }
            th,td{
                width:140px;
                height:30px;
                text-align:center;
            }
            table,td{
                border:1px solid black;
            }
        </style>
        </head>
        <body>
                <section>
                <span>
                        
                            <h2 style='color:#38B6FF; font-size:32px;'>Anspaugh<font style='color:#1746A2;  font-size:32px;'>Care</font><br>
                            <font style='color:#1746A2;  font-size:22px;' > Channeling Center</font></h2>
                        

                        
                        <span>
                    <div>
                       <h3>Channeling Report-Dr. ".$channeling['name']."</h3>
                    </div>
                    <div>
                        <table border='0'>
                            <tr><th>Channeling Date</th><th>Patient Count</th><th>Total Income</th><th>Free Appointments</th><th>Channeling Duration</th></tr>
                            ".$str."
                        </tabel>
                    </div>
        
                </section>
                </body>
                </html>
        ";
        $pdfModel->createPDF($html,"Channeling Report-".$channeling['doctor']);
    }




}

