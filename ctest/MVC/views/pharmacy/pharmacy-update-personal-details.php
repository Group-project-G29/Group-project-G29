<?php
    /** @var $model \app\models\User */
    use app\core\DbModel;
    use \app\core\form\Form;
    use app\core\Application;
    use app\core\component\Component;

// var_dump($user);
// exit;
?>


<?php



$component=new Component();
$form=Form::begin('/ctest/pharmacy-update-personal-details?cmd=update&id='.Application::$app->session->get('userObject')->emp_ID,'post');?> 

<section class="form-body" style="padding-bottom:100px">
    <div class="main_title">
        <h2 class="fs-150 fc-color--dark">Edit My Details</h2>
    </div><br>
    <p>Employee Number : <?= $user['emp_ID'] ?></p>
    <p><?= $user['nic'] ?></p>
    <p><?= $user['email'] ?></p>


    <div class="form-body-fields">
    <table>
    <?php echo $form->spanfield($model,'name','Employee Name','field','text') ?>
    <?php echo $form->spanfield($model,'contact','Contact Number','field','text') ?>
    <?php echo $form->spanselect($model,'gender','field',['select'=>'select','male'=>'Male',"female"=>"Female"],'gender')?>
    <?php echo $form->spanfield($model,'address','Address','field','text') ?>
    </table>
    <div><?php echo $component->button("update-personalDetails","submit","Save","button--class-0","update-personalDetails")?></div>
    
    </div>
   
    
    <?php Form::end() ?>   
 
    </body>
</html>



