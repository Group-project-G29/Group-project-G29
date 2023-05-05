<?php
// echo the meta tag or add it in the head section of your HTML document
    echo "<meta http-equiv='refresh' content='5'>";
?>
<?php
use app\core\form\Form;
use app\models\Medicine;

use app\core\Application;
use app\core\component\Component;
use app\models\Prescription;

    $component=new Component();
    $prescriptionModel=new Prescription();
    $grand_total=0;

?>
<?php if($order): ?>

  <?php 
            $pending='';
            $process='';
            $delivering='';
            $completed='';
            if($order['processing_status']=='pending'){
                $pending='highlight-status';
            }
            elseif($order['processing_status']=='processing'){
                $pending='highlight-status';
                $process='highlight-status';
            }
            elseif($order['processing_status']=='packed'){
                $pending='highlight-status';
                $process='highlight-status';
                $delivering='highlight-status';
            }
            elseif($order['processing_status']=='pickedup'){
                $pending='highlight-status';
                $process='highlight-status';
                $delivering='highlight-status';
                $completed='highlight-status';
            }
            elseif($order['processing_status']=='waiting'){
                $pending='highlight-status';
            }
            elseif($order['processing_status']=='accepted'){
                $pending='highlight-status';
                $process='highlight-status';
            }
           
                ?>
<?php if($order['pickup_status']=='delivery'):?>
    <section class="order-main">
    <section class="pharmacy-order-container--3">
        <div>
            <h2>Track Your Orders</h2>
            <h3>Share PIN with Delivery Rider on Completion of Delivery</h3>
            <div class="blue-box">
                <center><h1><?=$order['PIN'] ?></h1></center>
            </div>
        </div>
        <div class="recepient-information">
            <table border="0">
                <tr><td>Recipient Name</td><td>: <?=$order['name']?></td></tr>
                <tr><td>Address</td><td>: <?=$order['address'] ?></td></tr>
                <tr><td>Time</td><td>: <?=substr($order['created_time'],0,5).(($order['created_time']>'12:00')?' PM':' AM')?></td></tr>
                <tr><td>Date</td><td>: <?=$order['created_date']?></td></tr>
                <tr><td>Payment</td><td>: <?=$order['payment_status']?></td></tr>
            
            </table>
        </div>
      
        <div>
            <h3>Order Status</h3>
            
        </div>
        <table border='0' cellspacing='0'>
        <tr class="track-order-status" >
            <td class=<?="'status-box ".$pending."'"?>>
                <?php if($order['processing_status']=='pending' || $order['processing_status']=='waiting'): ?>
                    <img src="media/anim_icons/animloading.gif" >
                <?php else:?>
                    <img src="media/images/patient/pending.png" >
                <?php endif;?>
                <h4>Order Pending</h4>
            </td>
            <td class=<?="'status-box ".$process."'"?>>
                <?php if($order['processing_status']=='processing' || $order['processing_status']=='accepted'): ?>
                        <img src="media/anim_icons/animprocessing.gif" >
                    <?php else:?>
                        <img src="media/images/patient/process.png">
                <?php endif;?>
                <h4>Order Processing</h4>
            </td>
            <td class=<?="'status-box ".$delivering."'"?>>
                <?php if($order['processing_status']=='delivering'): ?>
                    <img src="media/anim_icons/animdelivering.gif" >
                <?php else:?>
                    <img src="media/images/patient/delivering.png" >
                <?php endif;?>
                <h4>Out for delivery</h4>
            </td>
            <td class=<?="'status-box ".$completed."'"?>>
                <?php if($order['processing_status']=='pickedup'): ?>
                        <img src="media/anim_icons/animcompleted.gif" >
                    <?php else:?>
                        <img src="media/images/patient/packed.png" >
                <?php endif;?>
                <h4>Delivered</h4>
            </td>
        </tr>
    </table>
    </section>
    <section class="pharmacy-order-container--3 left-container">
    <?php


    $component=new Component();
    $form=new Form();
    $medicineModel=new Medicine();
    $form->begin('','POST');
    $total=0;
   
?>
    <div class="medicine-payment">
        <h2>Medicines</h2>
        <table class="medicine-table">
            <tr><th>Medicine</th><th>Amount</th><th>Price</th></tr>
            <?php foreach($medicines as $medicine): ?>
                <?php $medicinei=$medicineModel->fetchAssocAll(['med_ID'=>$medicine['med_ID']])[0];?>
                <?php $amount=$medicineModel->fetchAssocAllByName(['order_ID'=>$order['order_ID'],'med_ID'=>$medicine['med_ID']],'medicine_in_order')[0]['amount'] ;?>
                <?php $total=$total+$medicine['order_current_price']*$amount ?>
           <tr> <td><?=$medicinei['name']." ".$medicinei['unit']?></td><td><?=$amount?></td><td><?=$medicine['order_current_price']*$amount ?></td></tr>
        
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
    <div style="display:flex; flex-direction:column; align-items:center;">
            <div class="flex pre">
                <h2>Prescriptions</h2>
                <img  id="why" src="./media/images/common/info.png">
            </div>
            <div class="why-body">
                Total Price of Softcopy Prescriptions are calculated after the processing of order
            </div>
        </div>
             <div class="prescription-table">
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
        
    </div>
        
    </div>
        
    <?php $form->end(); ?>


</section>
</section>
<?php else:?>
    <section class="order-main">
    <section class="pharmacy-order-container--3">
        <div>
            <div class="track-order">
                <h4>Track Your Orders</h4>
            </div>
            <div>
                <table border="0">
                    <tr><td><h3>Recipient Name</h3></td><td><h3>: <?=(Application::$app->session->get('userObject')->name)?></h3></td></tr>
                    <tr><td>Time</td><td>: <?=substr($order['created_time'],0,5).(($order['created_time']>'12:00')?' PM':' AM')?></td></tr>
                    <tr><td>Date</td><td>: <?=$order['created_date']?></td></tr>
                    <tr><td><h3>Payment</h3></td><td><h3>: <?=$order['payment_status']?></h3></td></tr>
                
                </table>
            </div>
        
        </div>
        <div>
            <h3>Order Status</h3>
            
        </div>
        <table>
        <tr class="track-order-status">
            <td class=<?="'status-box ".$pending."'"?>>
                <?php if($order['processing_status']=='pending' || $order['processing_status']=='waiting'): ?>
                    <img src="media/anim_icons/animloading.gif" >
                <?php else:?>
                    <img src="media/images/patient/pending.png" >
                <?php endif;?>
                <h4>Order Pending</h4>
            </td>
            <td class=<?="'status-box ".$process."'"?>>
                <?php if($order['processing_status']=='processing' || $order['processing_status']=='accepted'): ?>
                        <img src="media/anim_icons/animprocessing.gif" >
                    <?php else:?>
                        <img src="media/images/patient/process.png">
                <?php endif;?>
                <h4>Order Processing</h4>
            </td>
            <td class=<?="'status-box ".$delivering."'"?>>
                <?php if($order['processing_status']=='packed'): ?>
                    <img src="media/anim_icons/animpacked.gif" >
                <?php else:?>
                    <img src="media/images/patient/packed.png" >
                <?php endif;?>
                <h4>Packed</h4>
            </td>
             <td class=<?="'status-box ".$completed."'"?>>
                <?php if($order['processing_status']=='pickedup'): ?>
                        <img src="media/anim_icons/animcompleted.gif" >
                    <?php else:?>
                        <img src="media/images/patient/packed.png" >
                <?php endif;?>
                <h4>Picked Up</h4>
            </td>
        </tr>
        </table>
    </section>   
</section>
<section class="pharmacy-order-container--3 left-container">
    <?php


    $component=new Component();
    $form=new Form();
    $medicineModel=new Medicine();
    $form->begin('','POST');
    $total=0;
   
?>
    <div class="medicine-payment">
        <h2>Medicines</h2>
        <table class="medicine-table">
            <tr><th>Medicine</th><th>Amount</th><th>Price</th></tr>
            <?php foreach($medicines as $medicine): ?>
                <?php $medicinei=$medicineModel->fetchAssocAll(['med_ID'=>$medicine['med_ID']])[0];?>
                <?php $amount=$medicineModel->fetchAssocAllByName(['order_ID'=>$order['order_ID'],'med_ID'=>$medicine['med_ID']],'medicine_in_order')[0]['amount'] ;?>
                <?php $total=$total+$medicine['order_current_price']*$amount ?>
           <tr> <td><?=$medicinei['name']." ".$medicinei['unit']?></td><td><?=$amount?></td><td><?=$medicine['order_current_price']*$amount ?></td></tr>
        
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
        
    </div>
        
    <?php $form->end(); ?>


</section>
<?php endif; ?>


<script>
    // const accept=document.querySelector('.acpt-btn');
    // const reject=document.querySelector('.rjct-btn');
    // reject.addEventListener('click',()=>{
    //     location.href="patient-dashboard?spec=orders&cmd=reject&id="+reject.id;
    // })
    // accept.addEventListener('click',()=>{
    //     location.href="patient-dashboard?spec=orders&cmd=accept&id="+accept.id;

    // })
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
<?php else:?>
    <h3>No orders</h3>
<?php endif;?>
