

<?php
use app\core\component\Component;
use app\core\component\Sidebar;

$component = new Component();
// var_dump($openedChanneling);
?>
<?php if($openedChanneling){?>
<div class="table-container">

<table border="0">
    <thead>
      <tr>
        <th>Channeling</th>
        <th>Room</th>
        <th>Time</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    <?php 
      foreach($openedChanneling as $key=>$channeling): ?>

      
      <tr class="table-row row-height hover" id="<?= $channeling['opened_channeling_ID'] ?>">

        <td><?=$channeling['speciality']." - "?>  Dr. <?=$channeling['name'] ?></td>
        <!-- <td></td><td></td> -->
        <td><?= $channeling['room'] ?></td>
        <td><?= $channeling['time'] ?></td>
        <td><?= $channeling['status'] ?></td>
        <td></td>
        
      </tr>
      
      <?php endforeach; ?>

      

      
      <script>
        elementsArray = document.querySelectorAll(".table-row");
        elementsArray.forEach(function(elem) {
            elem.addEventListener("click", function() {
                location.href='all-channeling-session?channeling='+elem.id;
            });
        });
      </script>
</table>
      
</div>

<?php } else{
    echo("<center><br><br><div class='no-clinic-desc'>No Channeling Sessions Today</div></center>");
} ?>