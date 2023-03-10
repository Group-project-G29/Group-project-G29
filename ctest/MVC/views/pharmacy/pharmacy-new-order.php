

<?php
    /** @var $model \app\models\User */
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
use app\core\component\Component;

$component=new Component();
$form=Form::begin('pharmacy/pharmacy-new-order','post');?> 

<section class="form-body" style="padding-bottom:100px">

    <div class="main_title">
        <h2 class="fs-150 fc-color--dark">Create New Order</h2>
    </div>

    <div class="form-body-fields">

<!-- component and call when click the add button -->
    <div class="new-order-add-item">
        <?php echo $form->spanfield($model,'','Medicine Name','field','text','') ?>
        <?php echo $form->spanfield($model,'','Amount','field','text','') ?>
    </div>

    <p>+ Add </p>

    <div><?php echo $component->button("add-order","submit","Add Order","button--class-0","add-order")?></div>
    
    </div>
    
    <?php Form::end() ?>   
 
    </body>
</html>