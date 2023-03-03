<?php

use app\core\component\Component;

$component = new Component();
// var_dump($tests);
// exit;
?>
<div class="search-bar-container" style="padding-left:19vw">
  <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by name,specilaity', 'searchbar'); ?>
</div>

<div class="main-card ">
  <?php foreach ($tests as $test) : ?>

    <div class="card" id=<?=$test['patient_ID']?> >

      <div class="card-header-1 " style="padding-top: 5vh;padding-bottom:7.9vh">
        <h5><b>Name :</b><?= $test['doc_name'] ?> </h5>

        <h5><b>Patient :</b><?= $test['patient_name'] ?> </h5>
        <h5><b>Req date & Time :</b><?= $test['requested_date_time'] ?> </h5>

        <h5><b>Test :</b><?= $test['test_name'] ?> </h5>

      </div>
      <!-- <a href="lab-write-test-result.php"> -->
      <div >
      <?php echo $component->button('edit-details', '', 'Write Test Result', 'button--class-0  width-10', $test['patient_ID']); ?>
      </div></a>
      <div >
      <?php echo $component->button('edit-details', '','Upload', 'button--class-00  width-10', 'edit-details'); ?>
      </div>


    </div>

  <?php endforeach; ?>

</div>

<!-- <?php foreach ($tests as $test) : ?>

  <div class="detail-container">

    <table class="table-session">

      <tr class="table-row-session">
        <td>Doctor</td>
        <td>:</td>
        <td class="table-row-data">Dr.<?= $test['doc_name'] ?></td>
      </tr>
      <tr class="table-row-session">
        <td>patient</td>
        <td>:</td>
        <td class="table-row-data"><?= $test['patient_name'] ?></td>
      </tr>
      <tr class="table-row-session">
        <td>Requested date</td>
        <td>:</td>
        <td class="table-row-data"><?= $test['requested_date_time'] ?></td>
      </tr>
      <tr class="table-row-session">
        <td>Test</td>
        <td>:</td>
        <td class="table-row-data"><?= $test['test_name'] ?></td>
      </tr>
    </table>

    <div class="button" style="margin-left: 35%;">
      <?php echo $component->button('edit-details', '', 'Write Test Result', 'button--class-0  width-10', 'edit-details'); ?>
    </div>
    <div class="button-0" style="margin-left: 35%;">
      <?php echo $component->button('edit-details', '','Upload', 'button--class-0  width-10', 'edit-details'); ?>
    </div>

  </div>
<?php endforeach; ?> -->






<script>
  elementsArray = document.querySelectorAll(".button-1");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-add-new-test'; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-0");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-write-test-result?id='+elem.id; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-00");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-report-upload'; //pass the variable value
    });
  });
</script>