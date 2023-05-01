<?php

use app\core\component\Component;

$component = new Component();
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
use app\core\Application;


$form = Form::begin(' ' . Application::$app->session->get('contentlist'), 'post'); ?>
<div class="header-container">
    <div class="reg-body-spec_title">
        <h1 class="fs-200 fc-color--dark" style="padding-bottom: 2vh;">Update Test</h1>
    </div>
    <div class="semi-header-container">

        <div class="field-container">
            <section class="reg_body-spec" style="padding-bottom:50px">


                <div class="reg-body-spec fields" style="padding-left:15vw">
                    <table>

                         <?php echo $form->select($model, 'type', 'Type', 'field', ['select', 'image', 'field', 'text'], 'picker'); ?>
                         <?php echo $form->field($model, 'name', 'Name', '', 'text', 'name'); ?>
                         <?php echo $form->field($model, 'reference_ranges', 'Reference Range', '', 'text', 'range'); ?>
                         <?php echo $form->select($model, 'metric', 'Metric', '', ['select', 'K/UL', 'MIL/UL', 'G/UL', 'FL'], 'metric'); ?>
                         <?php echo $form->field($model, 'position', 'Upload', '', 'file', 'img'); ?>
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