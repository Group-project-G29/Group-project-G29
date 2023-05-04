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
    // exit;
?>

<div class="detail">
    <h3>Patient Name : <?=$order_details[0]['name']?></h3>
    <h3>Contact Number : <?=$order_details[0]['contact']?></h3>
    <h3>Address : <?=$order_details[0]['address']?></h3>
    <hr>
    <h3>Order ID : <?=$order_details[0]['order_ID']?></h3>
    <h3>Ordered Date & Time :<?=$order_details[0]['created_date']?> <?=$order_details[0]['created_time']?></h3>
    <h3>Pickup Status : <?=$order_details[0]['pickup_status']?></h3>
</div>
   
<?php if( $online_orders !== NULL ): ?>
    <?php $total_online=0 ?>
    <hr>
    <h3>From cart :</h3>
    <div class="table-container">
        <table border="0">
            <tr>
                <th>Medicine ID</th><th>Medicine Name</th><th>Medicine Strength</th><th>Price per unit</th><th>Amount</th><th>Total Price</th>
            </tr>
            <?php foreach($online_orders as $key=>$order): ?>
                <?php if( (int)$order['order_amount'] < (int)$order['available_amount'] ): ?>
                    <tr class="table-row">
                    <td><?=$order['med_ID']?></td>
                    <td><?=$order['name']?></td> 
                    <td><?=$order['strength']?></td> 
                    <td><?=$order['current_price']?></td> 
                    <td><?=$order['order_amount']?></td> 
                    <td><?=$order['current_price']*$order['order_amount']?></td> 
                    <?php $total_online = $total_online + $order['current_price']*$order['order_amount'] ?>
                <?php else: ?>
                    <tr class="table-row-faded">
                    <td><?=$order['med_ID']?></td>
                    <td><?=$order['name']?></td> 
                    <td><?=$order['strength']?></td> 
                    <td><?=$order['current_price']?></td> 
                    <td><?= "Out of Stock" ?></td> 
                    <td></td> 
                <?php endif; ?>
            <?php endforeach; ?>
            </tr>
        </table>
    </div>
    <h3>Total Price For Online Ordered Products : <?=$total_online?></h3>
    <?php $total=$total+$total_online ?>
<?php endif; ?>


<?php if( $ep_orders !== NULL ):?>
    <?php foreach( $ep_orders as $key=>$ep_order ): ?>
        <hr>
        <div class="detail">
            <h3>Doctor : <?=$ep_order['doctor']?></h3>
        </div>
        <h3>From E-prescription :</h3>
        <div class="table-container">
            <table border="0">
                <tr>
                    <th>Medicine ID</th><th>Medicine Name</th><th>Medicine Strength</th><th>Price per unit</th><th>Amount</th><th>Total Price</th>
                </tr>
                <?php $total_prescription=0 ?>
                <?php foreach($ep_pres_med[$ep_order['prescription_ID']] as $key=>$ep_pres_medicine): ?>
                    <?php if( (int)$ep_pres_medicine['order_amount'] < (int)$ep_pres_medicine['available_amount'] ): ?>
                        <tr class="table-row">
                        <td><?=$ep_pres_medicine['med_ID']?></td>
                        <td><?=$ep_pres_medicine['name']?></td> 
                        <td><?=$ep_pres_medicine['strength']?></td> 
                        <td><?=$ep_pres_medicine['current_price']?></td> 
                        <td><?=$ep_pres_medicine['order_amount']?></td> 
                        <td><?=$ep_pres_medicine['current_price']*$ep_pres_medicine['order_amount']?></td> 
                        <?php $total_prescription = $total_prescription + $ep_pres_medicine['current_price']*$ep_pres_medicine['order_amount'] ?>
                    <?php else: ?>
                        <tr class="table-row-faded">
                        <td><?=$ep_pres_medicine['med_ID']?></td>
                        <td><?=$ep_pres_medicine['name']?></td> 
                        <td><?=$ep_pres_medicine['strength']?></td> 
                        <td><?=$ep_pres_medicine['current_price']?></td> 
                        <td><?= "Out of Stock" ?></td> 
                        <td></td>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tr>
        </table>
    </div>
    <h3>Total Price For Prescription : <?=$total_prescription?></h3>
    <?php $total=$total+$total_prescription ?>
    <?php endforeach; ?>
<?php endif; ?>


<?php if( $sf_orders !== NULL ):?>
    <?php foreach( $sf_orders as $key=>$sf_order ): ?>
        <hr>
        <div class="detail">
            <h3>Doctor : <?=$sf_order['doctor']?></h3>
        </div>
        <h3>From Softcopy prescription :</h3>
        <div class="table-container">
            <table border="0">
                <tr>
                    <th>Medicine ID</th><th>Medicine Name</th><th>Medicine Strength</th><th>Price per unit</th><th>Amount</th><th>Total Price</th>
                </tr>
                <?php $total_prescription=0 ?>
                <?php foreach($sf_pres_med[$sf_order['prescription_ID']] as $key=>$sf_pres_medicine): ?>
                    <?php if( (int)$sf_pres_medicine['order_amount'] < (int)$sf_pres_medicine['available_amount'] ): ?>
                        <tr class="table-row">
                        <td><?=$sf_pres_medicine['med_ID']?></td>
                        <td><?=$sf_pres_medicine['name']?></td> 
                        <td><?=$sf_pres_medicine['strength']?></td> 
                        <td><?=$sf_pres_medicine['current_price']?></td> 
                        <td><?=$sf_pres_medicine['order_amount']?></td> 
                        <td><?=$sf_pres_medicine['current_price']*$sf_pres_medicine['order_amount']?></td> 
                        <?php $total_prescription = $total_prescription + $sf_pres_medicine['current_price']*$sf_pres_medicine['order_amount'] ?>
                    <?php else: ?>
                        <tr class="table-row-faded">
                        <td><?=$sf_pres_medicine['med_ID']?></td>
                        <td><?=$sf_pres_medicine['name']?></td> 
                        <td><?=$sf_pres_medicine['strength']?></td> 
                        <td><?=$sf_pres_medicine['current_price']?></td> 
                        <td><?= "Out of Stock" ?></td> 
                        <td></td>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tr>
        </table>
    </div>
    <h3>Total Price For Prescription : <?=$total_prescription?></h3>
    <?php $total=$total+$total_prescription ?>
    <?php endforeach; ?>
<?php endif; ?>


    <hr><h1>Total Price : <?=$total?></h1>


<div class='upper-container'>
    <?php echo $component->button('take-order','','Process','button--class-0  width-10','take-order');?>
</div>


<!-- ==================== -->
<script>
    const btn1=document.getElementById("take-order");
    btn1.addEventListener('click',function(){
        location.href="pharmacy-take-pending-order?id="+<?=$order_details[0]['order_ID']?>; //get
    })
</script>