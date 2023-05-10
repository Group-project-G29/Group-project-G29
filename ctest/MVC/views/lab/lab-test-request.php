<?php

use app\core\component\Component;
use app\models\LabReport;

$component = new Component();
$LabReport = new LabReport();
?>
<div class="search-bar-container" style="padding-left:19vw">
  <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by name,specilaity', 'searchbar'); ?>
</div>

<!-- <div class="main-card">
  <?php foreach ($tests as $test) : ?>

    <div class="card" id=<?= $test['patient_ID'] ?> >

      <div class="card-header-1 " style="padding-top: 5vh;padding-bottom:7.9vh">
        <h5><b>Doctor Name :</b><?= $test['doc_name'] ?> </h5>

        <h5><b>Patient :</b><?= $test['patient_name'] ?> </h5>
        <h5><b>Req date & Time :</b><?= $test['requested_date_time'] ?> </h5>

        <h5><b>Test :</b><?= $test['test_name'] ?> </h5>

      </div>
     
      <div >
      <?php echo $component->button('edit-details', '', 'Write Test Result', 'button--class-7  width-10', $test['request_ID']); ?>
      </div></a>
      <div >
      <?php echo $component->button('upload', '', 'Upload', 'button--class-8 ', $test['request_ID']); ?>
      </div>


    </div>

  <?php endforeach; ?>

</div> -->

<?php foreach ($tests as $test) : ?>
  <?php if(!$LabReport->isreport( $test['request_ID'])):?>
  <div class="card-new-0" id=<?= $test['patient_ID'] ?>>

    <div class="card-details">
    <p class="text-title">Dr.<?= $test['doc_name'] ?></p>

      <!-- <p class="text-body"><b>Doctor Name :</b><?= $test['doc_name'] ?> </p > -->

      <p class="text-body"><b>Patient :</b><?= $test['patient_name'] ?> </p>
      <p class="text-body"><b>Req date & Time :</b><?= $test['requested_date_time'] ?> </p>

      <p class="text-body"><b>Test :</b><?= $test['test_name'] ?> </p>
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