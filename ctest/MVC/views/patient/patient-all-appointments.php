<?php

use app\core\Application;
use app\core\component\Component;
    use app\core\form\Form;
use app\models\Appointment;
use app\models\Payment;
$component=new Component();
    $popup=$component->popup("Are you sure you want to delete the channeling session","popup-value","popup--class-1","yes");
    echo $popup;
    $appointmentModel=new Appointment();
    $paymentModel=new Payment();
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
                    <h3><?='Queue Number :'.$channeling['queue_no']?></h3>  
                    <h3><?='Doctor :'.$channeling['name']?></h3>  
                    <h3><?='Speciality :'.$channeling['speciality']?></h3>
                    <h4><?='Channeling date :'.$channeling['channeling_date']?></h4>
                    <h4><?='Channeling time :'.$channeling['time'].(($channeling['time']>'12:00')?' PM':' AM')?></h4> 
                    <div class="flex"><h4><?='Fee :LKR '.number_format($channeling['fee'],2,'.','')." "?></h4>
                    <?php $status=$paymentModel->fetchAssocAll(['appointment_ID'=>$channeling['appointment_ID']])[0]['payment_status']?>
                    <?php if($status!='done'):?>
                        <div class="small-link-w"><a href=<?="patient-dashboard?spec=payments&sel_payment=".$channeling['appointment_ID'] ?>>Pay Now</a></div></div>
                    <?php else:?>
                        </div>
                    <?php endif;?>
                    
                </div>
                <div>
                    <?php echo $component->button('referral',' ','Change Referrals','button--class-app',$channeling['appointment_ID']) ?>
                </div>
            </div>
            <?php else:?>
                <div class="btn-content">
                    <div class="patient-appointment-tile--2">
                        <img src="media/images/common/delete.png" id=<?='"'.$channeling['appointment_ID'].'"'?>  class="image">
                        <h3><?='Queue Number :'.$channeling['queue_no']?></h3>  
                        <h3><?='Doctor :'.$channeling['name']?></h3>  
                        <h3><?='Speciality :'.$channeling['speciality']?></h3>
                        <h4><?='Channeling date :'.$channeling['channeling_date']?></h4>
                        <h4><?='Channeling time :'.$channeling['time'].(($channeling['time']>'12:00')?' PM':' AM')?></h4> 
                        <h5>*This appointment is free of charge and can only be used to show lab reports to doctor.</h5>
                    </div>
                    <div>
                        <?php echo $component->button('referral',' ','Change Referrals','button--class-app',$channeling['appointment_ID']) ?>
                    </div>
                    
                </div>
                <?php endif; ?>
                <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-container">
            
            <h3>Looks like <br>You Have No Appointment</h3>
            <img src="media/images/common/empty.jpg">
        </div>
        <?php endif; ?>
        <?php 
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
