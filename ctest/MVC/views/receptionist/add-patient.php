<?php

use app\core\component\Component;

    $component=new Component();
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
$form=Form::begin('','post');?> 
<div class="field-container">
    <section class="reg_body-spec" style="padding-bottom:100px">
        <div class="reg-body-spec_title">
            <h1 class="fs-200 fc-color--dark">Add New Patient Here</h1>
        </div>
        
        <div class="reg-body-spec fields">
        <table>
        <?php echo $form->spanfield($model,'name','Name*','field','text') ?>
        <?php echo $form->spanfield($model,'nic','NIC*','field','text') ?>
        <?php echo $form->spanfield($model,'age','Age*','field','text') ?>
        <?php echo $form->SpanSelect($model,'gender','Gender*','field',['select','male','female'],'gender')?>
        <?php echo $form->spanfield($model,'contact','Contact*','field','text') ?>
        <?php echo $form->spanfield($model,'email','Email*','field','text') ?>
        <?php echo $form->spanfield($model,'address','Address','field ','text') ?>
        <?php echo $form->spanfield($model,'password','Password*','field','password') ?>
        <?php echo $form->spanfield($model,'cpassword','Retype Password*','field','password') ?>
        
        </table>
        <?= $component->button('btn','','Add New Patient','button--class-0') ?>
        </div>
</div>    
        
        <?php Form::end() ?>   
        
        </section>
    </body>
</html>