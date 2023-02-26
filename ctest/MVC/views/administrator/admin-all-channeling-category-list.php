

<?php
use app\core\component\Component;
use app\core\component\Sidebar;

$component = new Component();
// var_dump($channelings);exit;
// $count=0;
// foreach($channelings as $key=>$clinic):
//   if($day == $clinic['day']){
//     $count +=1; 
//   }
// endforeach;
?>


  

<!-- <div class='search-bar-container'>
    <?php //echo $component->searchbar($model,"name","search-bar--class1","Search by test name","searh");?>
</div> -->
<?php // if($count>0){ ?>
<div class="table-container">
<table border="0">
    
    <thead>
      <tr>
        <th>Doctor</th>
        <th>Day</th>
        <th>Time</th>
        <th>Room</th>
      </tr>
    </thead>
    <tbody>
      <?php $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
      foreach($days as $day):?>
      
        <?php foreach($channelings as $key=>$clinic): //var_dump($clinic);exit;?>

          <?php if($day == $clinic['day']){ ?>
            <tr class="table-row" id="<?= $clinic['channeling_ID'] ?>">

              <td><?= $clinic['name'] ?></td>
              <td><?= $clinic['day'] ?></td>
              <td><?= $clinic['time'] ?></td>
              <td><?= $clinic['room'] ?></td>
            </tr>
          <?php } ?>
        <?php endforeach; ?>
      <?php endforeach; ?>
      


      
      
</table>
</div>

<?php // }else{
  //echo("<center><br><br><div class='no-clinic-desc'>No Channelings</div></center>");
//} ?>