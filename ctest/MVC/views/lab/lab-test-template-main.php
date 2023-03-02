<?php


    use app\core\component\Component;

    $component = new Component();
    $component = new Component();

    use app\core\DbModel;
    use \app\core\form\Form; ?>
<!-- <div class="header-container"> -->
<div class="popup-container" style="padding-top: 15vh;">
    <div class="modal-form">
        <div class="reg-body-spec_title">
            <h1 class="fs-200 fc-color--dark" style="padding-bottom: 0vh;">Add Report Template</h1>
        </div>
        <?php $form = Form::begin('', 'post'); ?>

        <?php echo $form->field($templatemodel, 'title', 'Title*', 'field', 'text', 'title-1') ?>
        <?php echo $form->field($templatemodel, 'subtitle', 'Sub Title', 'field', 'text', 'sub') ?>

        <?= $component->button('btn', '', '+ Add', 'button--class-0', 'btn-1'); ?>
       

        <?php Form::end() ?>
        <?= $component->button('btn', '', 'Go', 'button--class-0', 'btn-2'); ?>
        
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
</script>