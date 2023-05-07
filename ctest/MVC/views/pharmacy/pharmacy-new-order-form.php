

<?php
    /** @var $model \app\models\User */
    // var_dump($model);
    // exit;
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
use app\core\component\Component;

$component=new Component();
$form=Form::begin('pharmacy-new-order?method=sod','post');?> 

<section class="form-body" style="padding-bottom:100px">

    <div class="main_title">
        <h2 class="fs-150 fc-color--dark">Create New Order</h2>
    </div>

    <div class="form-body-fields">

<!-- component and call when click the add button -->
    <div class="new-order-add-item">
        <?php echo $form->spanfield($ordermodel,'patient_ID','Patient ID','field','text','patient_ID') ?>
        <?php echo $form->spanfield($ordermodel,'name','Name','field','text','name') ?>
        <!-- <?php echo $form->spanfield($ordermodel,'address','Address ID','field','text','address') ?> -->
        <?php echo $form->spanfield($ordermodel,'contact','Contact Number','field','text','contact') ?>
        <?php echo $form->spanfield($prescriptionmodel,'doctor','Doctor Name','field','text','doctor') ?>
        <?php echo $form->spanfield($prescriptionmodel,'note','Special Note','field','text','note') ?>
        <!-- <?php echo $form->spanselect($ordermodel,'pickup_status','Pickup Status','field', ['delivery'=>'delivery', 'pickup'=>'pickup', 'select'=>'select'] ,'pickup_status') ?> -->
        <!-- <?php echo $form->spanselect($ordermodel,'payment_status','Payment Status','field', ['select'=>'select', 'pending'=>'pending', 'successful'=>'successful'] ,'payment_status') ?> -->
    </div>

    <div><?php echo $component->button("add-order","submit","Set Order Details","button--class-0","add-order")?></div>
    
    </div>
    
    <?php Form::end() ?>   
 
    </body>
</html>