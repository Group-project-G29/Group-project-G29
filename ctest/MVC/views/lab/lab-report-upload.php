<?php

use app\core\component\Component;
use \app\core\form\Form;

$component = new Component();

?>
<div class="header-container">
    <div class="reg-body-spec_title">
        <h1 class="fs-200 fc-color--dark" style="padding-bottom: 2vh;">Upload Report</h1>
    </div>
    <div class="semi-header-container">



        <h5>Request No =<?= $tests[0]['request_ID'] ?> </h5>
        <h5>Doctor = Dr.<?= $tests[0]['ename'] ?></h5>
        <h5>patient = <?= $tests[0]['pname'] ?></h5>
        <h5>Requested date & Time = <?= $tests[0]['requested_date_time'] ?></h5>


    </div>
    <? $form = Form::begin('', 'post'); ?>
    <section class="reg_body-spec" style="padding-bottom:50px">
        

            <label for="image"> </label><br>
            <input type='file'><br>
        

            <div class="button">
                <?php echo $component->button('submit', 'submit', 'Submit', 'button--class-0  width-10 curser', 'add'); ?>
            </div>
            
            <?php Form::end() ?>

</div>


</section>

<script>
    elementsArray = document.querySelectorAll(".button--class-0");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-test-request'; //pass the variable value
        });
    });
</script>
    


