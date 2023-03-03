<?php

use app\core\component\Component;
use \app\core\form\Form;

$component = new Component();
?>
<div class="header-container">
    

    <div class="semi-header-container">
    <h2>Add Report</h2>
    <?php $age="18"?>
        <h5><?php if ($patient['age']< $age){
        echo "*Pediatric";}
        else{echo "*Adult";}?></h5>

        <h5>Name = <?= $patient['name'] ?></h5>
        <h5>Age = <?= $patient['age'] ?></h5>
        <h5>Gender = <?= $patient['gender'] ?></h5>


        <div class="button">
            <?php echo $component->button('edit-details', '', 'Edit', 'button--class-0  width-10', 'edit-details'); ?>
        </div>

       

    </div>
</div>







<script>
    elementsArray = document.querySelectorAll(".button-1");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-add-new-test'; //pass the variable value
        });
    });

    elementsArray = document.querySelectorAll(".button");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-test-update?mod=update&id=' + elem.id; //pass the variable value
        });
    });

    elementsArray = document.querySelectorAll(".button-0");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-test-delete?cmd=delete&id=' + elem.id; //pass the variable value
        });
    });
</script>