
<?php

use app\core\Application;
use app\core\Calendar;
use app\core\component\Component;
    use app\core\form\Form;
use app\models\Appointment;
use app\models\OpenedChanneling;

    $component=new Component();
    $openedChanneling=new OpenedChanneling();
    $appointmentModel=new Appointment();
    $calendarModel=new Calendar();
     

?>
<div class="bg">

</div>
<div class="referral-popup" id="popup-main">
    <div class="referral-popup-wrapper">
        <h3>Your Queue No :<?=$appointment->queue_no?></h3>
        <div class="flex">
            <h4>Date:<?=$channeling['channeling_date']?></h4>
            <h4>Time:<?=$channeling['time']?></h4>
        </div>
        <h3 class="fs-50">Add any soft copy referal here. Please consider that referal should be clear and valid.</h3>
        <?php $form=Form::begin("handle-referral?cmd=add&id=".$appointment->appointment_ID,'post');?>
        <?= $form->field($model,'name','Refarrel','field-input--class1 flex','file');?>
        <?=$component->button("Done","submit","Done","button--class-0",$appointment->appointment_ID);?>
        <?php $form=Form::end();?>
    </div>        
</div>

<div class="appointment-container ">
        <?php foreach($channelings as $key=>$value): ?>
            <?php if($value['channeling_date']>=Date('Y-m-d') && $calendarModel->addDaysToDate(Date('Y-m-d'),$value['open_before'])>=$value['channeling_date'] &&  ($value['status']!='closed' || $value['status']!='cancelled')): ?>
            <div class="item">
                <div class="item--left">
                    <div>
                        <img src=<?="./media/images/emp-profile-pictures/".$value['img']?>>
                    </div>
                    <div class="about-info">
                        <h3>DR. <?=$value['name']?></h3>
                        <h3><?=$value['speciality']?></h3>
                        <p><?=$value['description']?></p>
                        
                    </div>
                </div>
                <div class="item--right">
                    <div class="channeling-vital-info">
                        <h3>Time :<?=$value['time']?></h3>
                        <h3>Date :<?=$value['channeling_date']?></h3>
                        <h3>Fee :LKR <?=$value['fee']?></h3>
                    </div>
                    <div>
                       
                       <?php if($openedChanneling->isPatientIn(Application::$app->session->get('user'),$value['opened_channeling_ID'])):?>
                               <?php echo "Already have an appointment" ?>
                       <?php elseif($value['total_patients']!=-1 && $value['remaining_appointments']<=0): ?>
                               <?php echo "All appointments has been taken" ?>
                       <?php elseif($value['status']!='Opened'): ?>
                           <?php echo "Not taking any appointments" ?>
                       <?php elseif(!$openedChanneling->isPatientIn(Application::$app->session->get('user'),$value['opened_channeling_ID'])):?>
                           <?= $component->button('add-appointment','','+ Add Consultation Appointment','button--class-1 width-10',$value['opened_channeling_ID']);?>
                       <?php if($appointmentModel->labReportEligibility(Application::$app->session->get('user'),$value['nic'],$value['opened_channeling_ID'])):?>
                           <?= $component->button('add-appointment','','+ Add Medical Report Consultation','button--class-5 width-10',$value['opened_channeling_ID']);?>
                       <?php endif; ?>
                       <?php endif; ?>
                   </div>
               </div>
           </div>
           <?php endif; ?>
            
            
        <?php endforeach; ?>
  
</div>

<script>
       
        const bg=document.querySelector(".bg");
        popup_button=document.getElementById("done");
        bg.classList.add("background");
        popup.style.display="flex";
        url="";
        elementsArray = document.querySelectorAll(".button--class-1");
        
        elementsArray.forEach(function(elem) {
            elem.addEventListener("click", function() {
                url='patient-appointment?mod=add&id='+elem.id;
                div.style.display="flex";
                bg.classList.add("background");
            });
        });
     
   </script>