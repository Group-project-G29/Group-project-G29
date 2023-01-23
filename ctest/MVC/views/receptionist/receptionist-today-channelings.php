<?php

use app\core\component\Component;

$component = new Component();
// var_dump($channelings);
// exit;
?>

<div class='upper-container'>
  <div class="search-bar-container">
    <?php echo $component->searchbar('','search','search-bar--class2','Search by name,specilaity','searchbar');?>
  </div>


</div>
<div class="table-container">
  <table border="0">

    <tr class="row-height header-underline">
      <th>Doctor</th>
      <th>Channeling</th>
      <th>Time</th>
      <th>Room</th>
      <th>Fee</th>

    </tr>


    <?php foreach ($channelings as $key => $channeling) : ?>
      
      
      <tr class='table-row  row-height hover' id=<?=$channeling['emp_ID']?>>
        <td><?= $channeling['name'] ?></td>
        <td><?= $channeling['speciality'] ?> </td>
        <td><?= $channeling['time'] ?> </td>
        <td><?= $channeling['room'] ?> </td>
        <td><?= $channeling['fee'] ?> </td>

      </tr>
    <?php endforeach; ?>




  </table>
</div>
<script>
  elementsArray = document.querySelectorAll(".table-row");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
    //   location.href = 'receptionist-all-channeling-more?spec=channeling&id='+elem.id;  //pass the variable value
    });
  });

  

</script>