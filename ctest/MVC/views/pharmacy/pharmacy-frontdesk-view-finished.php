<?php
    use app\core\component\Component;
    $component=new Component();
    $total = 0;
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

<div class="detail">
    <h3>Order ID : <?=$order_details['order_ID']?></h3>
    <h3>Patient Name : <?=$order_details['name']?></h3>
    <h3>Contact Number : <?=$order_details['contact']?></h3>
    <h3>Date : <?=$order_details['date']?></h3>
    <h3>Time : <?=$order_details['time']?></h3>
    <h3>Doctor : <?=$order_details['doctor']?></h3>
</div>


<div class="table-container">
    <table border="0">
        <tr>
            <th>Medicine ID</th><th>Medicine Name</th><th>Medicine Strength</th><th>Price per unit</th><th>Amount</th><th>Total Price</th>
        </tr>
        <?php foreach($order_medicines as $key=>$order): ?>
            <?php if( $order['status']=='include' ): ?>
                <tr class="table-row">
                <td><?=$order['med_ID']?></td>
                <td><?=$order['name']?></td> 
                <td><?=$order['strength']?></td> 
                <td><?=$order['current_price']?></td> 
                <td><?=$order['order_amount']?></td> 
                <td><?=$order['current_price']*$order['order_amount']?></td> 
                <?php $total = $total + $order['current_price']*$order['order_amount'] ?>
            <?php else: ?>
                <tr class="table-row-faded">
                <td><?=$order['med_ID']?></td>
                <td><?=$order['name']?></td> 
                <td><?=$order['strength']?></td> 
                <td><?=$order['current_price']?></td> 
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
<h1 style="text-align: right;">Total Price : <?=$total?></h1>

<!-- <div class='upper-container'>
    <?php echo $component->button('take-order','','Process','button--class-0  width-10','take-order');?>
</div> -->


<!-- ==================== -->
<script>
    const btn1=document.getElementById("take-order");
    btn1.addEventListener('click',function(){
        location.href="pharmacy-take-pending-order?id="+<?=$order_details[0]['order_ID']?>; //get
    })
</script>