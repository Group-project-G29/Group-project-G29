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

 <section class="finish-patient-list">
   <div>
      <table>
         <tr>
            <th>Patient Name</th><th>Appointment Status</th>
         </tr>
         <?php foreach($appointments as $appointment): ?>
            <tr>
               <td><?=$appointment['name']?></td><td><?=($appointment['status']=='used')?'Completed':'Not Seen';?></td>
            </tr>
         <?php endforeach; ?>
      </table> 
      </div>
 </section>


 <?= $component->button('finish','','finish channeling session','button--class-2','finish'); ?>
<script>
   const fbtn=document.getElementById("finish");  
   fbtn.addEventListener('click',()=>{
      location.href="channeling-assistance?spec=pre-channeling-test&cmd=channeling-finish";
   })       
</script> 