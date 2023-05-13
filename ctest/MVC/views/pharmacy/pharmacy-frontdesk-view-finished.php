<?php
    use app\core\component\Component;
    $component=new Component();
    $total = 0;
    use app\core\Time;
    $timeModel = new Time();
    // var_dump($online_orders);
    // var_dump($sf_orders);
    // var_dump($orders);
    // var_dump($orders);
    // var_dump($orders[0]['order_ID]);
    // exit;

    // var_dump($order_details);    //done
    // var_dump($online_orders);    //done
    // var_dump($sf_orders);
    // var_dump($sf_pres_med);
    // var_dump($ep_orders);
    // var_dump($ep_pres_med);
    // echo 'front finished';
    // exit;
?>

<div class="detail-front flex-del">
    <table>
    <tr><td>Order ID :</td><td> <div class="order_idw "><?=$order_details['order_ID']?></div></td></tr>
    <tr><td>Patient Name : </td><td><?=$order_details['name']?></td></tr>
    <tr><td>Contact Number : </td><td><?=$order_details['contact']?></td></tr>
    <tr><td>Date : </td><td><?=$order_details['date']?></td></tr>
    <tr><td>Time : </td><td><?=$order_details['time']?></td></tr>
    <tr><td>Doctor : </td><td><?=$order_details['doctor']?></td></tr>
    </table>
</div>


<div class="table-container">
    <table border="0">
        <tr>
            <th>Medicine ID</th><th>Medicine Name</th><th>Medicine Strength</th><th>Price per unit</th><th>Amount</th><th>Total Price</th>
        </tr>
        <?php foreach($order_medicines as $key=>$order): ?>
            <?php if( $order['status']=='include' ): ?>
                <tr class="table-row unselectable">
                <td><?=$order['med_ID']?></td>
                <td><?=$order['name']?></td> 
                <td><?=$order['strength']?></td> 
                <td><?= 'LKR. '. number_format($order['current_price'],2,'.','') ?></td> 
                <td><?=$order['order_amount']?></td> 
                <td><?= 'LKR. '. number_format($order['current_price']*$order['order_amount'],2,'.','') ?></td> 
                <?php $total = $total + $order['current_price']*$order['order_amount'] ?>
            <?php else: ?>
                <tr class="table-row-faded unselectable">
                <td><?=$order['med_ID']?></td>
                <td><?=$order['name']?></td> 
                <td><?=$order['strength']?></td> 
                <td><?= 'LKR. '. number_format($order['current_price'],2,'.','') ?></td> 
                <td style="color:red;"><?= "Out of Stock" ?></td> 
                <td style="color:red;">
                    <?php 
                        if ( (int)$order['available_amount']==0 ){
                            echo 'No items available';
                        } elseif ( (int)$order['available_amount']==1 ){
                            echo '1 item available';
                        } else {
                            echo $order['available_amount'].' items available'; 
                        }
                    ?>
                </td> 
                <?php $NA_count = $NA_count + 1 ?>
            <?php endif; ?>
        <?php endforeach; ?>
        </tr>
    </table>
</div>
<h1 style="text-align: right;">Total Price : <?= 'LKR. '. number_format($total,2,'.','') ?></h1>
