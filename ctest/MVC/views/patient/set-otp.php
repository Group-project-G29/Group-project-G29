
<?php
    /** @var $model \app\models\User */
?>

<?php

use app\core\component\Component;
use \app\core\form\Form;
$component=new Component();

$form=Form::begin('','post');?>
<?php
    use app\core\Application;
    if((Application::$app->session->get('user'))){
    unset($_SESSION['user']);
} ?>
<section class="reg_body fix-margin">
    <div class="reg-body_title">
         <h1 class="fs-200 fc-color--dark">OTP</h1>
    </div>
    <div class="OTP-container">
        <input type="text" class='field'>
        <input type="text" class='field'>
        <input type="text" class='field'>
        <input type="text" class='field'>
    </div>
    <?=$component->button('otp','','Send OTP','button--class-0',''); ?>
    <?=$component->button('confirm','','Confirm','button--class-0',''); ?>
   <?php Form::end() ?>

</section>