<?php

use app\models\Channeling;

    $cm=new Channeling();
    $cm->doctor='200045456060';
    $cm->fee=900;
    $cm->room='curtain-02';
    $cm->day='Sunday';
    $cm->time='18:10';
    $cm->start_date='2023-04-18';
    $cm->schedule_for=1;
    $cm->schedule_type='months';
    $cm->percentage=70;
    $cm->session_duration='06:00';

    //$cm->checkRoomOverlap();

?>
<div class="result">
        <?php var_dump($cm->checkRoomOverlap('curtain-02',$cm)); ?>
</div>