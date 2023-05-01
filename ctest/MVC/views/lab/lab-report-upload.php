<?php

use app\core\component\Component;
use \app\core\form\Form;

$component = new Component();

?>
<div class="semi-header-container">
    <div class="field-container">
        <div class="header-container" style=" padding-top:0vh;">

            <h1 class="fs-200 fc-color--dark">Upload Report</h1>



            <h5>Request No =<?= $tests[0]['request_ID'] ?> </h5>
            <h5>Doctor = Dr.<?= $tests[0]['ename'] ?></h5>
            <h5>patient = <?= $tests[0]['pname'] ?></h5>
            <h5>Requested date & Time = <?= $tests[0]['requested_date_time'] ?></h5>


        </div>
        <?php $form = Form::begin('lab-S-report-upload?id='.$tests[0]['request_ID'], 'post'); ?>
        <div class="reg-body-spec fields" style="padding-left:8vw">
        <div class="inputbox">
            <label for="image"> </label><br>
           
            <input type='file' name="location"><br>
            </div>
        </div>
            <div class="button" style="padding-left:25vw;margin-top:5vh">

                <?php echo $component->button('submit', 'submit', 'Submit', 'button--class-0  width-10 curser', 'add'); ?>
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