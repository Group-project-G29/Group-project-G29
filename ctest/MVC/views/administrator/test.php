<?php

use app\models\Appointment;
use app\models\Cart;
use app\models\Channeling;

    $cm=new Channeling();
    $cart=new Cart();
    $cm->doctor='200045456060';
    $cm->fee=900;
    $cm->room='curtain-02';
    $cm->day='Sunday';
    $cm->time='18:10';
    $cm->start_date='2023-04-22';
    $cm->schedule_for=1;
    $cm->schedule_type='months';
    $cm->percentage=70;
    $cm->session_duration='06:00';
    $cm->speciality='Gat'

    //$cm->checkRoomOverlap();
    
    ?>
    <div class="result">

            <?//php var_dump($cm->checkOverlap()); ?>
    </div>

<!-- 
<?php

    //$appointmentModel=new Appointment();
    //var_dump($appointmentModel->labReportEligibility(34,'200045456060',165));

?> -->

<?php  
    $cart->getCartPrice();


?>