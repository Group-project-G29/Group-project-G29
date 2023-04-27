<?php
    use app\core\component\Component;
    $component=new Component();
    $total = 0;
    $NA_count =0;
    // var_dump($order_type);
    //     exit;

?>

<div class="detail">
    <h3>Patient Name : <?=$orders[0]['p_name']?></h3>
    <h3>Contact Number : <?=$orders[0]['contact']?></h3>
    <h3>Address : <?=$orders[0]['address']?></h3>
    <h3>Age : <?=$orders[0]['age']?></h3>
    <h3>Gender : <?=$orders[0]['gender']?></h3>
    <hr>
    <h3>Order ID : <?=$orders[0]['order_ID']?></h3>
    <h3>Ordered Date & Time :<?=$orders[0]['created_date']?> <?=$orders[0]['created_time']?></h3>
    <h3>Pickup Status : <?=$orders[0]['pickup_status']?></h3>
</div>


<div class="table-container">
    <table border="0">
        <tr>
            <th>Medicine ID</th><th>Medicine Name</th><th>Medicine Strength</th><th>Price per unit</th><th>Amount</th><th>Total Price</th>
        </tr>
        <?php foreach($orders as $key=>$order): ?>
            <?php if( (int)$order['order_amount'] < (int)$order['available_amount'] ): ?>
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
                <td><?= "Out of Stock" ?></td> 
                <td></td> 
                <?php $NA_count = $NA_count + 1 ?>
            <?php endif; ?>
        <?php endforeach; ?>
        </tr>
    </table>
</div>
    <h1>Total Price : <?=$total?></h1>

<div class='upper-container'>
    <?php echo $component->button('cancle-process','','Cancle Process','button--class-3  width-10','cancle-process');?>

    <?php if( $order_type=='Softcopy-prescription' ): ?>
    <?php echo $component->button('add-more-medicine','','Add More Medicine','button--class-0  width-10','add-more-medicine');?>
    <?php endif; ?>

    <?php if( $NA_count>0 ): ?>
    <?php echo $component->button('notify-availability','','Send Notification','button--class-0  width-10','notify-availability');?>
    <?php endif; ?>

    <?php echo $component->button('finish-process','','Finish Process','button--class-0  width-10','finish-process');?>
</div>
<!-- <div class="popup" id="popup">
        <h2>Successful !!</h2>
        <p> Notification has been sent.. </p>
        <button type="button" onclick="closePopup()" id="ok">OK</button>
</div> -->



<!-- ==================== -->
<script>

    
    const btn1=document.getElementById("cancle-process");
    btn1.addEventListener('click',function(){
        location.href="pharmacy-cancle-processing-order?id="+<?=$orders[0]['order_ID']?>; //get
    })
    
    const btn2=document.getElementById("finish-process");
    btn2.addEventListener('click',function(){
        location.href="pharmacy-finish-processing-order?id="+<?=$orders[0]['order_ID']?>; //get
    })
    
    const btn3=document.getElementById("notify-availability");
    // btn3.onclick=openPopup();
    btn3.addEventListener('click',function(){
        // openPopup();
        location.href="pharmacy-notify-processing-order?id="+<?=$orders[0]['order_ID']?>; //get
    })
    // let popup =document.getElementById("popup");
    // function openPopup(){
    //     popup.classList.add("open-popup");
    // }
    // function closePopup(){
    //     popup.classList.remove("open-popup");
    // }

    // const btn5=document.getElementById("ok");
    // // btn3.onclick=openPopup();
    // btn5.addEventListener('click',function(){
    //     // openPopup();
    //     location.href="pharmacy-notify-processing-order?id="+<?=$orders[0]['order_ID']?>; //get
    // })

    const btn4=document.getElementById("add-more-medicine");
    btn4.addEventListener('click',function(){
        location.href="pharmacy-add-medicine-processing-order?id="+<?=$orders[0]['order_ID']?>; //get
    })


</script>