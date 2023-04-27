<?php

use app\core\Application;
use app\models\Appointment;

$appointmentModel=new Appointment();
?>
<div class="column-flex">
    <div class="main-detail-title">
        <h1><?=$channeling->speciality."-".$channeling->day?></h1>
    </div>
    <div class="number-content">
        <h2>Patients</h2>
        <div class="number-pad">
            <div class="number-item--white fs-200"><?=$appointmentModel->getUsedPatient($openedchanneling->opened_channeling_ID)?></div>
            <div class="number-item--blue fs-200"><?=$appointmentModel->getTotoalPatient($openedchanneling->opened_channeling_ID)?></div>
        </div>
    </div>
    <div class="scheduled-info fs-100">
        <span>Room :<?=$channeling->room?></span>
        <span>Starts At:<?=($channeling->time>='12:00')?$channeling->time.' PM':$channeling->time.' AM'?></span>

    </div>
    <div>
        <span class="fs-100">Assigned Nurses <?php $nurses=$nurse; ?></span>
        <div class="nurse-container">
            <?php foreach($nurse as $nurse):?>
                <div class="nurse-item">
                    <img src=<?='media/images/emp-profile-pictures/'.$nurse['img']?>>
                    <h3><?=$nurse['name']?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php if($openedchanneling->status=='finished'): ?>
        <button class="start2 button--class-0 start_button" id=<?=$openedchanneling->opened_channeling_ID?>>View</button>
        <?php else:?>
            <button class="start button--class-0 start_button" id=<?=$openedchanneling->opened_channeling_ID?>>Start</button>

    <?php endif;?>

</div>
<script>
    const btn=document.querySelector(".start");
    const btn2=document.querySelector(".start2");
    if(btn){
        btn.addEventListener('click',()=>{
            location.href="channeling-assistance?cmd=start&id="+btn.id;
        })
    }
    else if(btn2){
        btn2.addEventListener('click',()=>{
            location.href="channeling-assistance?cmd=start&id="+btn2.id;
        })
    }

</script>