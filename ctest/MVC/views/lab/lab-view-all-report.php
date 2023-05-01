<?php

use app\core\component\Component;

$component = new Component();
// var_dump($reports);
// exit;
?>

<div class='upper-container'>
  <div class="search-bar-container" style="padding-left:20vw">
    <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by name,specilaity', 'searchbar'); ?>
  </div>

  <b><h1 class="fs-200 fc-color--dark" style="padding-left: 65vh;padding-top: 10vh;">Lab Reports</h1></b>

</div>
<!-- <div class="main-card-1 ">
  <?php foreach ($reports as $key => $report) : ?>

    <div class="card-2" id=<?= $report['report_ID'] ?>>
         
          <div class="card-header-2" style="padding-top: 2vh;">

          <h3>Report ID:</h3>
          <h1><?= $report['report_ID'] ?> </h1>
          <h3>Patient Name:</h3>
          <h2><?= $report['pname'] ?> </h2>
          </div>

       

      </div>
    
  <?php endforeach; ?>

</div> -->
<?php foreach ($reports as $key => $report) : ?>
<div class="flip-card" id=<?= $report['report_ID'] ?>>
    <div class="flip-card-inner">
        <div class="flip-card-front" >

            
            <h3>Report ID:</h3>
          <p class="title"><?= $report['report_ID'] ?> </p>
         
         

          </div>
          <div class="flip-card-back" >
          <h3>Doctor Name:</h3>
            <p class="title"><?= $report['dname'] ?></p>
            <h3>Patient Name:</h3>
            <p class="title"><?= $report['pname'] ?> </p>
            
          </div>
        </div>
    </div>
    <?php endforeach; ?>
 

<script>
  elementsArray = document.querySelectorAll(".card-2");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-view-report-detail?id=' + elem.id; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".flip-card");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-view-report-detail?id=' + elem.id; //pass the variable value
    });
  });
</script>