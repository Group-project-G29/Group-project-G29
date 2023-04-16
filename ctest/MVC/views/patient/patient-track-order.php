<?php

use app\core\Application;
use app\core\component\Component;

    $component=new Component();

?>
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
<?php if($order['pickup_status']=='delivery'):?>
    <section>
        <div>
            <h3>Track Your Orders</h2>
            <h3>Share PIN with Delivery Rider on Completion of Delivery</h3>
            <div>
                <h3><?=$order['PIN'] ?></h3>
            </div>
        </div>
        <div>
            <table border="0">
                <tr><td><h3>Recipient Name</h2></td><td><h4>: <?=$order['name']?></h4></td></tr>
                <tr><td><h3>Address</h2></td><td><h4>: <?=$order['address'] ?></h4></td></tr>
                <tr><td><h3>Time</h2></td><td><h4>: <?=explode(" ",$order['time_of_creation'])[0]?></h4></td></tr>
                <tr><td><h3>Date</h2></td><td><h4>: <?=explode(" ",$order['time_of_creation'])[1]?></h4></td></tr>
                <tr><td><h3>Payment</h2></td><td><h4>: <?=$order['payment_status']?></h4></td></tr>
            
            </table>
        </div>
      
        <div>
            <h3>Order Status</h2>
            
        </div>
        <div>
            <div>
                <img src="media/images/patient/pending.png" class=<?="'".$pending."'"?>>
                <h3>Order Pending</h2>
            </div>
            <div>
                <img src="media/images/patient/process.png" class=<?="'".$process."'"?>>
                <h3>Order Processing</h2>
            </div>
            <div>
                <img src="media/images/patient/delivering.png" class=<?="'".$delivering."'"?>>
                <h3>Out for delivery</h2>
            </div>
            <div>
                <img src="media/images/patient/packed.png" class=<?="'".$completed."'"?>>
                <h3>Delivered</h2>
            </div>
        </div>
    </section>
<?php else:?>
    <section class="track-order-section">
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
        <div class="track-order-status">
            <div>
                <h3>Order Status</h2>
                
            </div>
            <div class="status-box">
                <img src="media/images/patient/pending.png" class=<?="'".$pending."'"?>>
                <h3>Order Pending</h2>
            </div>
            <div class="status-box">
                <img src="media/images/patient/process.png" class=<?="'".$process."'"?>>
                <h3>Order Processing</h2>
            </div>
            <div class="status-box">
                <img src="media/images/patient/delivering.png" class=<?="'".$delivering."'"?>>
                <h3>Packed</h2>
            </div>
            <div class="status-box">
                <img src="media/images/patient/packed.png" class=<?="'".$completed."'"?>>
                <h3>Picked Up</h2>
            </div>
        </div>
    </section>   
<?php endif; ?>
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