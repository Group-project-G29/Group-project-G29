

<?php
use app\core\component\Component;
use app\core\component\Sidebar;

$component = new Component();

?>


  

<!-- <div class='search-bar-container'>
    <?php //echo $component->searchbar($model,"name","search-bar--class1","Search by test name","searh");?>
</div> -->

<div class="table-container">
<table border="0">
    <thead>
      <tr>
        <th>Doctor</th>
        <th>Type</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($clinics as $key=>$clinic): ?>

      <tr class="table-row row-height hover" id="<?= $clinic['channeling_ID'] ?>">

        <td><?= $clinic['name'] ?></td>
        
        <td><?= $clinic['speciality'] ?></td>
        <td></td>
        <td><a href="./all-channeling-more?id=<?=$clinic['channeling_ID']?>">More</a></td><td></td>
      </tr>
      
      <?php endforeach; ?>



      <script>
        elementsArray = document.querySelectorAll(".table-row");
        elementsArray.forEach(function(elem) {
            elem.addEventListener("click", function() {
                location.href='all-channeling-more?id='+elem.id;
            });
        });
      </script>
</table>
</div>