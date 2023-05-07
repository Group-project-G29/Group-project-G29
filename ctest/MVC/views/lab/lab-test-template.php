<?php

use app\core\component\Component;
use app\core\DbModel;
use \app\core\form\Form;
use app\models\TemplateContent;

$component = new Component();
$contentM = new TemplateContent();

?>

<div>
    
    
    
    </div>
    
    <div class="semi-header-container-1">
        <div class="semi-field-container">
           <div class="flex"><h1><?= $temp_title_sub["title"] ?></h1><h2> <?= $temp_title_sub["subtitle"] ?></h2></div>
    </div>
    <div class="field-container" style="margin-left:5vw;margin-top:1vw">
        <?php $form = Form::begin('', 'post'); ?>

        <!-- <div class="reg-body-spec fields" style="padding-left:15vw"> -->

        <table class="template">
            <tr><th>Content Type</th><th>Parameter Name</th><th>Reference Range</th><th>Metric</th></tr>
            <tr>

                <td> <?php echo $form->labselect($contentM, 'type', '', 'field-1', ['field', 'text','select'], 'picker'); ?></td>
                <td> <?php echo $form->labfield($contentmodel, 'name', '', 'hide', 'text', 'name'); ?></td>
                <td><?php echo $form->labfield($contentmodel, 'reference_ranges', '', 'hide', 'text', 'range'); ?></td>
                <td> <?php echo $form->labselect($contentM, 'metric', '', 'hide', ['g/dl','IU/L', 'MIL/UL', 'G/UL', 'FL', 'select'], 'metric'); ?></td>
                <td>
                    <div class="button" >
                        <?= $component->button('btn', '', 'Add Content', 'button--class-0 width-10', 'btn-2'); ?>
                    </div>
                </td>
            </tr>
    





    </div>

    <?php Form::end() ?>

        
        
            <?php foreach ($contents as $content) : ?>
                <tr class='table-row  ' id=<?= $content["content_ID"] ?>>

                    <?php if ($content['type'] === "field") : ?>
                        <td><b>Type :</b><?= $content['type'] ?> </td>
                        <td><b>Name :</b><?= $content['name'] ?> </td>
                        <td><b>Metric :</b><?= $content['metric'] ?> </td>
                        <td><b>Reference Ranges :</b><?= $content['reference_ranges'] ?> </td>
                        <td><?= $component->button('btn', '', 'Remove Content', 'btn-1', $content["content_ID"]); ?></td>
                        <!-- <td><?= $component->button('btn', '', 'Edit', 'btn-2', $content["content_ID"]); ?></td> -->


                    <?php endif; ?>

                    <?php if ($content['type'] === "image") : ?>
                        <td><b>Type :</b><?= $content['type'] ?> </td>
                        <td><b>Name :</b><?= $content['name'] ?> </td>
                        <td><b> </b><?    ?> </td>
                        <td><b> </b><?    ?> </td>



                        <td><?= $component->button('btn', '', 'Remove Content', 'btn-1', $content["content_ID"]); ?></td>
                        <!-- <td><?= $component->button('btn', '', 'Edit', 'btn-2', $content["content_ID"]); ?></td> -->

                    <?php endif; ?>

                    <?php if ($content['type'] === "text") : ?>
                        <td><b>Type :</b><?= $content['type'] ?> </td>
                        <td><b>Name :</b><?= $content['name'] ?> </td>
                        <td><b> </b><?    ?> </td>
                        <td><b> </b><?    ?> </td>


                        <td><?= $component->button('btn', '', 'Remove Content', 'btn-1', $content["content_ID"]) ?></td>

                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    

</div>


<!-- //popup -->


<!-- <div class="popup-container" id="popup">
    <div class="modal-form">
        
        <h1 class="modal-title">Update Template content</h1>
        <?php $form = Form::begin('lab-test-template?mod=update ', ' '); ?>

        <div class="form-body">
            
            <?php if ($contents[0]['type'] === "field") : ?>
             //  echo $form->select($model, 'type', 'Type', 'field', ['select', 'image', 'field', 'text'], 'picker');  -->
<!-- <?php echo $form->field($model, 'name', 'Name', '', 'text', 'name'); ?>
            <?php echo $form->field($model, 'reference_ranges', 'Reference Range', '', 'text', 'range'); ?>
             <?php echo $form->select($model, 'metric', 'Metric', '', ['K/UL', 'MIL/UL', 'G/UL', 'FL', 'select'], 'metric'); ?>
             
             <?php elseif ($contents[0]['type'] === "image") : ?>
              <?php echo $form->field($model, 'name', 'Name', '', 'text', 'name'); ?> -->

<!-- //  echo $form->field($model, 'position', 'Upload', '', 'file', 'img');  -->
<!-- <?php elseif ($contents[0]['type'] === "text") : ?>
              <?php echo $form->field($model, 'name', 'Name', '', 'text', 'name'); ?>

              <?php endif ?>

            <?= $component->button('btn', 'submit', 'update', 'button--class-5', 'btn-3'); ?>


            <?php Form::end() ?>
           

        </div>
        <?= $component->button('btn', 'submit', "&times", '', 'closebtn'); ?>

    </div>

</div> -->




<script>
    const select = document.querySelector("#picker");
    const name = document.querySelector("#name");
    const reference_ranges = document.querySelector('#range');
    const metric = document.querySelector('#metric');


    function hide(element, hideClass = 'hide', visibleClass = 'field-1') {
        element.classList.remove(visibleClass);
        element.classList.add(hideClass);
    }

    function visible(element, hideClass = 'hide', visibleClass = 'field-1') {
        element.classList.remove(hideClass);
        element.classList.add(visibleClass);
    }
    select.addEventListener('change', () => {

        if (select.value == 'field') {
            visible(name);
            visible(reference_ranges);
            visible(metric);
            hide(img);

        } else if (select.value == 'image') {
            visible(name);
            hide(img);
            visible(reference_ranges);
            hide(metric)


        } else if (select.value == 'text') {
            visible(name);
            hide(reference_ranges);
            hide(metric)
            hide(img);


        }

    });
    const txt1 = document.getElementById('template');
    // const btn1 = document.getElementById('button');
    const out1 = document.getElementById('output');

    function fun1() {
        out1.innerHTML = txt1.value;

    }

    //btn1.addEventListener('click', fun1);

    elementsArray = document.querySelectorAll(".btn-1");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function(event) {
            event.preventDefault();
            location.href = 'lab-test-template?cmd=delete&id=' + elem.id;
        });
    });

    elementsArray = document.querySelectorAll(".btn-2");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-test-template?cmd=update&id=' + elem.id;
        });
    });

    elementsArray = document.querySelectorAll("#btn-4");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-view-all-template';
        });
    });

    var popup = document.getElementById("popup");
    var closebtn = document.getElementById("closebtn");
    var addeditbtn = document.getElementById("btn-2");
    var add = document.getElementById("btn-3");
    addeditbtn.onclick = function() {
        popup.style.display = "block";
    }
    closebtn.onclick = function() {
        popup.style.display = "none";
    }
    add.onclick = function(x) {
        x.disable = true;
    }

    window.onclick = function(event) {
        if (event.target == popup) {

        }
    }
</script>