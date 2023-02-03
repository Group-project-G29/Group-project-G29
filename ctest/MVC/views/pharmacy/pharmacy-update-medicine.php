<?php
    /** @var $model \app\models\User */
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
use app\core\Application;
use app\core\component\Component;

$component=new Component();
$form=Form::begin('/ctest/update-medicine?cmd=update&id='.Application::$app->session->get('medicine'),'post');?> 

<section class="form-body" style="padding-bottom:100px">
    <div class="main_title">
        <h2 class="fs-150 fc-color--dark">Update Medicine</h2>
       
    </div>
    <div class="form-body-fields">
    <table>
    <?php echo $form->spanfield($model,'name','Medicine Name*','field','text') ?>
    <?php echo $form->spanfield($model,'strength','Strength*','field','text') ?>
    <?php echo $form->spanfield($model,'brand','Brand*','field','text') ?>
    <?php echo $form->spanselect($model,'category','field',['select'=>'select','medication'=>'medication',"medical devices"=>"medical devices",'self care'=>'self care','wellness'=>'wellness'],'category')?>
    <?php echo $form->spanselect($model,'unit','field',['select'=>'select','tablet'=>'tablet','vile'=>'vile','bottle'=>'bottle','device'=>'device','grams'=>'grams'],'unit')?>
    <?php echo $form->spanfield($model,'unit_price','Unit Price*','field','text') ?>
    <?php echo $form->spanfield($model,'amount','Amount','field ','text') ?>
    <?php echo $form->spanfield($model,'img','Medicine Picture','field','file') ?>
    </table>
    <div><?php echo $component->button("update-medicine","submit","Update Medicine","button--class-0","update-medicine")?></div>
    
    </div>
   
    
    <?php Form::end() ?>   
 
    </body>
</html>