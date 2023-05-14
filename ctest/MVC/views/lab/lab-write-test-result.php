<?php

use app\core\component\Component;
use \app\core\form\Form;

$component = new Component();
// var_dump($contentmodel);
// var_dump($allocationmodel);
// var_dump($contents);
// exit;
?>
<div class="base-container">
    <!-- <img src="./media/images/logo-1.png" style="width:15vw;margin-left:13vw"> -->
    <div class="field-container-1">
        <div class="field--container-left">
            <?php $age = "18" ?>
            <h3><?php if ($contents[0]['age'] < $age) {
                    echo "*Pediatric";
                } else {
                    echo "*Adult";
                } ?>
            </h3>
            <div class="test-name"><?= $contents[0]['tname'] ?></div>
            

            <?php if($contents[0]['note']){ ?>
            <div class="field-content-data">
                <div>Note : </div><?= $contents[0]['note'] ?>
            </div>
            <?php } ?>
        </div>
        <div class="field--container-right" >
            
            <div class="field-content-data">
                <div>Request No : </div><?= $contents[0]['request_ID'] ?>
            </div>
            <div class="field-content-data">
                <div>Doctor :</div>Dr.<?= $contents[0]['ename'] ?>
            </div>
            <div class="field-content-data">
                <div>Patient Name : </div><?= $contents[0]['pname'] ?>
            </div>
            <div class="field-content-data">
                <div>Age : </div><?= $contents[0]['age'] ?>
            </div>
            <div class="field-content-data">
                <div>Gender : </div><?= $contents[0]['gender'] ?>
            </div>
        </div>
    </div>

    <hr>


    <div class="field-container-2">
        <?php $form = Form::begin('', 'post'); ?>

        <?php if ($contents[0]['content_ID'] != NULL) : ?>

        <div class="button" style="margin-left:40vw;margin-bottom:2vw">
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