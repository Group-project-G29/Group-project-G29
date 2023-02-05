<?php

use app\core\component\Component;

$component = new Component();
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;

$form = Form::begin('', 'post'); ?>
<div class="header-container">
    <div class="reg-body-spec_title">
        <h1 class="fs-200 fc-color--dark" style="padding-bottom: 2vh;">Add Test</h1>
    </div>
    <div class="semi-header-container">

        <div class="field-container">
            <section class="reg_body-spec" style="padding-bottom:50px">


                <div class="reg-body-spec fields" style="padding-left:15vw">
                    <table>
                        <?php echo $form->spanfield($model, 'name', 'Name*', 'field', 'text') ?>
                        <?php echo $form->spanfield($model, 'fee', 'Fee*', 'field', 'text') ?>
                    </table>
                    <div class="button" style="margin-top: 2vh;">
                        <?= $component->button('btn', '', 'Add', 'button--class-0'); ?>
                    </div>
                </div>

        </div>

        <?php Form::end() ?>
        </section>
    </div>
</div>



<!-- <script>
  elementsArray = document.querySelectorAll(".button");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-view-all-test'; //pass the variable value
    });
  });
</script> -->