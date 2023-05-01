<?php

// var_dump($template);
// var_dump($template_name_list);
// exit;

use app\core\component\Component;

$component = new Component();




use app\core\DbModel;
use \app\core\form\Form; ?>

<!-- </div> -->


<div class="semi-header-container">
    <div class="field-container">
        <div class="header-container" style=" padding-top:3vh;">

           <h3><b>Test Name :</b><?=$testDetail[0]['name']?></h3>
           <h3><b> Test Fee :</b><?=$testDetail[0]['test_fee']?></h3>
           <h3><b> Hospital Fee :</b><?=$testDetail[0]['hospital_fee']?></h3>
          <div class="" style="margin-top:3vw">
           <?= $component->button('btn', '', 'Add Template', 'button--class-0', 'btn-1'); ?>
           <?= $component->button('btn', '', 'Update', 'button--class-11', $testDetail[0]['name']); ?>
            </div>
    </div>
       


    </div>
</div>
    <!-- popup -->
    
    <div class="popup-container hide" id="popup">
        <div class="modal-form" >

            <h1 class="modal-title">Add Report Template</h1>

            <div class="form-body" style="margin-top:0vw ;">
                <?php $form = Form::begin('lab-add-new-template?cmd=tmp&id=' .$testDetail[0]['name'], 'post'); ?>

                <?php echo $form->field($templatemodel, 'title', 'Title*', 'field-2', 'text', 'title-1') ?>
                <?php echo $form->field($templatemodel, 'subtitle', 'Sub Title', 'field-2', 'text', 'sub') ?>
               
                <div class="button" style="margin-top: 2vh;padding-left:15vw">
                <?= $component->button('btn', 'submit', 'NEXT', 'button--class-0 width-10', 'btn-2'); ?>
                </div>

                <?php Form::end() ?>
                <?= $component->button('btn', 'submit', 'next', 'button--class-5', 'btn-2'); ?>

            </div>
            <?= $component->button('btn', 'submit', "&times", '', 'closebtn'); ?>

        </div>

    </div>
  

    <script>
       
        
        var popup = document.getElementById("popup");
        // popup.style.display = "none";
        elementsArray = document.querySelectorAll("#btn-2");
        console.log(elementsArray);
        elementsArray.forEach(function(elem) {
            elem.addEventListener("click", function() {
                location.href = 'lab-test-template'; //pass the variable value
            });
        });

        
        // popup.style.display = "none";
        elementsArray = document.querySelectorAll(".button--class-11");
        console.log(elementsArray);
        elementsArray.forEach(function(elem) {
            elem.addEventListener("click", function() {
                location.href = 'lab-test-update?mod=update&id=' + elem.id; 
            });
        });
        
        var closebtn = document.getElementById("closebtn");
        var addtemplatebtn = document.getElementById("btn-1");
        // var add = document.getElementById("btn-2");
        addtemplatebtn.onclick = function(event) {
            event.preventDefault();
            popup.style.display = "block";

        }
        closebtn.onclick = function() {
            popup.style.display = "none";
        }
        // add.onclick=function(x){
        //     // x.disable=true;
        //     popup.style.display="none";
        // }

        // window.onclick = function(event) {
        //     if (event.target == popup) {

        //     }
        // }
    </script>