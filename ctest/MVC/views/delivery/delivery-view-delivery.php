<?php
    use app\core\component\Component;
    use \app\core\form\Form;
    $component=new Component();
?>

<div class="detail">
    <h1>Delivery ID : <?= $delivery['delivery_ID']?></h1>
    <h3>Name : <?= $delivery['name'] ?></h3>
    <h3>Contact Number : <?= $delivery['contact'] ?></h3>
    <h3>Address : <?= $delivery['address'] ?></h3>
    <h3>Postal Code : <?= $delivery['postal_code'] ?></h3>
    <h3>City : <?= $delivery['city'] ?></h3>

    <?php if ( $delivery['comment'] != NULL ) :  ?>
        <h3 class='warning-comment'>Comments : *<?= $delivery['comment'] ?></h3>
    <?php endif; ?>
</div>
<hr>
<br>

<input type="checkbox" id="payment_status" name="payment_status" value="payment_status">
<label for="payment_status"> Payment Successful</label><br>

<?php
$form=Form::begin("/ctest/delivery-complete?id=".$delivery['delivery_ID']."&pin=5948",'post');?> 
<section class="form-body" style="padding-bottom:100px">


    <div class="main_title">
    <!-- <h2 class="fs-150 fc-color--dark">Enter PIN here</h2> -->
       
    </div>
    <div class="form-body-fields">
    <table>
        <!-- form for chcheckbox -->
    <?php echo $form->spanfield($model,'confirmation_PIN','Enter PIN here','field','text') ?>
    </table>
    <div><?php echo $component->button("complete","submit","Complete Order","button--class-0","complete")?></div>
    
    </div>
   
    
    <?php Form::end() ?>  

