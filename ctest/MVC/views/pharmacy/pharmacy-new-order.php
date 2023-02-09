

<?php
    /** @var $model \app\models\User */
?>

<p class="navigation-text-line-p"> 
    <a class="navigation-text-line-link" href="/ctest/pharmacy-orders-pending">orders</a>/
    <a class="navigation-text-line-link">add new order</a> 
</p>


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
    <table>
    <?php echo $form->spanfield($model,'name','Medicine Name','field','text') ?>
    <?php echo $form->spanfield($model,'amount','Amount','field','text') ?>

    </table>
    <div><?php echo $component->button("add-order","submit","Add Order","button--class-0","add-order")?></div>
    
    </div>
   
    
    <?php Form::end() ?>   
 
    </body>
</html>