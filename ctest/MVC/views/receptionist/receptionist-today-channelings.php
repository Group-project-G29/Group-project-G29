<?php
  use app\core\component\Component;
use app\models\Channeling;

  $component = new Component();
  $testModel=new Channeling();
?>

<div class='upper-container'>
  <div class="search-bar-container">
    <?php echo $component->searchbar('','search','search-bar--class2','Search by name,specilaity','searchbar');?>
  </div>


</div>
<div class="table-container">
  <table border="0">

    <?php foreach ($channelings as $key => $channeling) : ?>
    <?php if($channeling['channeling_date']==date('Y-m-d') && $channeling['status']=='Opened'): ?>
          <div class="today-channeling-tile" id=<?="'".$channeling['name']."'" ?>>
                <div class=<?="'"."grid".rand(1,4)."'"?>>
                    <div class="today-tile-time">
                        <h1><?="Dr.".$channeling['name']?></h1>
                    </div>
                </div> 
                    <div>
                    <h4><?="Time :".$channeling['time']." ".(($channeling['time']>='12.00')?'PM':'AM')?></h4>
                    <h4>Speciality :<?=$channeling['speciality']?></h4>
                    <h4>Room :<?=$channeling['room']?></h4>
                    <h4>Fee :LKR <?=$channeling['fee']?></h4>
                </div>
            </div>

      <?php endif; ?>
    <?php endforeach; ?>




  </table>
</div>
<script>
  elementsArray = document.querySelectorAll(".table-row");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'receptionist-channeling-more?id='+elem.id;  //pass the variable value
    });
  });

  

</script>