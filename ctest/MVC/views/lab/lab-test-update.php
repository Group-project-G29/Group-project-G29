<?php

use app\core\component\Component;

$component = new Component();
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
use app\core\Application;
use app\models\LabTest;
$LabTest=new LabTest();
$form = Form::begin('/ctest/lab-test-update?cmd=update&id=' . Application::$app->session->get('labtest'), 'post'); ?>

        <div class="semi-header-container" style="margin-left:-10vw;" >
            <div class="field-container">
                        <div class="header-container" style=" padding-top:0vh;">
                            <h1>Update Laboratory Test Here</h1>

            <table style="margin:5vw" class="field-tab">
                <?php echo $form->spanfield($model, 'name', 'Name*', 'field', 'text') ?>
                <?php echo $form->spanfield($model, 'test_fee', 'Test Fee*', 'field', 'text') ?>
                <?php echo $form->spanfield($model, 'hospital_fee', 'Hospital Fee*', 'field', 'text') ?>

            </table>
            <div class="button flex" style="padding-left:15vw">
                <?= $component->button('update-test', 'submit', 'Update', 'button--class-0 width-10'); ?>
                <?php Form::end() ?>
                <!-- check if test contain a template else show button to add template -->
                <?php if(!$LabTest->fetchAssocAll(['name'=>urldecode(Application::$app->session->get('labtest'))])[0]['template_ID']):?>                    <!-- if template id is null show add new template -->
                    <div>
                        <?= $component->button('update-test', 'submit', 'Add Template', 'button--class-0 btn',Application::$app->session->get('labtest')); ?>
                    </div>
                <?php endif;?>
            </div>
        </div>

        <!-- </div> -->
                </div>
        <!-- </section> -->
    </div>
    <!-- </div> -->

<script>
    const temp=document.querySelector(".btn");
    temp.addEventListener('click',(event)=>{
        event.preventDefault();
        location.href="lab-add-new-test?spec=lab-test-template&id="+temp.id;
    })
</script>