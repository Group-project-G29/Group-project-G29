<?php

// var_dump($template);
// var_dump($template_name_list);
// exit;

use app\core\component\Component;

$component = new Component();
$component = new Component();

use app\core\DbModel;
use \app\core\form\Form; ?>
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

                <?php $template_types = []; ?>
                

                <?php foreach ($template_name_list as $key => $template_name) :
                    $template_types[$key] = $template_name["title"];
                    $template_ID[$key] = $template_name["template_ID"];
                    
                endforeach; ?>
                


                <?php
// echo "$template_types";
// echo "$template_ID";

                echo $form->field($model, 'name', 'Name*', 'field', 'text', 'name');
                echo $form->field($model, 'test_fee', 'Fee*', 'field', 'text', 'test_fee');
                echo $form->field($model, 'hospital_fee', 'Hospital Fee (15%)*', 'field', 'text', 'hospital_fee');
                // echo  $form->Select($tempmodel, 'template_ID', 'Exsisting Template Type', 'field', $template_types , 'picker'); 
                // echo  $form->SpanSelect($tempmodel, '', 'Exsisting Template Type', 'field', ["$template_ID"=>"$template_types"], 'picker');
                // echo  $form->SpanSelect($tempmodel, '', 'Exsisting Template Type', 'field', ["$template_ID"=>"$template_types"], 'picker');

                ?>
                
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



<!-- <script>
    elementsArray = document.querySelectorAll("#button-2");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-test-template?spec=template&spec=content'; //pass the variable value
        });
    });
</script> -->
<script>
    elementsArray = document.querySelectorAll("#button-2");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-test-template-main'; //pass the variable value
        });
    });
</script>