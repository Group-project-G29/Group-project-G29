<?php

use app\core\component\Component;

$component = new Component();
// var_dump($user);
?>
<div class="search-bar-container">
  <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by name,specilaity', 'searchbar'); ?>
</div>
<div class="button-1" style="margin-left: 35%; margin-top:2vh;">
  <?php echo $component->button('edit-details', '', 'Add New Test', 'button--class-0  width-10', 'edit-details'); ?>
</div>
<?php foreach ($tests as $test) : ?>

  <div class="detail-container">

    <table class="table-session">

      <tr class="table-row-session">
        <td>Name</td>
        <td>:</td>
        <td class="table-row-data"><?= $test['name'] ?></td>
      </tr>
      <tr class="table-row-session">
        <td>Fee</td>
        <td>:</td>
        <td class="table-row-data">LKR.<?= $test['fee'] ?></td>
      </tr>
    </table>

    <div class="button" style="margin-left: 35%;" id=<?=$test['name']?>>
      <?php echo $component->button('edit-details', '', 'Edit Details', 'button--class-0  width-10', 'edit-details'); ?>
    </div>
    <div class="button-0" style="margin-left: 35%;" id=<?=$test['name']?>>
      <?php echo $component->button('edit-details', '', 'Delete', 'button--class-0  width-10', 'edit-details'); ?>
    </div>

  </div>
<?php endforeach; ?>






<script>
  elementsArray = document.querySelectorAll(".button-1");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-add-new-test'; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-test-update?mod=update&id='+elem.id; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button-0");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-test-delete?cmd=delete&id='+elem.id; //pass the variable value
    });
  });
</script>