<?php

// var_dump($template);
// var_dump($template_name_list);
// exit;

use app\core\component\Component;

$component = new Component();
// $curr_test = $labtestmodel->name;
// var_dump($curr_test);
// exit;

use app\core\DbModel;
use \app\core\form\Form; ?>

<!-- </div> -->


<div class="semi-header-container">
    <div class="field-container">
        <div class="header-container" style=" padding-top:0vh;">

            <?php $form = Form::begin('', 'post'); ?>
            <section class="reg_body-spec" style="padding:5vw;">
                <!-- <div class="reg-body-spec fields" style="padding-left:15vw"> -->
                <table>
                    <?php $template_types = array(); ?>


                    <?php $template_ID = []; ?>


                    <h1 class="fs-200 fc-color--dark" style="padding-bottom: 2vh;">Add Test</h1>

                    <?php

                    echo $form->spanfield($labtestmodel, 'name', 'Name*', 'field', 'text', 'name');
                    echo $form->spanfield($labtestmodel, 'test_fee', 'Fee*', 'field', 'text', 'test_fee');
                    echo $form->spanfield($labtestmodel, 'hospital_fee', 'Hospital Fee (15%)*', 'field', 'text', 'hospital_fee');

                    ?>
                </table>
                <div class="button" style="margin-top: 2vh;padding-left:15vw" id=<?= $labtestmodel->name?>>
                    <?= $component->button('btn-0', '', 'Submit', 'button--class-0', 'btn-1'); ?>
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