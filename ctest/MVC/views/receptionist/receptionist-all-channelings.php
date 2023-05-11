<?php

use app\core\component\Component;

$component = new Component();
// var_dump($channelings);
// exit;
?>

<div class='upper-container'>
  
</div>


<?php foreach ($channelingmore as $key => $channeling) : ?>
<div class="card-new " id=<?= $channeling['career_speciality'] ?>>

  <div class="card-details">
    <p class="text-body"> <img src="./media/images/logo-1.png" >  </p>
    <p class="text-title" style="color:#2c4666"><?= $channeling['career_speciality'] ?></p>
   
  </div>
  
</div>
<?php endforeach; ?>


<script>
   

  elementsArray = document.querySelectorAll(".card-new");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'receptionist-all-channeling-type?id=' + elem.id; //pass the variable value
    });
  });
</script>