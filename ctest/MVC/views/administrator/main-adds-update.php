<?php
    /** @var $model \app\models\User */
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
use app\core\Application;
use app\core\component\Component;

$component=new Component();
$form=Form::begin('/ctest/update-advertisement?cmd=update&id='.Application::$app->session->get('advertisement'),'post');?> 

<section class="form-body-adds" style="padding-bottom:100px">
    <div class="main_title">
        <h2 class="fs-150 fc-color--dark">Update Advertisement</h2>
       
    </div>
    <div class="form-body-fields">
    <table>
    <?php echo $form->spanfield($model,'title','Advertisement Title*','field','text') ?>
    <?php echo $form->spanfield($model,'description','Description*','field','text') ?>
    <?php echo $form->spanfield($model,'remark','Remark*','field','text') ?>
    <?php echo $form->spanfield($model,'image','Advertisement Picture','field','file') ?>
    </table>
    <div><?php echo $component->button("update-advertisement","submit","Update Advertisement","button--class-0","update-advertisement")?></div>
    
    </div>
   
    
    <?php Form::end() ?>   
 
    </body>
</html>