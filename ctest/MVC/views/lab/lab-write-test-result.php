<?php

use app\core\component\Component;
use \app\core\form\Form;

$component = new Component();
// var_dump($contentmodel);
// var_dump($allocationmodel);
// var_dump($contents);
// exit;
?>
<div class="semi-header-container">
    <div class="field-container">
        <div class="header-container">

            <h2>Add Report</h2>
            <?php $age = "18" ?>
            <h5><?php if ($contents[0]['age'] < $age) {
                    echo "*Pediatric";
                } else {
                    echo "*Adult";
                } ?></h5>

            <h5>Name = <?= $contents[0]['pname'] ?></h5>
            <h5>Age = <?= $contents[0]['age'] ?></h5>
            <h5>Gender = <?= $contents[0]['gender'] ?></h5>






            <?php $form = Form::begin('', 'post'); ?>


        </div>
        <div class="reg-body-spec fields" style="padding-left:15vw">

            <?php foreach ($contents as $key => $content) : ?>
                <?php if ($content["type"] === 'image') : ?>
                    <label for="image"><?php echo $content["cname"] ?></label><br>
                    <input type='file' id=<?= $content["content_ID"] ?> name=<?= $content["content_ID"] ?>><br>

                <?php else : ?>
                    <label for="cname"><?php echo $content["cname"] ?></label><br>
                    <input type='text' id=<?= $content["content_ID"] ?> name=<?= $content["content_ID"] ?>><br>

                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="button">
            <?php echo $component->button('Add', '', 'Add', 'button--class-0  width-10', 'add'); ?>
        </div>

        <?php Form::end() ?>



    </div>
</div>








<script>
    elementsArray = document.querySelectorAll(".button--class-0");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-view-all-report'; //pass the variable value
        });
    });
</script>