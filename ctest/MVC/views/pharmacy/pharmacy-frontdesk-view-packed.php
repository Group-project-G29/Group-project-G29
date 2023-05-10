<?php
    use app\core\component\Component;
    use \app\core\form\Form;
    $component=new Component();
    $total = 0;
    $NA_count =0;
    // var_dump($order_details);   
    // var_dump($order_medicines);   
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
<h1 style="text-align: right;">Total Price : <?= 'LKR. '. number_format($total,2,'.','') ?></h1>

<div class='upper-container'>
    <?php echo $component->button('cancle-process','','Cancle Process','button--class-3  width-10','cancle-process');?>
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