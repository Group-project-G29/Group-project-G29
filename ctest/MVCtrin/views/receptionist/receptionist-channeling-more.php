<?php

use app\core\component\Component;
use app\models\Employee;
// var_dump(($channelingmore));
// exit;

$component = new Component();
 
?>
<div class="header-container" style="padding-top:0px">
<div class="header-name" ><h2>Dr.<?=$channelingmore[0]['name']?></h2></div>
<div class="semi-header-name"><h3><?=$channelingmore[0]['speciality']?> Channeling</h3></div>
</div>
<div class="table-container">
  <table border="0" style="margin-left:0px">

    <tr class="row-height header-underline">
      <th>Channeling Type</th>
      <th>Date</th>
      <th>Time</th>
      <th>Room</th>
      <!-- <th>Status</th> -->


    </tr>


    <?php foreach ($channelingmore as $key => $channeling) : ?>
      <?php $time="12.00"?>
      <tr class='table-row  row-height hover' id=<?=$channeling['emp_ID']?>>
        <td><?= $channeling ['speciality']?></td>
        <td>Every <?= $channeling['day'] ?>s </td>
        <td><?php if ($channeling['time']<$time){
          echo $channeling['time']." A.M";}
          else{echo $channeling['time']." P.M";}?> </td>
        <td><?= $channeling['room'] ?> </td>
        <!-- <td> </td> -->

      </tr>
    <?php endforeach; ?>




  </table>
</div>
<script>
  elementsArray = document.querySelectorAll(".table-row");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'receptionist-channeling-session-detail?id='+elem.id;  //pass the variable value
    });
  });
</script>