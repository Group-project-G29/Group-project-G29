

<?php
    /** @var $model \app\models\User */
    var_dump($model);
    exit;
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
use app\core\component\Component;
$component=new Component();
$form=Form::begin('pharmacy/pharmacy-new-order','post');?> 

<section class="form-body" style="padding-bottom:100px">

    <div class="main_title">
        <h2 class="fs-150 fc-color--dark">Enter the Medicines</h2>
    </div>

    <div class="form-body-fields">

<!-- component and call when click the add button -->
    <div class="new-order-add-item"> 
        <div class="new-order-add-item-col">
            <?php echo $form->spanfield($model,'med_ID','Medicine Name','field','text','') ?>
        </div>
        <div class="new-order-add-item-col">
            <?php echo $form->spanfield($model,'amount','Amount','field','text','') ?>
        </div>
        <div class="new-order-add-item-col">
            <?php echo $component->button("add-another","submit","Add Another","button--class-0","add-another")?>
        </div>
    </div>
    
    <div><?php echo $component->button("add-order","submit","Add Order","button--class-0","add-order")?></div>
    
    </div>
    
    <?php Form::end() ?>    



 
    </body>
</html>