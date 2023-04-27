<?php

use app\core\component\Component;

$component = new Component();
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
use app\core\Application;


$form=Form::begin('/ctest/lab-personal-detail-update?cmd=update&id='.Application::$app->session->get('user'),'post');?> 
<div class="header-container"  style="padding-top:0vw">
    
    <div class="semi-header-container">
    <div class="reg-body-spec_title">
        <h1 class="fs-200 fc-color--dark" style="padding-bottom: 2vh;">Update <?= $user['name']?></h1>
        <h4 class="fc-color--dark" style="padding-bottom: 2vh;">Employee ID = <?= $user['emp_ID']?></h1>
    </div>
        <div class="field-container">
            <section class="reg_body-spec" style="padding-bottom:50px">


                <div class="reg-body-spec fields" style="padding-left:5vw">
                    <table>
                        <?php echo $form->spanfield($model, 'name', 'Full Name*', 'field', 'text') ?>
                        <?php echo $form->spanfield($model, 'nic', 'NIC*', 'field', 'text') ?>
                        <?php echo $form->spanfield($model, 'age', 'Age*', 'field', 'text') ?>
                        <?php echo $form->spanfield($model, 'email', 'Email*', 'field', 'text') ?>
                        <?php echo $form->spanfield($model, 'address', 'Address*', 'field', 'text') ?>
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

