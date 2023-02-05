<?php

use app\core\component\Component;

$component = new Component();
// var_dump($user);
?>
<div class="search-bar-container">
  <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by name,specilaity', 'searchbar'); ?>
</div>

<?php foreach ($tests as $test) : ?>

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
      location.href = 'lab-report-upload'; //pass the variable value
    });
  });
</script>