<?php

use app\core\component\Component;

$component = new Component();

    // var_dump($reports);
    // exit;
?>

<div class='upper-container'>
  <div class="search-bar-container" style="padding-left:20vw">
    <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by patient name,report ID', 'searchbar'); ?>
  </div>


</div>
<div class="large-div">
  <?php foreach ($reports as $report) : ?>
  <div class="reps"  id=<?= "'".$report['report_ID']."-".$report['pname']."'" ?>>
  <div class="card-new" style="width:13vw" >

    <div class="card-details" id=<?= "'".$report['report_ID']."-".$report['pname']."'" ?>>
      <p class="text-title">Report ID :<?= $report['report_ID'] ?></p>
          <p class="text-body" style="color:#1746A2"><b>*<?=$report['tname'] ?></b> </p>
          <p class="text-body"><b>Doctor :</b><?=$report['dname'] ?> </p>
          <p class="text-body"><b>Req Date :</b><?=$report['date'] ?> </p>
          <p class="text-body"><b>Patient :</b><?=$report['pname'] ?> </p>
    </div>
      <?php echo $component->button('edit-details', '', 'Edit Details', 'button--class-7 ', $report['report_ID']) ?>
      <?php echo $component->button('edit-details', '', 'Delete', 'button--class-9', $report['report_ID']) ?>
      
    </div>
  </div>
  <?php endforeach; ?>
</div>
 
<script>
  const reports=document.querySelectorAll('.reps');
        const searchBar=document.getElementById('searchbar');

        function checker(){
        
            var re=new RegExp(("^"+searchBar.value).toLowerCase())
        reports.forEach((el)=>{
            comp=""+el.id;
            comp=comp.split("-");
        
            if(re.test(comp[0].toLowerCase()) || re.test(comp[1].toLowerCase())){
                el.classList.remove("none");
            }
            else{
                el.classList.add("none");
               
            }
            })
            if(searchBar.value.length==0){
                reports.forEach((el)=>{
                    el.classList.remove("none");
                }) 
            }
        }
        searchBar.addEventListener('input',checker);
  elementsArray = document.querySelectorAll(".card-2");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      comp=""+elem.id;
      comp=comp.split("-");
      location.href = 'lab-view-report-detail?id=' + comp[0]; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".card-details");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      comp=""+elem.id;
        comp=comp.split("-");
      location.href = 'lab-view-report-detail?id=' + comp[0]; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-7");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      comp=""+elem.id;
        comp=comp.split("-");
      location.href = 'lab-edit-report-detail?mod=update&id=' + comp[0]; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-9");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      comp=""+elem.id;
        comp=comp.split("-");
      location.href = 'lab-edit-report-detail?cmd=delete&id=' + comp[0] //pass the variable value
    });
  });
</script>