<?php
  use app\core\component\Component;
use app\models\Channeling;

  $component = new Component();
  $testModel=new Channeling();

?>

<div class='upper-container'>
  <div class="search-bar-container">
    
  </div>


</div>
<div class="today-ch-container">


    <?php foreach ($channelings as $key => $channeling) : ?>
    <?php if($channeling['channeling_date']==date('Y-m-d') ): ?>
      <?php if($channeling['status']=='finished' || $channeling['status']=='cancelled' ): ?>
          <div class="today-channeling-tile background-done" id=<?="'".$channeling['name']."'" ?>>
      <?php else:?>
          <div class="today-channeling-tile" id=<?="'".$channeling['name']."'" ?>>
      <?php endif;?>
                <div class=<?="'"."grid".rand(1,4)."'"?>>
                    <div class="today-tile-time">
                        <h1><?="Dr.".$channeling['name']?></h1>
                    </div>
                </div> 
                    <div>
                    <h3><?="Time :".$channeling['time']." ".(($channeling['time']>='12.00')?'PM':'AM')?></h3>
                    <h4>Speciality :<?=$channeling['speciality']?></h4>
                    <h4>Room :<?=$channeling['room']?></h4>
                    <h4>Fee :LKR <?=$channeling['fee'].".00"?></h4>
                    <h4>Channeling Status :<?=ucfirst($channeling['status'])?></h4>
                </div>
            </div>

      <?php endif; ?>
    <?php endforeach; ?>




</div>
<script>
  elementsArray = document.querySelectorAll(".table-row");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'receptionist-channeling-todas-more?id='+elem.id;  //pass the variable value
    });
  });

  

</script>