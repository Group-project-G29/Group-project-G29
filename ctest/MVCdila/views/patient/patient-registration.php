<?php
    /** @var $model \app\models\User */
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
$form=Form::begin('','post');?> 
<section class="reg_body" style="padding-bottom:100px">
    
    <div class="reg-body_title">
        <h1 class="fs-200 fc-color--dark">Patient Registration</h1>
    </div>
    <div class="reg-body_bottom-text">Pediatric patient click <a href="/ctest/pediatric-registration"> here</a></div>
    <div class="reg-body fields">
    <?php echo $form->field($model,'name','Name*','field','text') ?>
    <?php echo $form->field($model,'nic','NIC*','field','text') ?>
    <?php echo $form->field($model,'age','Age*','field','text') ?>
    <?php echo $form->select($model,'gender',"Gender *",'field',['select','male','female'],'gender')?>
    <?php echo $form->field($model,'contact','Contact*','field','text') ?>
    <?php echo $form->field($model,'email','Email*','field','text') ?>
    <!-- <?php echo $form->field($model,'contact','Contact','field','text') ?> -->
    <?php echo $form->field($model,'address','Address','field ','text') ?>
    <?php echo $form->field($model,'password','Password*','field','password') ?>
    <?php echo $form->field($model,'cpassword','Retype Password*','field','password') ?>
    <div><input class="button-lighter" style="margin-bottom:3vh;" type="submit" value="Register"></div>
    </div>
   
    
    <?php Form::end() ?>   
     
    </section>
    </body>
</html>