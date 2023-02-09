<?php

use app\core\component\Component;

$component = new Component();
// var_dump($userinfo);
// exit;
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
use app\core\Application;


$form=Form::begin('/ctest/receptionist-personal-detail-update?cmd=update&id='.Application::$app->session->get('userinfo'),'post');?> 

    
    <div class="semi-header-container">
    <div class="reg-body-spec_title" >
        <h1 class="fs-200 fc-color--dark" style="padding-top: 2vh;text-align:center"><?= $userinfo['name']?></h1>
        <h4 class="fc-color--dark" style="padding-bottom: 2vh;text-align:center">Employee ID = <?= $userinfo['emp_ID']?></h1>
    </div>
        <div class="field-container">
            <section class="reg_body-spec">


                <div class="reg-body-spec fields">
                    <table>
                        <?php echo $form->spanfield($model, 'name', 'Full Name*', 'field', 'text') ?>
                        <?php echo $form->spanfield($model, 'nic', 'NIC*', 'field', 'text') ?>
                        <?php echo $form->spanfield($model, 'age', 'Age*', 'field', 'text') ?>
                        <?php echo $form->spanfield($model, 'email', 'Email*', 'field', 'text') ?>
                        <?php echo $form->spanfield($model, 'address', 'Address*', 'field', 'text') ?>
                    </table>
                    <div class="button" style="margin-top: 2vh;margin-bottom:2vh;padding-left:18vw">
                        <?= $component->button('update-test', 'submit', 'Update', 'button--class-0'); ?>
                    </div>
                </div>

        </div>

        <?php Form::end() ?>
        
        </section>
    </div>

<!-- <script>
        const btn=document.querySelectorAll('.button');
        btn.addEventListener('click',()=>{
            location.href="/ctest/receptionist-personal-detail-update?cmd=update&id="+el.id;
        })
    </script> -->
