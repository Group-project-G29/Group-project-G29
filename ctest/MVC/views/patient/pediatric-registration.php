

<?php

use app\core\DbModel;
use \app\core\form\Form;
$form=Form::begin('','post');?> 
<section class="reg_body-ped" style="padding-bottom:-100px">
    <div class="reg-body_title-pe">
        <h4 class="fc-color--dark">Pediatric Patient Registration</h4>
    </div>
    <div class="reg-body_bottom-text">Adult patient click <a href="/ctest/patient/adult/login"> here</a></div>
    <div class="reg-body_fields">
    <?php echo $form->field($model,'firstname','First Name*','field','text') ?>
    <?php echo $form->field($model,'lastname','Last Name*','field','text') ?>
    <?php echo $form->field($model,'age','Patient Age*','field','text') ?>
    <?php echo $form->select($model,'gender','Gender','field',['select','male','female'],'gender')?>
    <?php echo $form->field($model,'guardian_name','Guradian Name*','field','text') ?>
    <?php echo $form->field($model,'nic','Guradian NIC*','field','text') ?>
    <?php echo $form->field($model,'contact','Guardian Contact*','field','text') ?>
    <?php echo $form->field($model,'email','Guardian Email*','field','text') ?>
    <?php echo $form->field($model,'address','Address','field ','text') ?>
    <?php echo $form->field($model,'password','Password*','field','password') ?>
    <?php echo $form->field($model,'cpassword','Retype Password*','field','password') ?>
    <div><input class="button-lighter" style="margin-bottom:3vh;" type="submit" value="Register"></div>
    </div>
   
    
    <?php Form::end() ?>   
     
    </section>
