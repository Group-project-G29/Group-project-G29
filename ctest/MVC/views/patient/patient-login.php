
<?php
    /** @var $model \app\models\User */
?>

<?php
use \app\core\form\Form;

$form=Form::begin('','post');?>

<section class="reg_body">
    <div class="reg-body_title">
         <h1 class="fs-200 fc-color--dark">Login Here<h1>
    </div>
    <div class="reg-body_fields">
    <?php echo $form->field($model,'username','Username','field','text') ?>
    <?php echo $form->field($model,'password','Password','field','password') ?>
    <div class="reg-body_bottom-text">Want to register? click<a href="/ctest/patient/pediatric/login"> here</a></div>
    <div><input class="button-lighter" type="submit" value="Log In"></div>
    </div>
 
   <?php Form::end() ?>

</section>
