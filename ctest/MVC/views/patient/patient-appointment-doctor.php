
<?php

use app\core\component\Component;
use app\core\Application;
use app\core\Calendar;
use app\core\Time;
use app\models\Appointment;
$component=new Component();
use app\models\OpenedChanneling;
use app\models\Patient;

    $openedChanneling=new OpenedChanneling();
    $appointmentModel=new Appointment();
    $calendarModel=new Calendar();
    $patientModel=new Patient();
    $timeModel=new Time();
   
       
    
?>
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
                        <h3>Time :<?=$timeModel->time_format($value['time'])?></h3>
                        <h3>Date :<?=$value['channeling_date']?></h3>
                        <h3>Fee :LKR <?=number_format($value['fee'],2,'.','')?></h3>
                    </div>
                    <?php if(Application::$app->session->get('user')): ?>
                    <div>
                    <?php if($openedChanneling->isPatientIn(Application::$app->session->get('user'),$value['opened_channeling_ID'])):?>
                                <?php echo "Already have an appointment" ?>
                        <?php elseif($value['total_patients']!=0 && $value['remaining_appointments']<=0): ?>
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
                    <?php endif;?>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>

</div>

<script>

        let elementsArray = document.querySelectorAll(".button--class-1");
 ;
        elementsArray.forEach(function(elem) {
            elem.addEventListener("click", function() {
                location.href='patient-appointment?cmd=add&id='+elem.id;
               ;
            });
        });
        let elementsArrayTwo=document.querySelectorAll('.button--class-5')
        elementsArrayTwo.forEach(function(elem) {
            elem.addEventListener("click", function() {
                location.href='patient-appointment?cmd=add&id='+elem.id+'&type=labtest';
               
            });
        });
     
   </script>