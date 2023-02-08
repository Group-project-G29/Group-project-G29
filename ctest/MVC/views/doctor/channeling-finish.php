<?php

use app\core\component\Component;

 $component=new Component(); ?>

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

 <?= $component->button('finish','','finish channeling session','button--class-2'); ?>