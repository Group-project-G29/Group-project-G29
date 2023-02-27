<?php

use app\core\component\Component;

$component = new Component();
$component = new Component();
use app\core\DbModel;
use \app\core\form\Form;?>
<!-- <div class="header-container"> -->
<div class="reg-body-spec_title">
    <h1 class="fs-200 fc-color--dark" style="padding-bottom: 2vh;">Add Test</h1>
</div>
<div class="button" style="margin-top: 2vh;">
            <?= $component->button('btn', '', '+ Add Template', 'button--class-0', 'button-2'); ?>
        </div>
<?php $form = Form::begin('', 'post'); ?>
<div class="semi-header-container">

    <div class="field-container">
        <section class="reg_body-spec" style="padding-bottom:50px">


            <!-- <div class="reg-body-spec fields" style="padding-left:15vw"> -->
            <table>
                <?php echo $form->field($model, 'name', 'Name*', 'field', 'text', 'name') ?>
                <?php echo $form->field($model, 'test_fee', 'Fee*', 'field', 'text', 'test_fee') ?>
                <?php echo $form->field($model, 'hospital_fee', 'Hospital Fee (15%)*', 'field', 'text', 'hospital_fee') ?>
                <?php echo $form->Select($model, 'title', 'Exsisting Template Type', 'field', ['CBC', 'ALT', 'select'], 'picker'); ?>

            </table>

            <!-- </div> -->
            <div class="button" style="margin-top: 2vh;">
                <?= $component->button('btn', '', 'Add', 'button--class-0', 'button-1'); ?>
            </div>

    </div>

    <?php Form::end() ?>


    </section>
</div>

</div>



<script>
    elementsArray = document.querySelectorAll("#button-2");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-test-template?spec=template'; //pass the variable value
        });
    });
</script>