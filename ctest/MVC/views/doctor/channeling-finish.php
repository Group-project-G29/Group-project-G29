<?php

use app\core\Application;
use app\core\component\Component;
use app\models\Appointment;

$appointmentModel=new Appointment();
 $component=new Component(); ?>
<div class="finish-page">
      <div class="number-pad">
               <div class="number-item--white">
                  <?=$appointmentModel->getUsedPatient(Application::$app->session->get('channeling'))?>
                  
               </div>
               <div class="number-item--blue">
                  <?=$appointmentModel->getTotoalPatient(Application::$app->session->get('channeling'))?>
               </div>
         </div>
         <section class="finish-patient-list">
            <?php foreach($appointments as $appointment): ?>
               <?php if($appointment['status']=='used'): ?>
                  <div class="finish-patient-item">
               <?php else: ?>
                  <div class="finish-patient-item-red">
               <?php endif;?>
                  
                  <?=($appointment['status']=='used')?$appointment['name']." ".' has Seen':$appointment['name'].' has not seen';?>
               
               </div>
            <?php endforeach; ?>
   </section>

   <?= $component->button('finish','','finish channeling session','button--class-2 finish-channeling'); ?>
</div>