<?php
use app\core\component\Component;

$component = new Component();


use app\core\DbModel;
use \app\core\form\Form; ?>

<div class="semi-header-container" style="margin-left:-8vw"> 
    <div class="field-container">
        <div class="header-container" style=" padding-top:0vh;">

            <?php $form = Form::begin('', 'post'); ?>
            <section class="reg_body-spec" style="padding:5vw;">
                <!-- <div class="reg-body-spec fields" style="padding-left:15vw"> -->
                <table class="field-tab">
                    <?php $template_types = array(); ?>


                    <?php $template_ID = []; ?>


                    <h1 class="fs-200 fc-color--dark" style="padding-bottom: 2vh;">Add New Laboratory Test</h1>

                   
                   
                        <?php echo $form->spanfield($labtestmodel, 'name', 'Name*', 'field', 'text', 'name'); ?>
                        
                        <?php echo $form->spanfield($labtestmodel, 'test_fee', 'Fee*', 'field', 'text', 'test_fee');?>
                    
                        <?php echo $form->spanfield($labtestmodel, 'hospital_fee', 'Hospital Fee', 'field', 'text', 'hospital_fee');?>
                    
                    
                </table>
                <div class="button" style=" margin-top: 2vh;padding-left:15vw; margin-left:4.7vw;" id=<?= $labtestmodel->name?>>
                    <?= $component->button('btn-0', '', 'Submit', 'button--class-0 width-10', 'btn-1'); ?>
                </div>
                <?php Form::end() ?>
               
            </div>
        </section>


    </div>
</div>
    
    

    <script>
        
        elementsArray = document.querySelectorAll("#btn-1");
        console.log(elementsArray);
        elementsArray.forEach(function(elem) {
            elem.addEventListener("click", function() {
                location.href = 'lab-add-new-template?id='+elem.id; //pass the variable value
            });
        });
        
        // var closebtn = document.getElementById("closebtn");
        // var addtemplatebtn = document.getElementById("btn-1");
        // // var add = document.getElementById("btn-2");
        // addtemplatebtn.onclick = function(event) {
        //     event.preventDefault();
        //     popup.style.display = "block";

        // }
        // closebtn.onclick = function() {
        //     popup.style.display = "none";
        // }
        // add.onclick=function(x){
        //     // x.disable=true;
        //     popup.style.display="none";
        // }

        // window.onclick = function(event) {
        //     if (event.target == popup) {

        //     }
        // }
    </script>