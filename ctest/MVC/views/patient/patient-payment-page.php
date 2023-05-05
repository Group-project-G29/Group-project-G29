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
<?php if($lacked): ?>
    <div class="p">
<div style="margin-left:-20vw;margin-top:-3vh;width:110vw;"class="background">

</div>
<div class="lacked-popup">
    <div>
        <h3>Following items that you ordered cannot be provided by us.<br> Sorry for the inconvenience.</h3>
    </div>
    <table>
        <tr>
            <th>Item name</th><th>Reason</th>
        </tr>
        <?php foreach($lacked as $item): ?>
            <tr>
                <td align='center'><?=$item ?></td><td align='center'>Out of Stock</td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="lacked-bu-container">
        <?= $component->button('btn1','','Order Now','acpt-btn button--class-0');?>
        <?= $component->button('btn2','','Go Back','rjct-btn button--class-0');?>
    </div>

</div>
</div>
<?php endif; ?>
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
            <h3><?="Medicine Price :LKR ".$total.".00"?></h3>
            <?php $grand_total=$grand_total+$total ?>
        </div>
    </div>
    <div class="prescription-table">
        <div style="display:flex; flex-direction:column; align-items:center;">
            <div class="flex pre">
                <h2>Prescriptions</h2>
                <img  id="why" src="./media/images/common/info.png">
            </div>
            <div class="why-body">
                Total Price of Softcopy Prescriptions are calculated after the processing of order
            </div>
        </div>
            <table>
                <tr><th>Prescription</th><th>Price</th></tr>
                <?php foreach($prescriptions as $prescription): ?>
                    <tr>
                        <td>
                            <a href=<?="'"."handle-documentation?spec=prescription&mod=view&id=".$prescription['prescription_ID']."'"?>><?=(($prescription['type']=='E-prescription')?"E-Prescription-":"SoftCopy Prescription-").$prescription['prescription_ID']?></a>
                        </td>
                        <td>
                            <?php if($prescription['type']=='E-prescription'): ?>
                                <?=($prescriptionModel->getPrice($prescription['prescription_ID'])==''?'':'LKR'.$prescriptionModel->getPrice($prescription['prescription_ID']).".00") ?>
                                <?php $grand_total=$grand_total+(($prescriptionModel->getPrice($prescription['prescription_ID'])=='')?0:$prescriptionModel->getPrice($prescription['prescription_ID']))?>
                            <?php else: ?>
                                <?php $val=$prescriptionModel->getPatientPrescriptionPrice($prescription['prescription_ID']);?>
                                <?php if($val): ?>
                                    <?="LKR ".$val?>
                                <?php else:?>
                                    <div class="image-pres">
                                        <div class="h-container">
                                            <img class="h-glass" src="./media/anim_icons/pending.gif">
                                        </div>
                                        Price will be shown after order is processed
                                    </div>
                                <?php endif;?>
                            <?php endif;?>
                        </td>
                    </tr>
                <?php endforeach;?>
            </table>
            <?php if(!$prescriptions): ?>
                <div class="no-item">
                    <h4>No Prescription Added</h4>
                </div>
            <?php endif; ?>
        
        </div>
        <div>
            <h3><?="Current Total Price<font size='3'>(without pending prescription price)</font> :LKR ".$grand_total.".00"?></h3>
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
    const accept=document.querySelector('.acpt-btn');
    const reject=document.querySelector('.rjct-btn');
    const p=document.querySelector('.p');
    if(accept && reject){
        reject.addEventListener('click',()=>{
            location.href="patient-dashboard?spec=orders";
        })
        accept.addEventListener('click',()=>{
           p.classList.add('hide'); 

    
        })

    }
    const why=document.getElementById('why');
    const whybody=document.querySelector('.why-body');
    $toggle=0;
    whybody.style.display='none'
    why.addEventListener('click',()=>{
        if($toggle==1) {
            whybody.style.display='block';
            $toggle=0;
        }
        else{
            whybody.style.display='none';
            $toggle=1;
        }
    });
   
</script>
