<?php
namespace app\core;

use app\models\PastChanneling;
use app\models\Payment;
use app\core\Date;

// class to handle time formats
class SummaryReportsPayment{

    public function paymentReport(){
        $payment=new Payment();
        $pdfModel=new PDF();
        $dateModel=new Date();
        $str1="";
        $str2="";
        $str3="";
        $str4="";
        $day=(int)Date('d')+1;
        $month=(int)Date('m');
        $year=(int)Date('Y');
        $lowdate=$dateModel->arrayToDate([01,$month,$year]);
        $update=$dateModel->arrayToDate([$day,$month,$year]);


        $docWisePayments=$payment->customFetchAll("SELECT employee.name, SUM(total_income), SUM(doctor_income), SUM(center_income), SUM(no_of_patient) FROM `past_channeling` INNER JOIN `opened_channeling` ON past_channeling.opened_channeling_ID = opened_channeling.opened_channeling_ID INNER JOIN `channeling` ON opened_channeling.channeling_ID = channeling.channeling_ID INNER JOIN `employee` ON channeling.doctor = employee.nic WHERE past_channeling.created_date>='$lowdate' and past_channeling.created_date<'$update' GROUP BY channeling.doctor");
        
        $parmacyIncome = $payment->customFetchAll("SELECT SUM(amount), COUNT(payment.payment_status) FROM `payment` INNER JOIN `_order` ON payment.order_ID = _order.order_ID WHERE _order.completed_date>='$lowdate' and _order.completed_date<'$update' and payment.payment_status='Done' GROUP BY payment.payment_status");

        $laboratoryIncome = $payment->customFetchAll("SELECT SUM(amount), COUNT(payment.payment_status) FROM `payment` WHERE generated_timestamp>='$lowdate' and generated_timestamp<='$update' and payment.payment_status='Done' AND type = 'lab' GROUP BY payment.payment_status");
        
        // var_dump($labIncome);exit;
        
        $total_income = 0;
        $center_total_income = 0;
        $doctor_total_income = 0;
        foreach($docWisePayments as $docWisePayment){
            $name=$docWisePayment['name'];
            $appointments=$docWisePayment['SUM(no_of_patient)'];
            $doctor_income=$docWisePayment['SUM(doctor_income)'];
            $center_income=$docWisePayment['SUM(center_income)'];
            $total_income= $total_income + $docWisePayment['SUM(total_income)'];
            $center_total_income= $center_total_income + $center_income;
            $doctor_total_income= $doctor_total_income + $doctor_income;
            $str1.="<tr><td>$name</td><td>$appointments</td><td>LKR ".number_format($doctor_income,2,'.','')."</td><td>LKR ".number_format($center_income,2,'.','')."</td><tr>";
        }
        // var_dump($total_income, $center_income, $doctor_total_income);exit;

        $orderCount = $parmacyIncome[0]['COUNT(payment.payment_status)']??0;
        $orderIncome = $parmacyIncome[0]['SUM(amount)']??0;
        $str2.="<tr><td>$orderCount</td><td>LKR ".number_format($orderIncome,2,'.','')."</td><tr>";

        $labCount = $laboratoryIncome[0]['COUNT(payment.payment_status)']??0;
        $labIncome = $laboratoryIncome[0]['SUM(amount)']??0;
        $str3.="<tr><td>$labCount</td><td>LKR ".number_format($labIncome,2,'.','')."</td><tr>";


        $full_income = $total_income + $orderIncome + $labIncome;
        $center_full_income = $center_total_income + $orderIncome + $labIncome;
        $str4.="<tr><td>LKR ".number_format($doctor_total_income,2,'.','')."</td><td>LKR ".number_format($orderIncome,2,'.','')."</td><td>LKR ".number_format($labIncome,2,'.','')."</td><td>LKR ".number_format($center_full_income,2,'.','')."</td><td>LKR ".number_format($full_income,2,'.','')."</td><tr>";
        
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
                       <h3>Doctors Income Report</h3>
                    </div>
                    <div>
                        <table border='0'>
                            <tr><th>Doctor Name</th><th>Patient Count</th><th>Doctor Income</th><th>Institute Income</th></tr>
                            ".$str1."
                        </tabel>
                    </div>

                    <div>
                       <h3>Parmacy Income Report</h3>
                    </div>
                    <div>
                        <table border='0'>
                            <tr><th>Order Count</th><th>Total Income</th></tr>
                            ".$str2."
                        </tabel>
                    </div>

                    <div>
                       <h3>Laboratory Income Report</h3>
                    </div>
                    <div>
                        <table border='0'>
                            <tr><th>lab patients</th><th>Total Income</th></tr>
                            ".$str3."
                        </tabel>
                    </div>

                    <div>
                       <h3>Channeling Center Full Income Report</h3>
                    </div>
                    <div>
                        <table border='0'>
                            <tr><th>Income By Appointments</th><th>Pharmacy Income</th><th>Laboratory Income</th><th>Total Institute Income</th><th>Total Income</th></tr>
                            ".$str4."
                        </tabel>
                    </div>
        
                </section>
                </body>
                </html>
        ";
        $pdfModel->createPDF($html,"Channeling Report");
    }




}

