<?php
namespace app\core;

use app\models\PastChanneling;
use app\models\Employee;
use app\core\Date;

// class to handle time formats
class SummaryReportsPatients{

    public function patientsReport(){
        $patient=new Employee();
        $pdfModel=new PDF();
        $dateModel=new Date();
        $str1="";
        $str2="";
        $day=(int)Date('d')+1;
        $month=(int)Date('m');
        $year=(int)Date('Y');
        $lowdate=$dateModel->arrayToDate([01,$month,$year]);
        $update=$dateModel->arrayToDate([$day,$month,$year]);


        $doctorWisePatients = $patient->customFetchAll("SELECT employee.name, COUNT(appointment.patient_ID) FROM `appointment` INNER JOIN `opened_channeling` ON appointment.opened_channeling_ID = opened_channeling.opened_channeling_ID INNER JOIN `channeling` ON opened_channeling.channeling_ID = channeling.channeling_ID INNER JOIN `employee` ON channeling.doctor = employee.nic WHERE opened_channeling.channeling_date>='$lowdate' and opened_channeling.channeling_date<'$update' GROUP BY channeling.doctor");

        $specialityWisePatients = $patient->customFetchAll("SELECT speciality, COUNT(appointment.patient_ID) FROM `appointment` INNER JOIN `opened_channeling` ON appointment.opened_channeling_ID = opened_channeling.opened_channeling_ID INNER JOIN `channeling` ON opened_channeling.channeling_ID = channeling.channeling_ID WHERE opened_channeling.channeling_date>='$lowdate' and opened_channeling.channeling_date<'$update' GROUP BY channeling.speciality");
        
        // var_dump($specialityWisePatients);exit;
        
        foreach($doctorWisePatients as $doctorWisePatient){
            $name=$doctorWisePatient['name'];
            $appointments=$doctorWisePatient['COUNT(appointment.patient_ID)'];
            $str1.="<tr><td>$name</td><td>$appointments</td><tr>";
        }

        foreach($specialityWisePatients as $specialityWisePatient){
            $speciality = $specialityWisePatient['speciality'];
            $patientCount = $specialityWisePatient['COUNT(appointment.patient_ID)'];
            $str2.="<tr><td>$speciality</td><td>$patientCount</td><tr>";
        }
        // var_dump($str2);exit;


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
                       <h3>Doctors Wise Patients Report</h3>
                    </div>
                    <div>
                        <table border='0'>
                            <tr><th>Doctor Name</th><th>Total Patients</th></tr>
                            ".$str1."
                        </tabel>
                    </div>

                    <div>
                       <h3>Speciality Wise Patients Report</h3>
                    </div>
                    <div>
                        <table border='0'>
                            <tr><th>Speciality</th><th>Total Patients</th></tr>
                            ".$str2."
                        </tabel>
                    </div>

        
                </section>
                </body>
                </html>
        ";
        $pdfModel->createPDF($html,"Channeling Report");
    }




}

