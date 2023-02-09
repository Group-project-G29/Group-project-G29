<?php

use app\core\component\Component;

$component = new Component();
// var_dump($channelings);
// exit;
?>

<div class='upper-container'>
  <div class="search-bar-container">
    <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by name,specilaity', 'searchbar'); ?>
  </div>


</div>
<div class="main-card ">
  <?php foreach ($channelingmore as $key => $channeling) : ?>

      <div class="card-0" id=<?= $channeling['speciality'] ?>>
         
          <div class="card-header-1 " style="padding-top: 15vh;">

            <h1><?= $channeling['speciality'] ?> </h1>
          </div>

       

      </div>
    
  <?php endforeach; ?>

</div>

<!-- <div class="table-container">


  <table border="0">

    <tr class="row-height header-underline">
      <th>Doctor</th>
      <th>Type</th>

    </tr>


    <?php foreach ($channelings as $key => $channeling) : ?>
      
      
      <tr class='table-row  row-height hover' id=<?= $channeling['emp_ID'] ?>>
        <td><?= $channeling['name'] ?></td>
        <td><?= $channeling['speciality'] ?> </td>
      </tr>
    <?php endforeach; ?>




  </table>
</div> -->
<script>
  elementsArray = document.querySelectorAll(".card-0");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'receptionist-all-channeling-type?id=' + elem.id; //pass the variable value
    });
  });
</script>