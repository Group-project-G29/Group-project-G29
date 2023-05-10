
<?php
    /** @var $model \app\models\User */
?>

<?php

use app\core\component\Component;
use \app\core\form\Form;
use app\models\Patient;

$component=new Component();

$form=Form::begin('','post');?>
<?php
    use app\core\Application;
    if((Application::$app->session->get('user'))){
    unset($_SESSION['user']);
} ?>
<section class="reg_body fix-margin">
      <div class="NIC">
        <img src="./media/anim_icons/NIC.gif">
    </div>
    <div class="get-nic-content">
        <h3>Enter your NIC number  here.</h3>
        <h4>We'll send E-mail with url to change your password.</h4>
    </div>
    <div class="OTP-container">
       <?=$form->field($patient,'nic','','Field','text',''); ?> 
       <?=$component->button('confirm','submit','Confirm','button--class-0',''); ?>
    </div>
<?$form=Form::end()?>
</section>