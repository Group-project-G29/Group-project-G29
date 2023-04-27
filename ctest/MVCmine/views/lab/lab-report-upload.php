<?php

use app\core\component\Component;
use \app\core\form\Form;

$component = new Component();

$form = Form::begin('', 'post'); ?>
<div class="header-container">
    <div class="reg-body-spec_title">
        <h1 class="fs-200 fc-color--dark" style="padding-bottom: 2vh;">Upload Report</h1>
    </div>
    <div class="semi-header-container">

        <div class="field-container">
            <div class="detail-container">

                <table class="table-session">
                    <tr class="table-row-session">
                        <td>Request No</td>
                        <td>:</td>
                        <td class="table-row-data"></td>
                    </tr>
               
                <tr class="table-row-session">
                    <td>Doctor</td>
                    <td>:</td>
                    <td class="table-row-data">Dr.<?= $tests['doc_name'] ?></td>
                </tr>
                <tr class="table-row-session">
                    <td>patient</td>
                    <td>:</td>
                    <td class="table-row-data"><?= $tests['patient_name'] ?></td>
                </tr>
                <tr class="table-row-session">
                    <td>Requested date</td>
                    <td>:</td>
                    <td class="table-row-data"><?= $tests['requested_date_time'] ?></td>
                </tr>
                </table>
            </div>
            <section class="reg_body-spec" style="padding-bottom:50px">

                <div class="reg-body-spec fields" style="padding-left:15vw">
                    <table>
                    <!-- <?php echo $form->field($model,'img','Report','field','file') ?> -->

                    </table>
                    <!-- <div class="button" style="margin-top: 2vh;">
                        <?= $component->button('btn', '', 'upload', 'button--class-0'); ?>
                    </div> -->
                </div>

        </div>

        <?php Form::end() ?>
        </section>
    </div>
</div>









<script>
    elementsArray = document.querySelectorAll(".button-1");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-add-new-test'; //pass the variable value
        });
    });

    elementsArray = document.querySelectorAll(".button");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-test-update?mod=update&id=' + elem.id; //pass the variable value
        });
    });

    elementsArray = document.querySelectorAll(".button-0");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-test-delete?cmd=delete&id=' + elem.id; //pass the variable value
        });
    });
</script>