<?php

use app\core\component\Component;

$component = new Component();
// var_dump($user);
?>

<div class="search-bar-container" style="padding-left:19vw">
  <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by name,specilaity', 'searchbar'); ?>
</div>
<div class="sub-header-container" style="padding:2vh 0 2vh 0">

  <div class="button-1" style="margin-left: 35%;">
  <?php echo $component->button('edit-details', '', 'Add New Test', 'button--class-0  width-10', 'edit-details'); ?>
  </div>
</div>
<div class="main-card ">
  <?php foreach ($tests as $test) : ?>

    <div class="card">

      <div class="card-header-1 " style="padding-top: 8.5vh;padding-bottom:10vh">
        <h5><b>Name :</b><?= $test['name'] ?> </h5>
        <h5><b>Test Fee :</b><?= $test['test_fee'] ?> </h5>
        <h5><b>Hospital Fee :</b><?= $test['hospital_fee'] ?> </h5>
        <h5><b>Template ID :</b><?= $test['template_ID'] ?> </h5>


      </div>

      <div style="align-content:center">
        <?php echo $component->button('edit-details', '', 'Edit Details', 'button--class-1 ', $test['name']) ?>
      </div>
      <div style="align-content:center">
        <?php echo $component->button('edit-details', '', 'Delete', 'button--class-00 ', $test['name']) ?>
      </div>


    </div>

  <?php endforeach; ?>

</div>

<script>
  elementsArray = document.querySelectorAll(".button--class-0");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-add-new-test'; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-1");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-test-update?mod=update&id=' + elem.id; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-5");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-test-template'; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-00");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-test-delete?cmd=delete&id=' + elem.id; //pass the variable value
    });
  });
</script>