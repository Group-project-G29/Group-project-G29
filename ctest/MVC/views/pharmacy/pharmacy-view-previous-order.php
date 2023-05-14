<?php
    use app\core\component\Component;
    $component=new Component();
    $total = 0;
    use app\core\Time;
use app\models\Employee;

    $timeModel = new Time();
    // var_dump($orders);
    // exit;
    // var_dump($order_details);    //done
    // var_dump($online_orders);    //done
    // var_dump($sf_orders);
    // var_dump($sf_pres_med);
    // var_dump($ep_orders);
    // var_dump($ep_pres_med);
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


<div class="order-type-view">
    <?php if( $online_orders != NULL ): ?>
        <div class="drop-down-container">
            <div class="one-drop-down" onclick="show('online_orders_products')">Online Ordered Products <img src="./media/images/icons/angle-down.png" alt="down arrow image"></div>
                <div id="online_orders_products" hidden>
                    <?php $total_online=0 ?>
                    <div class="table-container">
                        <table border="0">
                            <tr>
                                <th>Medicine ID</th><th>Medicine Name</th><th>Medicine Strength</th><th>Price per unit</th><th>Amount</th><th>Total Price</th>
                            </tr>
                            <?php foreach($online_orders as $key=>$order): ?>
                                <?php if( (int)$order['order_amount'] < (int)$order['available_amount'] ): ?>
                                    <tr class="table-row unselectable">
                                    <td><?=$order['med_ID']?></td>
                                    <td><?=$order['name']?></td> 
                                    <td><?=$order['strength']?></td> 
                                    <td><?= 'LKR. '. number_format($order['current_price'],2,'.','') ?></td> 
                                    <td><?=$order['order_amount']?></td> 
                                    <td><?= 'LKR. '. number_format($order['current_price']*$order['order_amount'],2,'.','') ?></td> 
                                    <?php $total_online = $total_online + $order['current_price']*$order['order_amount'] ?>
                                <?php else: ?>
                                    <tr class="table-row-faded unselectable">
                                    <td><?=$order['med_ID']?></td>
                                    <td><?=$order['name']?></td> 
                                    <td><?=$order['strength']?></td> 
                                    <td><?= 'LKR. '. number_format($order['current_price'],2,'.','') ?></td> 
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


    <?php if( $ep_orders != NULL ):?>
        <?php foreach( $ep_orders as $key=>$ep_order ): ?>
            <div class="drop-down-container">
                <div class="one-drop-down" onclick="show('<?= 'ep'.$key ?>')">E-prescription <img src="./media/images/icons/angle-down.png" alt="down arrow image"></div>
                    <div id="<?= 'ep'.$key ?>" hidden>    
                        <div class="detail">
                            <table>
                                <tr><td>Doctor </td><td>
                                    <?php 
                                        $doctorModel = new Employee();
                                        $doctor = $doctorModel->get_employee_details_by_NIC($ep_order['doctor'] );
                                        echo ' : '.$doctor[0]['name'];
                                    ?></td></tr>
                                <?php if($ep_order["last_processed_timestamp"] != NULL): ?>
                                    <tr><td>Last Processed </td><td> <?= ' : '.$ep_order["last_processed_timestamp"] ?></td></tr>
                                <?php endif; ?>
                            </table>
                        </div>

                        <div class="table-container">
                            <table border="0">
                                <tr>
                                    <th>Medicine ID</th><th>Medicine Name</th><th>Medicine Strength</th><th>Price per unit</th><th>Amount</th><th>Total Price</th>
                                </tr>
                                <?php $total_prescription=0 ?>
                                <?php foreach($ep_pres_med[$ep_order['prescription_ID']] as $key=>$ep_pres_medicine): ?>
                                    <?php if( (int)$ep_pres_medicine['order_amount'] < (int)$ep_pres_medicine['available_amount'] ): ?>
                                        <tr class="table-row unselectable">
                                        <td><?=$ep_pres_medicine['med_ID']?></td>
                                        <td><?=$ep_pres_medicine['name']?></td> 
                                        <td><?=$ep_pres_medicine['strength']?></td> 
                                        <td><?= 'LKR. '. number_format($ep_pres_medicine['current_price'],2,'.','') ?></td>  
                                        <td><?=$ep_pres_medicine['order_amount']?></td> 
                                        <td><?= 'LKR. '. number_format($ep_pres_medicine['current_price']*$ep_pres_medicine['order_amount'],2,'.','') ?></td>
                                        <?php $total_prescription = $total_prescription + $ep_pres_medicine['current_price']*$ep_pres_medicine['order_amount'] ?>
                                    <?php else: ?>
                                        <tr class="table-row-faded unselectable">
                                        <td><?=$ep_pres_medicine['med_ID']?></td>
                                        <td><?=$ep_pres_medicine['name']?></td> 
                                        <td><?=$ep_pres_medicine['strength']?></td> 
                                        <td><?= 'LKR. '. number_format($ep_pres_medicine['current_price'],2,'.','') ?></td> 
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


    <?php if( $sf_orders != NULL ):?>
        <?php foreach( $sf_orders as $key=>$sf_order ): ?>
            <div class="drop-down-container">
                <div class="one-drop-down" onclick="show('<?= 'sf'.$key ?>')">Softcopy prescription <img src="./media/images/icons/angle-down.png" alt="down arrow image"></div>
                    <div id="<?= 'sf'.$key ?>" hidden>

                        <center><a class="view-prescription-separate" target="_blank"  href="view-softcopy?id=<?=$sf_order['prescription_ID']?>" >
                                view prescription
                        </a></center>

                        <div class="table-container">
                            <?php $total_prescription=0 ?>
                            <?php if ($sf_pres_med[$sf_order['prescription_ID']]): ?>
                                <table border="0">
                                    <tr>
                                        <th>Medicine ID</th><th>Medicine Name</th><th>Medicine Strength</th><th>Price per unit</th><th>Amount</th><th>Total Price</th>
                                    </tr>
                                    <?php foreach($sf_pres_med[$sf_order['prescription_ID']] as $key=>$sf_pres_medicine): ?>
                                        <?php if( (int)$sf_pres_medicine['order_amount'] < (int)$sf_pres_medicine['available_amount'] ): ?>
                                            <tr class="table-row unselectable">
                                            <td><?=$sf_pres_medicine['med_ID']?></td>
                                            <td><?=$sf_pres_medicine['name']?></td> 
                                            <td><?=$sf_pres_medicine['strength']?></td> 
                                            <td><?= 'LKR. '. number_format($sf_pres_medicine['current_price'],2,'.','') ?></td>  
                                            <td><?=$sf_pres_medicine['order_amount']?></td> 
                                            <td><?= 'LKR. '. number_format($sf_pres_medicine['current_price']*$sf_pres_medicine['order_amount'],2,'.','') ?></td>  
                                            <?php $total_prescription = $total_prescription + $sf_pres_medicine['current_price']*$sf_pres_medicine['order_amount'] ?>
                                        <?php else: ?>
                                            <tr class="table-row-faded unselectable">
                                            <td><?=$sf_pres_medicine['med_ID']?></td>
                                            <td><?=$sf_pres_medicine['name']?></td> 
                                            <td><?=$sf_pres_medicine['strength']?></td> 
                                            <td><?= 'LKR. '. number_format($sf_pres_medicine['current_price'],2,'.','') ?></td>   
                                            <td><?= "Out of Stock" ?></td> 
                                        <td></td>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tr>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                <h3 style="text-align: right;">Softcopy Prescription : <?= 'LKR. '. number_format($total_prescription,2,'.','') ?></h3>
            </div>
        <?php $total=$total+$total_prescription ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

    <hr><h1 style="text-align: right; padding-bottom: 100px;">Total Price : <?= 'LKR. '. number_format($total,2,'.','') ?></h1>

<script>
    function show(day){
        var x = document.getElementById(day);
        if (x.hidden === true) {
            x.hidden = false;
        } else {
            x.hidden = true;
        }
    }
</script>