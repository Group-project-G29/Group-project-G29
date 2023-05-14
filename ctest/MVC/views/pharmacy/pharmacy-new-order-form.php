

<?php
    /** @var $model \app\models\User */
    // var_dump($ordermodel);
    // exit;
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
use app\core\component\Component;

$component=new Component();
$form=Form::begin('pharmacy-new-order?method=addfd','post');?> 

<section class="form-body back-new" style="padding-bottom:100px">

    <div class="main_title">
        <h1 class="fc-color--dark" >Create New Order</h1>
    </div>

    <div class="form-body-fields">

<!-- component and call when click the add button -->
        <div class="new-order-add-item">
            <table>
            <?php echo $form->spanfield($ordermodel,'name','Name','field','text','name') ?>
            <?php echo $form->spanfield($ordermodel,'age','Age','field','text','age') ?>
            <?php echo $form->spanfield($ordermodel,'doctor','Doctor Name','field','text','doctor') ?>
            <?php echo $form->spanfield($ordermodel,'contact','Contact Number','field','text','contact') ?>
            <!-- <?php echo $form->spanselect($ordermodel,'payment_status','Payment Status','field', ['select'=>'select', 'pending'=>'pending', 'successful'=>'completed'] ,'payment_status') ?> -->
            </table>
        </div>
        
        
    </div>
    <div><?php echo $component->button("add-order","submit","Set Order Details","button--class-0  width-10","add-order")?></div>
    
    <?php Form::end() ?>   
 
    </body>
</html>