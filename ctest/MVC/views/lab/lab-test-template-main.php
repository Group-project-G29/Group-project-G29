<?php


    use app\core\component\Component;

    $component = new Component();
    $component = new Component();

    use app\core\DbModel;
    use \app\core\form\Form; ?>

<!-- <div class="header-container"> -->
<?= $component->button('btn', '', 'Add Template', 'button--class-6', 'btn-1'); ?>

<div class="popup-container" id="popup">
    <div class="modal-form">
        
            <h1 class="modal-title">Add Report Template</h1>
        
        <div class="form-body">
        <?php $form = Form::begin('', 'post'); ?>

        <?php echo $form->field($templatemodel, 'title', 'Title*', 'field-1', 'text', 'title-1') ?>
        <?php echo $form->field($templatemodel, 'subtitle', 'Sub Title', 'field-1', 'text', 'sub') ?>

        <?= $component->button('btn', '', '+ Add', 'button--class-0', 'btn-1'); ?>
       

        <?php Form::end() ?>
        <?= $component->button('btn', '', 'Go', 'button--class-0', 'btn-2'); ?>
       
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
    addtemplatebtn.onclick=function(){
        popup.style.display="block";
    }
    closebtn.onclick=function(){
        popup.style.display="none";
    }

    window.onclick=function(event){
        if(event.target== popup){
            popup.style.display="none";
        }
    }
</script>