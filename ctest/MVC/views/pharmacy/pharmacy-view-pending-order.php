<?php
    use app\core\component\Component;
    $component=new Component();
    $total = 0;

    // var_dump($online_orders);
    // var_dump($sf_orders);
    // var_dump($ep_orders);
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
    <h3>Order ID : <?=$order_details[0]['order_ID']?></h3>
    <h3>Date :<?=$order_details[0]['created_date']?></h3>
    <h3>Time :<?=$order_details[0]['created_time']?></h3>
    <h3>Pickup Status : <?=$order_details[0]['pickup_status']?></h3>
    <h3>Payment Status : <?=$order_details[0]['payment_status']?></h3>
    <hr>
    <h3>Patient Name : <?=$order_details[0]['name']?></h3>
    <h3>Contact Number : <?=$order_details[0]['contact']?></h3>
    <h3>Address : <?=$order_details[0]['address']?></h3>
    <?php if($order_details[0]['text']!=NULL): ?>
        <h3 class="orders-pending-note">
            Note* - <?=$order_details[0]['text']?>
        </h3>
    <?php endif; ?>
</div>

<div class="order-type-view">
    <?php if( $online_orders !== NULL ): ?>
        <div class="drop-down-container">
            <div class="one-drop-down" onclick="show('online_orders_products')">Online Ordered Products <img src="./media/images/icons/angle-down.png" alt="down arrow image"></div>
                <div id="online_orders_products" hidden>
                    <?php $total_online=0 ?>
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
                </div>
            <h3 style="text-align: right;">Online Ordered Products : <?= 'LKR. '. number_format($total_online,2,'.','') ?></h3>
        </div>
        <?php $total=$total+$total_online ?>
    <?php endif; ?>


    <?php if( $ep_orders !== NULL ):?>
        <?php foreach( $ep_orders as $key=>$ep_order ): ?>
            <div class="drop-down-container">
                <div class="one-drop-down" onclick="show('<?= 'ep'.$key ?>')">E-prescription <img src="./media/images/icons/angle-down.png" alt="down arrow image"></div>
                    <div id="<?= 'ep'.$key ?>" hidden>    
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
                    </div>
                <h3 style="text-align: right;">E-Prescription : <?= 'LKR. '. number_format($total_prescription,2,'.','') ?></h3>
            </div>
        <?php $total=$total+$total_prescription ?>
        <?php endforeach; ?>
    <?php endif; ?>


    <?php if( $sf_orders !== NULL ):?>
        <?php foreach( $sf_orders as $key=>$sf_order ): ?>
            <div class="drop-down-container">
                <div class="one-drop-down" onclick="show('<?= 'sf'.$key ?>')">Softcopy prescription <img src="./media/images/icons/angle-down.png" alt="down arrow image"></div>
                    <div id="<?= 'sf'.$key ?>" hidden>
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
                    </div>
                <h3 style="text-align: right;">Softcopy Prescription : <?= 'LKR. '. number_format($total_prescription,2,'.','') ?></h3>
            </div>
        <?php $total=$total+$total_prescription ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

    <hr><h1 style="text-align: right;">Total Price : <?= 'LKR. '. number_format($total,2,'.','') ?></h1>


<div class='upper-container'>
    <?php echo $component->button('take-order','','Process','button--class-0  width-10','take-order');?>
</div>


<!-- ==================== -->
<script>
    const btn1=document.getElementById("take-order");
    btn1.addEventListener('click',function(){
        location.href="pharmacy-take-pending-order?id="+<?=$order_details[0]['order_ID']?>; //get
    })

    function show(day){
        var x = document.getElementById(day);
        if (x.hidden === true) {
            x.hidden = false;
        } else {
            x.hidden = true;
        }
    }
</script>