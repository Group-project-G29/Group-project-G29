<?php

use app\core\component\Component;
use \app\core\form\Form;

$component = new Component();

?>
<div class="semi-header-container">
<div class="field-container" style="margin-left: 5vw;">
        <h1 class="fs-200 fc-color--dark">Upload Report</h1>
        <h4><b>Request No :</b><?= $tests[0]['request_ID'] ?> </h4>
        <h4><b>Doctor = Dr.</b><?= $tests[0]['ename'] ?></h4>
        <h4><b>patient :</b> <?= $tests[0]['pname'] ?></h4>
        <h4><b>Requested date & Time :</b> <?= $tests[0]['requested_date_time'] ?></h4>
</div>
<hr>
<div class="field-container" style="margin-left: 5vw;">

        <?php $form = Form::begin('lab-S-report-upload?id='.$tests[0]['request_ID'], 'post'); ?>
        
        <div class="button" style="margin-left:20vw">

            <?php echo $component->button('submit', 'submit', 'Submit', 'button--class-0  width-10 curser', 'add'); ?>
        </div>
        <div class="inputbox">
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