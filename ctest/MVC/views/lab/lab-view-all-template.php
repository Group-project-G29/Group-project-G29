<?php

use app\core\component\Component;

$component = new Component();
// var_dump($templates);
// exit;
?>

<div class='upper-container'>
  <div class="search-bar-container" style="padding-left:20vw">
    <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by template,title', 'searchbar'); ?>
  </div>

  <b>
  </b>

</div>
<div class="large-div" >

<?php foreach ($templates as $key => $template) : ?>

  <div class="templates" id=<?= "'" . $template['template_ID'] . "-" . $template['name'] . "-".$template['title']."'" ?>>
    <div class="">
      <div class="flip-card">
        <div class="flip-card-inner">
          <div class="flip-card-front">


            <h3>Test Name:</h3>
            <p class="title"><?= $template['name'] ?> </p>



            <div class="flip-card-back">
              <h3>Title:</h3>
              <p class="title"><?= $template['title'] ?></p>
              <h3>Sub Title:</h3>
              <p class="title"><?= $template['subtitle'] ?> </p>
  
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php endforeach; ?>
</div>

<script>
  const templates = document.querySelectorAll('.templates');
  const searchBar = document.getElementById('searchbar');

  function checker() {

    var re = new RegExp(("^" + searchBar.value).toLowerCase())
    templates.forEach((el) => {
      comp = "" + el.id;
      comp = comp.split("-");

      if (re.test(comp[1].toLowerCase()) || re.test(comp[2].toLowerCase())) {
        el.classList.remove("none");
      } else {
        el.classList.add("none");

      }
    })
    if (searchBar.value.length == 0) {
      templates.forEach((el) => {
        el.classList.remove("none");
      })
    }
  }
  searchBar.addEventListener('input', checker);


  elementsArray = document.querySelectorAll(".templates");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      comp = "" + elem.id;
      comp = comp.split("-");
      location.href = 'lab-view-all-template-more?id=' + comp[0]; //pass the variable value
    });
  });
</script>