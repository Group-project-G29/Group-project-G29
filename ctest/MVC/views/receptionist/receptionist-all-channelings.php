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
      <th>Type</th>

    </tr>


    <?php foreach ($channelings as $key => $channeling) : ?>
      
      
      <tr class='table-row  row-height hover' id=<?=$channeling['emp_ID']?>>
        <td><?= $channeling['name'] ?></td>
        <td><?= $channeling['speciality'] ?> </td>
      </tr>
    <?php endforeach; ?>




  </table>
</div>
<script>
  elementsArray = document.querySelectorAll(".table-row");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'receptionist-channeling-more?id='+elem.id;  //pass the variable value
    });
  });

  

</script>