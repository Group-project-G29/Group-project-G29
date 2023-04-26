<?php

use app\core\component\Component;

$component = new Component();
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
use app\core\Application;


$form=Form::begin('/ctest/lab-test-update?cmd=update&id='.Application::$app->session->get('labtest'),'post');?> 
<div class="header-container">
    <div class="reg-body-spec_title">
        <h1 class="fs-200 fc-color--dark" style="padding-bottom: 2vh;">Update Test</h1>
    </div>
    <div class="semi-header-container">

        <div class="field-container">
            <section class="reg_body-spec" style="padding-bottom:50px">

           
                <div class="reg-body-spec fields" style="padding-left:15vw">
                    <table>
                        <?php echo $form->spanfield($model, 'name', 'Name*', 'field', 'text') ?>
                        <?php echo $form->spanfield($model, 'test_fee', 'Test Fee*', 'field', 'text') ?>
                        <?php echo $form->spanfield($model, 'hospital_fee', 'Hospital Fee*', 'field', 'text') ?>
                       
                    </table>
                    <div class="button" style="margin-top: 2vh;">
                        <?= $component->button('update-test', 'submit', 'Update', 'button--class-0'); ?>
                    </div>
                </div>

        </div>

        <?php Form::end() ?>
        </section>
    </div>
</div>

