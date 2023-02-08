<?php

use app\core\component\Component;

    $component=new Component();

?>
<section>
    <div>
        <h2>Track Your Orders</h2>
        <h3>Share PIN with Delivery Rider on Completion of Delivery</h3>
    </div>
    <div>
        <h1><?=$order['PIN']?></h1>
    </div>
    <div>
        <table border="0">
            <tr><td><h4>Recipient Name</h4></td><td><h4>: <?=$order['name']?></h4></td></tr>
            <tr><td><h4>Address</h4></td><td><h4>: <?=$order['address'] ?></h4></td></tr>
            <tr><td><h4>Time</h4></td><td><h4>: <?=explode(" ",$order['time_of_creation'])[0]?></h4></td></tr>
            <tr><td><h4>Date</h4></td><td><h4>: <?=explode(" ",$order['time_of_creation'])[1]?></h4></td></tr>
            <tr><td><h4>Payment</h4></td><td><h4>: <?=$order['payment_status']?></h4></td></tr>
           
        </table>
        <?=$component->button('btn','','View Orders','button-class--0','') ?>
    </div>
    <div>
        <h2>Order Status</h2>
    </div>
    <div>
        <div>
            <img src="">
            <h4>Order Pending</h4>
        </div>
         <div>
            <img src="">
            <h4>Order Processing</h4>
        </div>
         <div>
            <img src="">
            <h4>Out for delivery</h4>
        </div>
         <div>
            <img src="">
            <h4>Delivered</h4>
        </div>
    </div>
</section>