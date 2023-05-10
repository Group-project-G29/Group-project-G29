
<?php
    /** @var $model \app\models\User */
?>

<?php
use \app\core\form\Form;

$form=Form::begin('','post');?>
<?php
    use app\core\Application;
 if((Application::$app->session->get('user'))){
    unset($_SESSION['user']);
} ?>
<section class="reg_body fix-margin">
    <div class="reg-body_title">
         <h1 class="fs-200 fc-color--dark">Employee Login<h1>
    </div>
    <div class="reg-body_fields">
    <?php echo $form->field($model,'username','Username','field','text') ?>
    <?php echo $form->field($model,'password','Password','field','password') ?>
    <div class="reg-body_bottom-text">Forgot Password? click<a href="/ctest/employee-nic"> here</a></div>
    <div><input class="button-lighter" type="submit" value="Log In"></div>
    </div>
 
   <?php Form::end() ?>

</section>