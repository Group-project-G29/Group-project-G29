

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

      <tr class="table-row">

        <td><?= $clinic['name'] ?></td>
        
        <td><?= $clinic['speciality'] ?></td>
        <td></td>
        <td><a href="./all-clinic-more?id=<?=$clinic['channeling_ID']?>">More</a></td><td></td>
      </tr>
      
      <?php endforeach; ?>

</table>
</div>