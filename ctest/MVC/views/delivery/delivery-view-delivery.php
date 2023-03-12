<?php
    use app\core\component\Component;
    use \app\core\form\Form;
    $component=new Component();
// var_dump($delivery);
// exit;

?>

<div class="detail">
    <h1>Delivery ID : <?= $delivery['delivery_ID']?></h1>
    <h3>Contact Number : <?= $delivery['contact'] ?></h3>
    <h3>Address : <?= $delivery['address'] ?></h3>
    <h3>Postal Code : <?= $delivery['postal_code'] ?></h3>
</div>
<hr>
<br>

<?php
$form=Form::begin('','post');?> 

<section class="form-body" style="padding-bottom:100px">

    <div class="main_title">
        <h2 class="fs-150 fc-color--dark">Enter PIN here</h2>
       
    </div>
    <div class="form-body-fields">
    <table>
    <?php echo $form->spanfield($model,'PIN','','field','text') ?>
    </table>
    <div><?php echo $component->button("complete","submit","Complete Order","button--class-0","complete")?></div>
    
    </div>
   
    
    <?php Form::end() ?>  


<!-- form to get the pin
button to confirm -->

<script>

    const btn1=document.getElementById("complete");
    btn1.addEventListener('click',function(){
        location.href="delivery-complete?id="+<?=$delivery['order_ID']?>; //get
    })
</script>