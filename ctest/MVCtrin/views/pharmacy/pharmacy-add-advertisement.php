<?php
    /** @var $model \app\models\User */
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
use app\core\component\Component;

$component=new Component();
$form=Form::begin('','post');?> 

<section class="form-body" style="padding-bottom:100px">

    <div class="main_title">
        <h2 class="fs-150 fc-color--dark">Add Advertisement</h2>
       
    </div>
    <div class="form-body-fields">
    <table>
    <?php echo $form->spanfield($model,'title','Advertisement Title*','field','text') ?>
    <?php echo $form->spanfield($model,'description','Description*','field','text') ?>
    <?php echo $form->spanfield($model,'remark','Remark*','field','text') ?>
    <?php echo $form->spanfield($model,'img','Advertisement Picture','field','file') ?>
    </table>
    <div><?php echo $component->button("add-advertisement","submit","Add Advertisement","button--class-0","add-advertisement")?></div>
    
    </div>
   
    
    <?php Form::end() ?>   
 
    </body>
</html>