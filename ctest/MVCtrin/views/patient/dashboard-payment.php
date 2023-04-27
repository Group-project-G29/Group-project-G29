<?php

use app\models\Appointment;
use app\models\LabReport;
use app\models\Payment;

    $payment=new Payment();
    $appointmentModel=new Appointment();
    $labreport=new LabReport();
    $total=0;

?>

<section class="payment-lslip">
    <div class="all-payment-holder">
        <table border='0' class="payment-table">
            <tr class="payment-lslip-header"><th class="width-10">Payment</th><th class="width-10">Created Timestamp</th><th>Price</th><th class="width-10">Status</th></tr>
            <?php foreach($payments as $payment):?>
                <?php 
                    switch($payment['type']){
                        case 'order':
                            $name='Medicine Order-'.$payment['order_ID'];
                            break;
                        case 'appointment':
                            $appointmentDetail=$appointmentModel->getAppointmentDetail($payment['appointment_ID'])[0];
                            $name="Dr.".$appointmentDetail['name']." channeling appointment";
                            break;
                        case 'labreport':
                            $labreportDetail=$labreport->getReport($payment['name']);
                            $name=$labreportDetail[0]['title'];
                            break;

                    }    
                    
                    
                ?>
                <tr class="payment-lslip-tr"><td class="width-10" align='left'><?="$name"?></td><td class="width-10" align='center'><?=$payment['generated_timestamp']?></td><td class="width-10" align='center'><?="LKR ".$payment['amount'].".00"?></td><td class="width-10" align='center'><?=$payment['payment_status'] ?></td></tr>
                <?php endforeach;?>
                <?php $total=$total+$payment['amount'] ?>
            </table>
        </div>
        <div class="total-payment">
            <h3><?="Total Payment : LKR ".$total.".00"?></h3>
        </div>
</section>