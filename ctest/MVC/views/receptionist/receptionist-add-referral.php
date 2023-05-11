
<?php

use app\core\component\Component;
use app\core\form\Form;
$component=new Component();


?>

<div class="referral-popup" id="popup-main">
<div class="referral-popup-wrapper">
    <h3>Patient Queue No :<?=$appointment->queue_no?></h3>
    <div class="flex">
        <h4>Date:<?=$channeling['channeling_date']?></h4>
        <h4>Time:<?=$channeling['time']?></h4>
    </div>
    <h3 class="fs-50">Add any soft copy referal here. Please consider that referal should be clear and valid.</h3>
    <?php $form=Form::begin("receptionist-patient-appointment?mod=referral&id=".$appointment->appointment_ID,'post');?>
    <div style="display:flex; flex-direction:column; gap:2vh; align-items:center;">
    <?= $form->field($model,'name','Refarrel','field-input--class1 flex','file');?>
    <?=$component->button("Done","submit","Done","button--class-0",$appointment->appointment_ID);?>
    <!-- <?php $form=Form::end();?> -->
</div>        
</div>



<script>
   
    const bg=document.querySelector(".bg");
    popup_button=document.getElementById("done");
    bg.classList.add("background");
    popup.style.display="flex";
    url="";
    elementsArray = document.querySelectorAll(".button--class-1");
    

</script>