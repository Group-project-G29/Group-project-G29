
<?php
use app\core\form\Form;
use app\models\Medicine;

use app\core\Application;
use app\core\component\Component;

    $component=new Component();

?>
<?php if($order): ?>
<?php if($lacked): ?>
<div class="background">

</div>
<div class="lacked-popup">
    <div>
        <h3>Following items that you ordered cannot be provided by us. Sorry for the inconvenience</h3>
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
    <div>
        <?= $component->button('btn1','','Accept Order','acpt-btn',$order['order_ID']);?>
        <?= $component->button('btn2','','Reject Order','rjct-btn',$order['order_ID']);?>
    </div>

</div>
<?php endif; ?>
  <?php 
            $pending='';
            $process='';
            $delivering='';
            $completed='';
            if($order['processing_status']=='pending'){
                $pending='highlight-status';
            }
            elseif($order['processing_status']=='processing'){
                $process='highlight-status';
            }
            elseif($order['processing_status']=='packed'){
                $delivering='highlight-status';
            }
            elseif($order['processing_status']=='pickedup'){
                $delivering='highlight-status';
            }
            elseif($order['processing_status']=='waiting'){
                $pending='highlight-status';
            }
            elseif($order['processing_status']=='accepted'){
                $pending='highlight-status';
            }
            
                ?>
<section class="flex-container">
    <section class="order-main">
        <?php if($order['pickup_status']=='delivery'):?>
        <section>
        <section class="pharmacy-order-container--3">
            <div>
                <h4>Track Your Orders</h4>
                <h5>Share PIN with Delivery Rider on Completion of Delivery</h5>
                <div>
                    <h3><?=$order['PIN'] ?></h3>
                </div>
            </div>
            <div class="recepient-information">
                <table border="0">
                    <tr><td><h4>Recipient Name</h4></td><td><h4>: <?=$order['name']?></h4></td></tr>
                    <tr><td><h4>Address</h4></td><td><h4>: <?=$order['address'] ?></h4></td></tr>
                    <tr><td><h4>Time</h4></td><td><h4>: <?=explode(" ",$order['time_of_creation'])[0]?></h4></td></tr>
                    <tr><td><h4>Date</h4></td><td><h4>: <?=explode(" ",$order['time_of_creation'])[1]?></h4></td></tr>
                    <tr><td><h4>Payment</h4></td><td><h4>: <?=$order['payment_status']?></h4></td></tr>
                
                </table>
            </div>
        
            <div>
                <h3>Order Status</h3>
                
            </div>
            <div>
                <div>
                    <img src="media/images/patient/pending.png" class=<?="'".$pending."'"?>>
                    <h4>Order Pending</h4>
                </div>
                <div>
                    <img src="media/images/patient/process.png" class=<?="'".$process."'"?>>
                    <h4>Order Processing</h4>
                </div>
                <div>
                    <img src="media/images/patient/delivering.png" class=<?="'".$delivering."'"?>>
                    <h4>Out for delivery</h4>
                </div>
                <div>
                    <img src="media/images/patient/packed.png" class=<?="'".$completed."'"?>>
                    <h4>Delivered</h4>
                </div>
            </div>
        </section>
        <?php else:?>
        <section class="pharmacy-order-container--3">
            <div>
                <div class="track-order">
                    <h4>Track Your Orders</h4>
                </div>
                <div>
                    <table border="0">
                        <tr><td><h3>Recipient Name</h3></td><td><h3>: <?=(Application::$app->session->get('userObject')->name)?></h3></td></tr>
                        <tr><td><h3>Date</h3></td><td><h3>: <?=explode(" ",$order['created_date'])[0]?></h3></td></tr>
                        <tr><td><h3>Time</h3></td><td><h3>: <?=explode(" ",$order['created_time'])[0]?></h3></td></tr>
                        <tr><td><h3>Payment</h3></td><td><h3>: <?=$order['payment_status']?></h3></td></tr>
                    
                    </table>
                </div>
            
            </div>
            <div>
                <h3>Order Status</h3>
                
            </div>
            <div class="track-order-status">
                <div class="status-box">
                    <img src="media/images/patient/pending.png" class=<?="'".$pending."'"?>>
                    <h4>Order Pending</h4>
                </div>
                <div class="status-box">
                    <img src="media/images/patient/process.png" class=<?="'".$process."'"?>>
                    <h4>Order Processing</h4>
                </div>
                <div class="status-box">
                    <img src="media/images/patient/delivering.png" class=<?="'".$delivering."'"?>>
                    <h4>Packed</h4>
                </div>
                <div class="status-box">
                    <img src="media/images/patient/packed.png" class=<?="'".$completed."'"?>>
                    <h4>Picked Up</h4>
                </div>
            </div>
        </section>   
    </section>
    <?php endif; ?>

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
            
        <?php $form->end(); ?>


    </section>

</section>
<script>
    const accept=document.querySelector('.acpt-btn');
    const reject=document.querySelector('.rjct-btn');
    reject.addEventListener('click',()=>{
        location.href="patient-dashboard?spec=orders&cmd=reject&id="+reject.id;
    })
    accept.addEventListener('click',()=>{
        location.href="patient-dashboard?spec=orders&cmd=accept&id="+accept.id;

    })

</script>
<?php else:?>
    <h3>No orders</h3>
<?php endif;?>
