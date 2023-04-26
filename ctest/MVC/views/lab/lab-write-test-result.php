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
        <div class="header-container" style=" padding-top:0vh;">

            <h1>Add Report</h1>
            <?php $age = "18" ?>
            <h2><?php if ($contents[0]['age'] < $age) {
                    echo "*Pediatric";
                } else {
                    echo "*Adult";
                } ?></h2>
            <h4>Request ID = <?= $contents[0]['request_ID'] ?></h4>
            <h4>Name = <?= $contents[0]['pname'] ?></h4>
            <h4>Age = <?= $contents[0]['age'] ?></h4>
            <h4>Gender = <?= $contents[0]['gender'] ?></h4>






            
            
        </div>
        <?php $form = Form::begin('', 'post'); ?>
        <div class="reg-body-spec fields" style="padding-left:15vw">

            <?php foreach ($contents as $key => $content) : ?>
                <?php if ($content["type"] === 'image') : ?>
                    <div class="inputbox">
                    <label for="image"><?php echo $content["cname"] ?></label><br>
                    <input type='file' id=<?= $content["content_ID"] ?> name=<?= $content["content_ID"] ?>><br>
                    </div>
                <?php else : ?>
                    <div class="inputbox">
                    <label for="cname"><?php echo $content["cname"] ?></label><br>
                    <input type='text' id=<?= $content["content_ID"] ?> name=<?= $content["content_ID"] ?>><br>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div><br>
        <div class="button" style="padding-left:40vw">
            <?php echo $component->button('Add', '', 'Add', 'button--class-0  width-10 ', 'add'); ?>
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