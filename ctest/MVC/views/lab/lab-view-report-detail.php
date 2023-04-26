<?php

use app\core\component\Component;

$component = new Component();
// var_dump($user);
?>
<!-- 

<div class="sub-header-container" style="padding:2vh 0 2vh 0">

  <div class="button-1" style="margin-left: 35%;">
  <?php echo $component->button('edit-details', '', 'Add New Test', 'button--class-0  width-10', 'edit-details'); ?>
  </div>
</div> -->
<div class="main-card ">
  <?php foreach ($reports as $report) : ?>

    <!-- <div class="card"> -->

      <div class="card-header-1 " style="padding-top: 8.5vh;padding-bottom:10vh">
        <!-- <h5><b>Report ID :</b><?= $report['report_ID'] ?> </h5> -->
        
        <?php if($report['type']==='field'):?>
          <h5><b> <?= $report['name']  ?>:</b><?= $report['int_value']?> <b>Range</b> <?= $report['reference_ranges'] ?></h5> 
        <?php endif?>
        <?php if($report['type']==='text'):?>
          <h5><?= $report['name'] ?><?=$report['text_value']?> </h5>
          <?php endif?>

        <?php if($report['type']==='image'):?>
          <h5><?= $report['name'] =$report['location']?> </h5>
          <?php endif?>
          <!-- <h5><b>Reference Range:</b><?= $report['reference_ranges'] ?> </h5> -->

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
</script> -->