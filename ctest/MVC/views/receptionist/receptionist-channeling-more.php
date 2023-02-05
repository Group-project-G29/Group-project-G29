<?php

use app\core\component\Component;
use app\models\Employee;
// var_dump(($channelingmore));
// exit;

$component = new Component();
 
?>
<div class="header-container">
<div class="header-name"><h2>Dr.<?=$channelingmore[0]['name']?></h2></div>
<div class="semi-header-name"><h3><?=$channelingmore[0]['speciality']?> Channeling</h3></div>
</div>
<div class="table-container">
  <table border="0">

    <tr class="row-height header-underline">
      <th>channeling</th>
      <th>Date</th>
      <th>Time</th>
      <th>Room</th>
      <th>Status</th>


    </tr>


    <?php foreach ($channelingmore as $key => $channeling) : ?>
      
      <tr class='table-row  row-height hover' id=<?=$channeling['emp_ID']?>>
        <td><?= $channeling ['speciality']?></td>
        <td>All <?= $channeling['day'] ?> </td>
        <td><?= $channeling['time'] ?> </td>
        <td><?= $channeling['room'] ?> </td>
        <td> </td>

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