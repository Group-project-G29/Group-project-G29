<?php

use app\core\Application;
use app\models\Cart;
use app\core\component\Component;
use app\core\form\Form;
use app\models\Medicine;
use app\models\Order;
use app\models\Payment;
use app\models\Prescription;

    $paymentModel=new Payment();
    $component=new Component();
    $form=new Form();
    $medicineModel=new Medicine();
    $cartModel=new Cart();
    $total=0;
    $grand_total=0;
    $orderModel=new Order();
    $prescriptionModel=new Prescription();
?>

<section class="pharmacy-order-container--2">
    <div class="medicine-payment">
        <h2>Medicines</h2>
        <table class="medicine-table">
            <tr><th>Medicine</th><th>Amount</th><th>Price</th></tr>
            <?php foreach($medicines as $medicine): ?>
                <?php $medicinei=$medicineModel->fetchAssocAll(['med_ID'=>$medicine['med_ID']])[0];?>
                <?php $total=$total+$medicine['unit_price']*$medicine['amount'] ?>
           <tr> <td><?=$medicinei['name']." ".$medicinei['unit']?></td><td><?=$medicine['amount']?></td><td><?=$medicine['unit_price']*$medicine['amount'] ?></td></tr>
            <?php endforeach;?>
        </table>
        <?php if(!$medicines): ?>
           <div class="no-item">
               <h4>No Medicines Added</h4>
           </div>
       <?php endif; ?>
        <div class="price-container">
            <h3><?="Medicine Price :LKR ".$total?></h3>
            <?php $grand_total=$grand_total+$total ?>
        </div>
    </div>
    <div class="prescription-table">
        <h2>Prescriptions</h2>
            <?php foreach($prescriptions as $prescription): ?>
                <div class="flex">
                    <a href=<?="'"."handle-documentation?spec=prescription&mod=view&id=".$prescription['prescription_ID']."'"?>><?="Prescription-".$prescription['prescription_ID']?></a>
                    <?=($prescriptionModel->getPrice($prescription['prescription_ID'])==''?'':'LKR'.$prescriptionModel->getPrice($prescription['prescription_ID'])) ?>
                    <?php $grand_total=$grand_total+(($prescriptionModel->getPrice($prescription['prescription_ID'])=='')?0:$prescriptionModel->getPrice($prescription['prescription_ID']))?>
                </div>
            <?php endforeach;?>
            <?php if(!$prescriptions): ?>
                <div class="no-item">
                    <h4>No Prescription Added</h4>
                </div>
            <?php endif; ?>
        
        </div>
        <div>
            <h2><?="Current Total Price :LKR ".$grand_total?></h2>
        </div>
        <?php $prescription1=$prescriptionModel->fetchAssocAll(['cart_ID'=>$cartModel->getPatientCart(Application::$app->session->get('user'))[0]['cart_ID'],'type'=>'softcopy prescription']) ?>
        <div>
            <?php if(!$prescription1):?>
                <?= $component->button('pay-btn','submit','Pay Now','button--class-0','btn-2');?>
                <?php endif;?>
                <?= $component->button('pay-on-btn','submit','Pay On Pickup','button--class-0','btn-1');?>
        </div>
        <div class="payways">
            <img src="media/images/common/payways.png">
        </div>

</section>

<script>
   
    const btn2=document.getElementById('btn-2'); 
   //to payment gateway
    if(btn2){
       btn2.addEventListener('click',()=>{
            location.href="patient-payment?spec=payment-gateway"
        })  
    }
   
    const btn1=document.getElementById('btn-1');
    if(btn1){
         btn1.addEventListener('click',()=>{
            location.href="patient-payment?spec=medicine-order&cmd=complete&type=payon";
        }) 
    }
  
</script>
