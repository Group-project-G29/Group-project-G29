<?php

use app\core\Application;
use app\core\component\Component;
    use app\core\form\Form;
use app\models\Appointment;

$component=new Component();
    $popup=$component->popup("Are you sure you want to delete the channeling session","popup-value","popup--class-1","yes");
    echo $popup;
    $appointmentModel=new Appointment();
    ?>
<!-- 
    <div class="filter-holder">
        <?php 
        echo $component->filtersortby('','',[],['Speciality'=>'speciality','Doctor'=>'Doctor']);
        ?>
</div> -->
<div class="appointment-container">
    <?php if($channelings):?>
        
        <?php foreach($channelings as $key=>$channeling): ?>
            <?php if($appointmentModel->isInPass($channeling['appointment_ID'])): ?>
                <?php if($channeling['type']=='consultation'):?>
                <div class="btn-content">
                <div class="patient-appointment-tile--1">
                    <img src="media/images/common/delete.png" id=<?='"'.$channeling['appointment_ID'].'"'?> class="image">
                    <h3><?='Doctor :'.$channeling['name']?></h3>  
                    <h3><?='Speciality :'.$channeling['speciality']?></h3>
                    <h4><?='Channeling date :'.$channeling['channeling_date']?></h4>
                    <h4><?='Channeling time :'.$channeling['time']?></h4> 
                    
                </div>
                <div>
                    <?php echo $component->button('referral',' ','Change Referrals','button--class-app',$channeling['appointment_ID']) ?>
                </div>
            </div>
            <?php else:?>
                <div class="btn-content">
                    <div class="patient-appointment-tile--2">
                        <img src="media/images/common/delete.png" id=<?='"'.$channeling['appointment_ID'].'"'?>  class="image">
                        <h3><?='Time :'.$channeling['name']?></h3>  
                        <h3><?='Speciality :'.$channeling['speciality']?></h3>
                        <t4><?='Channeling date :'.$channeling['channeling_date']?></t4>
                        <t4><?='Channeling time :'.$channeling['time']?></t4> 
                        <div>
                            <?php echo $component->button('referral',' ','Change Referrals','button--class-4',$channeling['appointment_ID']) ?>
                        </div>
                        
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-container">
            Empty
        </div>
        <?php endif; ?>
        <?php 
        use app\models\Payment;
    $paymentModel=new Payment();
   // echo $paymentModel->payNow(100,'noth',Application::$app->session->get('userObject'),'345'); ?>
</div>

<script>
    elementsArray = document.querySelectorAll(".image");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            url='handle-appointment?cmd=delete&id='+elem.id;
            div.style.display="flex";
            bg.classList.add("background");
        });
    });
    yes.addEventListener("click",()=>{
        location.href=url;
    })
    const updates=document.querySelectorAll('.button--class-app');
    console.log(updates);
    updates.forEach(function(elem){
        elem.addEventListener('click',()=>{
            location.href="patient-appointment?spec=referral&mod=update&id="+elem.id;
        })
    })
</script>
