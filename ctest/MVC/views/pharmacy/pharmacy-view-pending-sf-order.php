<?php
    /** @var $model \app\models\User */
    use app\core\component\Component;
    use \app\core\form\Form;
    $component=new Component();
    $total = 0;
    $NA_count = 0;
    // var_dump($orders);
    // var_dump($curr_pres_orders);
    // echo 'hi';
    // exit;
    ?>

<div class="detail">
    <h3>Patient Name : <?=$orders[0]['name']?></h3>
    <h3>Contact Number : <?=$orders[0]['contact']?></h3>
    <h3>Address : <?=$orders[0]['address']?></h3>
    <h3>Age : <?=$orders[0]['age']?></h3>
    <h3>Gender : <?=$orders[0]['gender']?></h3>
    <hr>
    <h3>Order ID : <?=$orders[0]['order_ID']?></h3>
    <h3>Ordered Date & Time :<?=$orders[0]['created_date']?> <?=$orders[0]['created_time']?></h3>
    <h3>Pickup Status : <?=$orders[0]['pickup_status']?></h3>
</div>




<section class="form-body" style="padding-bottom:100px">
 
<div class="form-body-fields">
    
<!-- component and call when click the add button -->
<div class="new-order-add-item"> 
    <div class="main_title">
        <h2 class="fs-150 fc-color--dark">Enter the Medicines</h2>
    </div>
    <?php
    $component=new Component();
    $form=Form::begin('pharmacy-new-order-items?cmd=nr&presid='.$orders[0]["prescription_ID"],'post');
    ?> 
        <div class="new-order-add-item-col">
            <?php echo $form->editableselect('medicine','Medicine Name','field', ['pandol'=>'panadol', 'vitamin-C'=>50]) ?>
        </div>
        <div class="new-order-add-item-col">
            <?php echo $form->editableselect('amount','Amount','field',[]) ?>
        </div>
        <div class="new-order-add-item-col">
            <?php echo $component->button("add-another","submit","Add Another","button--class-00","add-another")?>
            <!-- <a class="add-more" >+ Add More Item</a> -->
        </div>
    </div>
    
    <!-- <div><?php echo $component->button("add-order","submit","Confirm Medicines","button--class-0","add-order")?></div> -->
    
</div>
<?php Form::end() ?>   

<?php if (isset($curr_pres_orders)): ?>
    <div class="table-container">
        <table border="0">
            <tr>
                <th>Medicine ID</th><th>Medicine Name</th><th>Medicine Strength</th><th>Price per unit</th><th>Amount</th><th>Total Price</th>
            </tr>
            <?php foreach($curr_pres_orders as $key=>$curr_pres_order): ?>
                <?php if( (int)$curr_pres_order['order_amount'] < (int)$curr_pres_order['available_amount'] ): ?>
                    <tr class="table-row">
                        <td><?=$curr_pres_order['med_ID']?></td>
                        <td><?=$curr_pres_order['name']?></td> 
                        <td><?=$curr_pres_order['strength']?></td> 
                        <td><?=$curr_pres_order['current_price']?></td> 
                        <td><?=$curr_pres_order['order_amount']?></td> 
                        <td><?=$curr_pres_order['current_price']*$curr_pres_order['order_amount']?></td> 
                        <?php $total = $total + $curr_pres_order['current_price']*$curr_pres_order['order_amount'] ?>
                    </tr>
                <?php else: ?>
                    <tr class="table-row-faded">
                        <td><?=$curr_pres_order['med_ID']?></td>
                        <td><?=$curr_pres_order['name']?></td> 
                        <td><?=$curr_pres_order['strength']?></td> 
                        <td><?=$curr_pres_order['current_price']?></td> 
                        <td><?=$curr_pres_order['order_amount']?></td> 
                        <td><?=$curr_pres_order['current_price']*$curr_pres_order['order_amount']?></td> 
                        <?php $NA_count = $NA_count + 1 ?>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    </div>
<?php endif; ?>
<h1>Total Price : <?=$total?></h1>

<div class='upper-container'>
    <?php echo $component->button('cancle-process','','Cancle Process','button--class-3  width-10','cancle-process');?>

    <?php if( $NA_count>0 ): ?>
    <?php echo $component->button('notify-availability','','Send Notification','button--class-0  width-10','notify-availability');?>
    <?php endif; ?>

    <?php echo $component->button('finish-process','','Finish Process','button--class-0  width-10','finish-process');?>
</div>

<!-- =========================IF AVAILABLE================================ -->
<div class="table-container">
            <img src="https://th.bing.com/th/id/OIP.JJIj5kC8a8c6vvYoxEBuUwHaH0?pid=ImgDet&rs=1" >
            <!-- <img src="<?=$orders[0]["location"]?>" alt="Prescription here. This is an image from web."> -->
            <!-- <?php echo($orders[0]["location"]) ?><br> -->

            <?php
                // echo '<img src="' . $orders[0]["location"] . '"alt="Prescription here. This is an image from web."/>';
                ?>
        </div>
<!-- ===================================================================== -->
    
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
    btn3.addEventListener('click',function(){
        location.href="pharmacy-notify-processing-order?id="+<?=$orders[0]['order_ID']?>; //get
    })
    
</script>