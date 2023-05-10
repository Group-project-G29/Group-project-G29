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

  <b>
  </b>

</div>
  <?php foreach ($templates as $key => $template) : ?>
  <div class="flip-card" id=<?= $template['template_ID'] ?>>
      <div class="flip-card-inner">
        <div class="flip-card-front">


          <h3>Test Name:</h3>
          <p class="title"><?= $template['name'] ?> </p>



        </div>
        <div class="flip-card-back" >
          <h3>Title:</h3>
            <p class="title"><?= $template['title'] ?></p>
            <h3>Sub Title:</h3>
            <p class="title"><?= $template['subtitle'] ?> </p>
            
          </div>
      </div>
    </div>
  
<?php endforeach; ?>

<script>

  
  elementsArray = document.querySelectorAll(".card-2");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-view-all-template-more?id=' + elem.id; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".flip-card");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-view-all-template-more?id=' + elem.id; //pass the variable value
    });
  });
</script>