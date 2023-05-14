<?php

use app\core\component\Component;
use \app\core\form\Form;

$component = new Component();

?>
<div class="semi-header-container" style="left: 20vw;padding-top:0vw;">
    <img src="./media/images/logo-1.png" style="width:15vw;margin-left:13vw">
    <div class="field-container" style="margin-left: 5vw; line-height:1.5vw">
        <div class="field-container-left" style="width:50%">
            <?php $age = "18" ?>
            <h5><?php if ($contents[0]['age'] < $age) {
                    echo "*Pediatric";
                } else {
                    echo "*Adult";
                } ?></h5>
            <h4><b>Request No : </b><?= $contents[0]['request_ID'] ?></h4>
            <h4><b>Doctor :</b>Dr.<?= $contents[0]['ename'] ?></h4>
            <h4><b>Patient Name : </b><?= $contents[0]['pname'] ?></h4>
            <h4><b>Age : </b><?= $contents[0]['age'] ?></h4>
            <h4><b>Gender : </b><?= $contents[0]['gender'] ?></h4>
        </div>
        <div class="field-container-right" style="width:50%">
            <h4><b>Test Name : </b><?= $contents[0]['tname'] ?></h4>
            <h4 style="color:red"><b>*Note : </b><?= $contents[0]['note'] ?></h4>
        </div>
    </div>
    <hr>
    <div class="field-container" style="margin-left: 5vw;">
        <?php $form = Form::begin('', 'post'); ?>

        <?php if ($contents[0]['content_ID'] != NULL) : ?>
            <div class="button" style="margin-left:20vw;margin-bottom:2vw">
                <?php echo $component->button('Add', '', 'Add', 'button--class-0  width-10 ', 'add'); ?>
            </div>

            <?php foreach ($contents as $key => $content) : ?>

                    <div class="report-container">
                        <label class="label1" for="cname"><?php echo $content["cname"] ?></label>
                        <label class="label2" for="cname"><?php echo $content["metric"] ?></label>
                        <input class="" name=<?= $content["content_ID"] ?> type="text">

                        <br>
                    </div>
            <?php endforeach; ?>
         <?php else : ?>
                    <p>*No Contents Yet!!</p>
                <?php endif; ?>
                
                
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