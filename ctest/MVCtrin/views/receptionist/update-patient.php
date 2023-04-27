<?php
    /** @var $model \app\models\User */

use app\core\Application;
use app\core\component\Component;

?>


<?php
$component=new Component();
use app\core\DbModel;
use \app\core\form\Form;
$form=Form::begin('receptionist-handle-patient?cmd=update&id='.Application::$app->session->get('patient'),'post');?> 
<div class="field-container">
    <section class="reg_body-spec" style="padding-bottom:100px">
        <div class="reg-body-spec_title">
            <h1 class="fs-200 fc-color--dark">Update Patient Here</h1>
        </div>
        <div class="reg-body-spec fields">
        <table>

        <?php echo $form->spanfield($model,'name','Name*','field','text') ?>
        <?php echo $form->spanfield($model,'nic','NIC*','field','text') ?>
        <?php echo $form->spanfield($model,'age','Age*','field','text') ?>
        <?php echo $form->spanselect($model,'gender','Gender','field',['select'=>'select','male'=>'male','female'=>'female'],'gender')?>
        <?php echo $form->spanfield($model,'contact','Contact*','field','text') ?>
        <?php echo $form->spanfield($model,'email','Email*','field','text') ?>
        <!-- <?php echo $form->spanfield($model,'contact','Contact','field','text') ?> -->
        <?php echo $form->spanfield($model,'address','Address','field ','text') ?>
        <div style="display:none;">
       
    </div>
        </table>
        </div>
    
        
        <?php Form::end() ?>   
        <div>
            <?= $component->button('btn','','Update Patient','button--class-0',Application::$app->session->get('patient')) ?>
        </div>
        </section>
</div>
    </body>
    <script>
        const btn=document.querySelectorAll('.button--class-0');
        btn.addEventListener('click',()=>{
            location.href="/ctest/receptionist-handle-patient?cmd=update&id="+el.id;
        })
    </script>
</html>