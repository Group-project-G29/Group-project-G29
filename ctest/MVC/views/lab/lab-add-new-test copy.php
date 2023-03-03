<?php

// var_dump($template);
// var_dump($template_name_list);
// exit;

use app\core\component\Component;

$component = new Component();


use app\core\DbModel;
use \app\core\form\Form; ?>

    <?= $component->button('btn', '', '+ Add Template', 'button--class-0', 'btn-1'); ?>
<!-- </div> -->


<?php $form = Form::begin('', 'post'); ?>
<div class="semi-header-container">
    <div class="field-container">
        <section class="reg_body-spec" style="padding:5vw;">
            <!-- <div class="reg-body-spec fields" style="padding-left:15vw"> -->
            <table>
                <?php $template_types = []; ?>
                <?php foreach ($template_name_list as $key => $template_name) :
                    $template_types[$key] = $template_name["title"];
                    $template_ID[$key] = $template_name["template_ID"];          
                endforeach; ?>
                    <h1 class="fs-200 fc-color--dark" style="padding-bottom: 2vh;">Add Test</h1>

                <?php
                echo $form->field($model, 'name', 'Name*', 'field', 'text', 'name');
                echo $form->field($model, 'test_fee', 'Fee*', 'field', 'text', 'test_fee');
                echo $form->field($model, 'hospital_fee', 'Hospital Fee (15%)*', 'field', 'text', 'hospital_fee');
                // echo  $form->Select($tempmodel, 'template_ID', 'Exsisting Template Type', 'field', $template_types , 'picker'); 
                // echo  $form->SpanSelect($tempmodel, '', 'Exsisting Template Type', 'field', ["$template_ID"=>"$template_types"], 'picker');
                 echo  $form->Select($tempmodel, '', 'Exsisting Template Type', 'field', $template_ID, 'picker');
                ?> 
            </table>
            <div class="button" style="margin-top: 2vh;">
                <?= $component->button('btn', '', 'Add Test', 'button--class-0', 'button-1'); ?>
            </div>
    </div>
    <?php Form::end() ?>
    </section>
</div>
<div class="popup-container" id="popup">
    <div class="modal-form">
        
            <h1 class="modal-title">Add Report Template</h1>
        
        <div class="form-body">
        <?php $form = Form::begin('', 'post'); ?>

        <?php echo $form->field($templatemodel, 'title', 'Title*', 'field-1', 'text', 'title-1') ?>
        <?php echo $form->field($templatemodel, 'subtitle', 'Sub Title', 'field-1', 'text', 'sub') ?>

        <?= $component->button('btn', '', '+ Add', 'button--class-5', 'add'); ?>
       

        <?php Form::end() ?>
        <?= $component->button('btn', '', 'Go', 'button--class-5', 'btn-2'); ?>
       
        </div>
        <?= $component->button('btn', '', "&times", '', 'closebtn'); ?>

    </div>

</div>

<script>
    elementsArray = document.querySelectorAll("#btn-2");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-test-template'; //pass the variable value
        });
    });

    var popup=document.getElementById("popup");
    var closebtn=document.getElementById("closebtn");
    var addtemplatebtn=document.getElementById("btn-1");
    var add=document.getElementById("add");
    addtemplatebtn.onclick=function(){
        popup.style.display="block";
    }
    closebtn.onclick=function(){
        popup.style.display="none";
    } 
    add.onclick=function(x){
        x.disable=true;
    }

    window.onclick=function(event){
        if(event.target== popup){
            popup.style.display="none";
        }
    }
</script>