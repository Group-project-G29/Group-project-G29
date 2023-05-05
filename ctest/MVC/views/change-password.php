
<?php
    /** @var $model \app\models\User */
?>

<?php
use \app\core\form\Form;

$form=Form::begin('','post');?>
<?php
    use app\core\Application;
?>
<section class="reg_body fix-margin">
    <div class="reg-body_title">
         <h1 class="fs-200 fc-color--dark">Change Password<h1>
    </div>
    <div class="reg-body_fields">
    <?php echo $form->field($model,'password','Password','field','password') ?>
    <?php echo $form->field($model,'cpassword','Re-enter Password','field','password') ?>
    <div class="reg-body_bottom-text">Forgot Password? click<a href="/ctest/nic"> here</a></div>
    <div><input class="button-lighter" type="submit" value="Log In"></div>
    </div>
 
   <?php Form::end() ?>

</section>