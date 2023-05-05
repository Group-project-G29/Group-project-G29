<?php

use app\core\Application;
use app\core\component\Component;
use app\core\form\Form;
use app\models\Medicine;
use app\models\Payment;

    $paymentModel=new Payment();
    $component=new Component();
    $form=new Form();
    $medicineModel=new Medicine();
    $total=0;
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
            <h3><?="Total Price :LKR ".$total?></h3>
        </div>
    </div>
    <div class="prescription-table">
        <h2>Prescription</h2>
            <?php foreach($prescriptions as $prescription): ?>
                <a href=<?="'"."handle-documentation?spec=prescription&mod=view&id=".$prescription['prescription_ID']."'"?>><?="Prescription-".$prescription['prescription_ID']?></a>
            <?php endforeach;?>
            <?php if(!$prescriptions): ?>
                <div class="no-item">
                    <h4>No Prescription Added</h4>
                </div>
            <?php endif; ?>
        
        </div>
        <div>
           <div class="payways">
            <img src="media/images/common/payways.png">
        </div> 
        </div>
    

</section>
<?php echo $paymentModel->payNow($amount,'Medicine Order-'.Application::$app->session->get('user'),Application::$app->session->get('userObject'),'',$hash,'patient-dashboard?spec=payment&cmd=done',''); ?>

<script>
   
//     const btn2=document.getElementById('btn-2'); 
//    //to payment gateway
//     btn2.addEventListener('click',()=>{
//         location.href="patient-payment?spec=medicine-order&cmd=add&type=delivery"
//     })
</script>
