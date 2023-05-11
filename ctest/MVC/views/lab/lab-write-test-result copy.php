<?php

use app\core\component\Component;
use \app\core\form\Form;

$component = new Component();
// var_dump($contentmodel);
// var_dump($allocationmodel);
// var_dump($contents);
// exit;
?>
<div class="semi-header-container" style="left: 20vw;">
    <div class="field-container" style="margin-left: 5vw;">
        <h1 class="fs-200 fc-color--dark">Add Report</h1>
        <?php $age = "18" ?>
        <h5><?php if ($contents[0]['age'] < $age) {
                echo "*Pediatric";
            } else {
                echo "*Adult";
            } ?></h5>
        <h4><b>Request No : </b><?= $contents[0]['request_ID'] ?></h4>
        <h4><b>Name : </b><?= $contents[0]['pname'] ?></h4>
        <h4><b>Age : </b><?= $contents[0]['age'] ?></h4>
        <h4><b>Gender : </b><?= $contents[0]['gender'] ?></h4>
    </div>
    <hr>
    <div class="field-container" style="margin-left: 5vw;">
        <?php $form = Form::begin('', 'post'); ?>

        <div class="button" style="margin-left:20vw;margin-bottom:2vw">
            <?php echo $component->button('Add', '', 'Add', 'button--class-0  width-10 ', 'add'); ?>
        </div>
        <?php foreach ($contents as $key => $content) : ?>
           
            <div class="inputbox">
                <table border="0">
               <tr> <label for="cname"><?php echo $content["cname"] ?></label></tr>
               <tr><input type='text' id=<?= $content["content_ID"] ?> name=<?= $content["content_ID"] ?>></tr>
               <tr> <label for="cname"><?php echo $content["metric"] ?></label><br><br></tr>
                </table>
            </div>
        <?php endforeach; ?>


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