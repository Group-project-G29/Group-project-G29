<?php
    use app\core\component\Component;
    use \app\core\form\Form;
    $component=new Component();
    $total = 0;
    $NA_count =0;
    use app\core\Time;
    $timeModel = new Time();
    // var_dump($order_details);   
    // var_dump($order_medicines);   
    // exit;
?>

<div class="detail-front flex-del">
    <table>
    <tr><td>Order ID :</td><td> <div class="order_idw "><?=$order_details[0]['order_ID']?></div></td></tr>
    <tr><td>Date : </td><td><?=$order_details[0]['created_date']?></td></tr>
    <tr><td>Time : </td><td><?= $timeModel->time_format($order_details[0]['created_time']) ?></td></tr>
    <tr><td>Pickup Status : </td><td><?= ucfirst($order_details[0]['pickup_status']) ?></td></tr>
    <tr><td>Patient Name : </td><td><?=$order_details[0]['name']?></td></tr>
    <tr><td>Contact Number : </td><td><?=$order_details[0]['contact']?></td></tr>
    <tr><td>Address : </td><td><?=$order_details[0]['address']?></td></tr>
    <tr><td>Payment Status : </td><td>
        <?php if($order_details[0]['payment_status']=='pending'): ?>
            <img src="./media/anim_icons/pending.gif">
        <?php else: ?>
            <img src="./media/anim_icons/animcompleted.gif">
        <?php endif; ?> </td></tr>
    <?php if($order_details[0]['text']!=NULL): ?>
        <tr><td class="orders-pending-note">Note* :</td><td><?=$order_details[0]['text']?></td></tr>
    <?php endif; ?>
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

<div class='upper-container push-left' >
    <?php echo $component->button('pickup','','Pick Up','button--class-0  width-10','pickup');?>
</div>

<script>
    
    const btn1=document.getElementById("cancle-process");
    btn1.addEventListener('click',function(){
        location.href="pharmacy-frontdesk-cancle-order?id="+<?=$order_details['order_ID']?>; 
    })
    
    const btn2=document.getElementById("pickup");
    btn2.addEventListener('click',function(){
        location.href="pharmacy-frontdesk-pickup-order?id="+<?=$order_details['order_ID']?>+'&total='+<?=$total?>; 
    })

</script>