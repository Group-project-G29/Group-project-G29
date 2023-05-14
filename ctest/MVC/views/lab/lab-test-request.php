<?php

use app\core\component\Component;
use app\models\LabReport;

$component = new Component();
$LabReport = new LabReport();
?>
<div class="search-bar-container" style="padding-left:19vw">
  <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by patient ,doctor', 'searchbar'); ?>
</div>
<div class="large-div">

  <?php foreach ($tests as $test) : ?>
    <?php if (!$LabReport->isreport($test['request_ID'])) : ?>
      <div class="reqs" id=<?= "'" . $test['patient_ID'] . "-".$test['doc_name']."-".$test['patient_name']."'" ?>>

        <div class="card-new-0">

          <div class="card-details">
            <p class="text-title"><?= $test['test_name'] ?></p>


            <p class="text-body"><b>Patient :</b><?= $test['patient_name'] ?> </p>
            <p class="text-body"><b>Req date & Time :</b><?= $test['requested_date_time'] ?> </p>

            <p class="text-body"><b>Doctor :</b>Dr.<?= $test['doc_name'] ?> </p>
          </div>
          <!-- <button class="card-button"> -->
          <?php echo $component->button('edit-details', '', 'Write Test Result', 'button--class-10 ', $test['request_ID']) ?>
          <?php echo $component->button('edit-details', '', 'Upload', 'button--class-8', $test['request_ID']) ?>

          <!-- </button> -->
        </div>
      </div>
    <?php endif ?>
  <?php endforeach; ?>
</div>

<script>
  const requests = document.querySelectorAll('.reqs');
  const searchBar = document.getElementById('searchbar');

  function checker() {

    var re = new RegExp(("^" + searchBar.value).toLowerCase())
    requests.forEach((el) => {
      comp = "" + el.id;
      comp = comp.split("-");

      if (re.test(comp[1].toLowerCase()) || re.test(comp[2].toLowerCase())) {
        el.classList.remove("none");
      } else {
        el.classList.add("none");

      }
    })
    if (searchBar.value.length == 0) {
      requests.forEach((el) => {
        el.classList.remove("none");
      })
    }
  }
  searchBar.addEventListener('input', checker);


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
      comp = "" + elem.id;
      comp = comp.split("-");
      location.href = 'lab-write-test-result?id=' + comp[0]; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-8");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      comp = "" + elem.id;
      comp = comp.split("-");
      location.href = 'lab-report-upload?id=' + comp[0]; //pass the variable value
    });
  });
</script>