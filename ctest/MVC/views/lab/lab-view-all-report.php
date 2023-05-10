<?php

use app\core\component\Component;

$component = new Component();

?>

<div class='upper-container'>
  <div class="search-bar-container" style="padding-left:20vw">
    <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by name,specilaity', 'searchbar'); ?>
  </div>


</div>

<!-- <?php foreach ($reports as $key => $report) : ?>
  <div class="flip-card" id=<?= $report['report_ID'] ?>>
    <div class="flip-card-inner">
      <div class="flip-card-front">


        <h3>Report ID:</h3>
        <p class="title"><?= $report['report_ID'] ?> </p>



      </div>
      <div class="flip-card-back" id=<?= $report['report_ID'] ?>>
        <h3>Doctor Name:</h3>
        <p class="title"><?= $report['dname'] ?></p>
        <h3>Patient Name:</h3>
        <p class="title"><?= $report['pname'] ?> </p>
        
        <?php echo $component->button('edit-details', '', 'Edit Details', 'button--class-7 ', $report['report_ID']) ?>
        <?php echo $component->button('delete', '', 'Delete', 'button--class-9', $report['report_ID']) ?>
        <div class="reg-body_bottom-text">Edit Details<a href="lab-edit-report-detail?mod=update$id=$report['report_ID']"></a></div>


      </div>
    </div>
  </div>
<?php endforeach; ?> -->

<?php foreach ($reports as $report) : ?>
<div class="card-new" >

  <div class="card-details" id=<?= $report['report_ID'] ?>>
    <p class="text-title">Report ID :<?= $report['report_ID'] ?></p>
        <p class="text-body"><b>Doctor Name :</b><?=$report['dname'] ?> </p>
        <p class="text-body"><b>Patient Name :</b><?=$report['pname'] ?> </p>
  </div>
  <!-- <button class="card-button"> -->
  <?php echo $component->button('edit-details', '', 'Edit Details', 'button--class-7 ', $report['report_ID']) ?>
  <?php echo $component->button('edit-details', '', 'Delete', 'button--class-9', $report['report_ID']) ?>

  <!-- </button> -->
</div>
<?php endforeach; ?>


<script>
  elementsArray = document.querySelectorAll(".card-2");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-view-report-detail?id=' + elem.id; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".card-details");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-view-report-detail?id=' + elem.id; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-7");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-edit-report-detail?mod=update&id=' + elem.id; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-9");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-view-all-report?cmd=delete&id=' + elem.id; //pass the variable value
    });
  });
</script>