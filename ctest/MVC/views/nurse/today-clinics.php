

<?php
use app\core\component\Component;
use app\core\component\Sidebar;

$component = new Component();

?>

<div class="table-container">

<table border="0">
    <thead>
      <tr>
        <th>Channeling</th>
        <th>Date</th><th></th>
        <th>Time</th>
        <th> </th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($clinics as $key=>$clinic): ?>

      <?php 
      $count=0;
      if($clinic['day']!=getdate()['weekday']){
        continue;
      }
      $count=$count+1;
      ?>
      <tr class="table-row row-height hover" id="<?= $clinic['channeling_ID'] ?>">

        <td><?=$clinic['speciality']?><br>Dr. <?=$clinic['name'] ?></td>
        <!-- <td></td><td></td> -->
        <td><?= $clinic['day'] ?></td>
        <td></td>
        <td><?= $clinic['time'] ?></td>
        <td></td><td></td>
        
      </tr>
      
      <?php endforeach; ?>

      

      
      <script>
        elementsArray = document.querySelectorAll(".table-row");
        elementsArray.forEach(function(elem) {
            elem.addEventListener("click", function() {
                location.href='Channeling?channeling='+elem.id;
            });
        });
      </script>
</table>
      
</div>

<center><br><br><div class="no-clinic-desc"><?php
  if($count==0){echo("No Channeling Sessions Today");}
?></div></center>