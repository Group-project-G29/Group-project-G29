<?php

use app\core\component\Component;
use \app\core\form\Form;

$component = new Component();

?>
<div class="semi-header-container" style="left: 20vw;padding-top:0vw;">
    <img src="./media/images/logo-1.png" style="width:15vw;margin-left:13vw">

    <div class="field-container" style="margin-left: 5vw; line-height:1.5vw">
        <div class="field-container-left" style="width:50%">

            <h4><b>Request No :</b><?= $tests[0]['request_ID'] ?> </h4>
            <h4><b>Doctor :</b>Dr.<?= $tests[0]['ename'] ?></h4>
            <h4><b>patient Name :</b> <?= $tests[0]['pname'] ?></h4>
            <h4><b>Age : </b><?= $tests[0]['age'] ?></h4>
            <h4><b>Gender : </b><?= $tests[0]['gender'] ?></h4>
        </div>
        <div class="field-container-right" style="width:50%">
            <h4 style="color:red"><b>*Note : </b><?= $tests[0]['note'] ?></h4>
        </div>
    </div>
    <hr>
    <div class="field-container" style="margin-left: 5vw;">

        <?php $form = Form::begin('lab-S-report-upload?id=' . $tests[0]['request_ID'], 'post'); ?>

        <div class="button" style="margin-left:20vw">

            <?php echo $component->button('submit', 'submit', 'Upload', 'button--class-0  width-10 curser', 'add'); ?>
        </div>
        <div class="report-container" style="width:100%">
            <label for="image"> </label><br>
            <input type='file' name="location"><br>
        </div>


        <?php Form::end() ?>

    </div>

</div>


<script>
    elementsArray = document.querySelectorAll(".button--class-0");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-test-request'; //pass the variable value
        });
    });
</script>