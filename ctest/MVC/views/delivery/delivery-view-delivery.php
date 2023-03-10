<?php
    use app\core\component\Component;
    use \app\core\form\Form;
    $component=new Component();
    // var_dump($delivery);
    // var_dump($err);
    // exit;
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


<!-- pass the pin to url - $POST[pin] -->
<?php
$form=Form::begin("/ctest/delivery-complete?id=".$delivery['delivery_ID'],'post');?> 
<section class="form-body" style="padding-bottom:100px">
    
    <?php if ( $delivery['payment_status'] === 'pending' ) :  ?>
        <div>
            <input type="checkbox" id="payment_status" name="payment_status" value="payment_done_now">
            <label for="payment_status"> Payment Successful</label><br>
            <?php
                if (isset($err)){
                    if($err === "pending_payment"){
                        echo '<p class="err-msg"><em><b>*Payment has to be done</b></em></p>';
                    }
                }
            ?>
        </div>
    <?php elseif ( $delivery['payment_status'] === 'done' ) : ?>
        <p>payment successful</p>
    <?php endif; ?>

    <div class="main_title">
    <!-- <h2 class="fs-150 fc-color--dark">Enter PIN here</h2> -->
       
    </div>
    <div class="form-body-fields">

    <table>
        <?php echo $form->spanfield($model,'confirmation_PIN','Enter PIN here','field','text') ?>
        <?php 
            if (isset($err)){
                if($err === "empty_pin"){
                    echo '<p class="err-msg"><em><b>*Enter the PIN provided by the customer</b></em></p>';
                }
                if($err === "incorrect_pin"){
                    echo '<p class="err-msg"><em><b>*PIN you entered is incorrect</b></em></p>';
                }
            }
        ?> 
    </table>

    <div><?php echo $component->button("complete","submit","Complete Order","button--class-0","complete")?></div><br>
    <!-- have comented above line -->
    </div>
   
    
    <?php Form::end() ?>  

