<?php

use app\core\component\Component;

$component = new Component();
// var_dump($templates);
// exit;
?>

<div class='upper-container'>
  <div class="search-bar-container" style="padding-left:20vw">
    <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by name,specilaity', 'searchbar'); ?>
  </div>

  <b><h1 class="fs-200 fc-color--dark" style="padding-left: 60vh;padding-top: 10vh;">Templates</h1></b>

</div>
<div class="main-card-1 ">
  <?php foreach ($templates as $key => $template) : ?>

    <div class="card-2" id=<?= $template['template_ID'] ?>>
         
          <div class="card-header-2" style="padding-top: 8vh;">

            <h1><?= $template['name'] ?> </h1>
          </div>

       

      </div>
    
  <?php endforeach; ?>

</div>

<script>
  elementsArray = document.querySelectorAll(".card-2");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-view-all-template-more?id=' + elem.id; //pass the variable value
    });
  });
</script>