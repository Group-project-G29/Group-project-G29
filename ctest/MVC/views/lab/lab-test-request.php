<?php

use app\core\component\Component;
use app\models\LabReport;

$component = new Component();
$LabReport = new LabReport();
?>
<div class="search-bar-container" style="padding-left:19vw">
  <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by name,specilaity', 'searchbar'); ?>
</div>

<?php foreach ($tests as $test) : ?>
  <?php if(!$LabReport->isreport( $test['request_ID'])):?>
  <div class="card-new-0" id=<?= $test['patient_ID'] ?>>

    <div class="card-details">
    <p class="text-title"><?= $test['test_name'] ?></p>

      <!-- <p class="text-body"><b>Doctor Name :</b><?= $test['doc_name'] ?> </p > -->

      <p class="text-body"><b>Patient :</b><?= $test['patient_name'] ?> </p>
      <p class="text-body"><b>Req date & Time :</b><?= $test['requested_date_time'] ?> </p>

      <p class="text-body"><b>Doctor :</b>Dr.<?= $test['doc_name'] ?> </p>
    </div>
    <!-- <button class="card-button"> -->
    <?php echo $component->button('edit-details', '', 'Write Test Result', 'button--class-10 ', $test['request_ID']) ?>
    <?php echo $component->button('edit-details', '', 'Upload', 'button--class-8', $test['request_ID']) ?>

    <!-- </button> -->
  </div>
  <?php endif?>
<?php endforeach; ?>

<script>
  elementsArray = document.querySelectorAll(".button-1");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-add-new-test'; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-10");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-write-test-result?id=' + elem.id; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-8");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-report-upload?id=' + elem.id; //pass the variable value
    });
  });
</script>