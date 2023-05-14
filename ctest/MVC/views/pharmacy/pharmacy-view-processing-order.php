<?php
    use app\core\component\Component;
    use \app\core\form\Form;
    use app\models\Employee;
    $component=new Component();
    $total = 0;
    $NA_count =0;
    use app\core\Time;

    $timeModel = new Time();
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
    <div>
        <?php echo $component->button('notify_availability','','Send Notification','button--class-0  width-10 hidden-btn','notify_availability');?>
        <?php echo $component->button('finish-process','','Finish Process','button--class-0  width-10','finish-process');?>
    </div>
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
                                <?php if( $order['status']=='include' ): ?>
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
                                    <?php if( $ep_pres_medicine['status']=='include' ): ?>
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

                        <!-- Add medicines for softcopies -->
                        <section class="editable-selects-cont">
                            <?php $form=new Form(); ?>

                            <?php $form->begin('pharmacy-new-order-items?presid='.$sf_order['prescription_ID'],'post');?>
                                <div class="prescription-field-container">
                                <center><table border=0>
                                        <tr>
                                            <td>
                                                <div class="cls-name">
                                                    <?=$form->editableselect('name'.$sf_order['prescription_ID'],'Medical Product*','',$medicine_array);  ?>
                                                </div> 
                                            </td>
                                            <td>
                                                <div class="cls-amount">
                                                    <?=$form->editableselect('amount','Amount*','',[]); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?=$component->button('submit','submit','Add','button--class-0 set-op','addbtn'); ?>
                                            </td>
                                        </tr>
                                    </table></center>
                                    <?php
                                        if (isset($err)){
                                            if($err === "incorrect_medicine"){
                                                echo '<p class="err-msg"><em><b>*Incorrect Medicine Name Entered</b></em></p>';
                                            }
                                        }
                                    ?>
                                </div>
                            <?php $form->end(); ?>
                            <center><a class="view-prescription-separate" target="_blank"  href="view-softcopy?id=<?=$sf_order['prescription_ID']?>" >
                                view prescription
                            </a></center>
                        </section>



                        <div class="table-container">
                            <table border="0">
                                <tr>
                                    <th>Medicine ID</th>
                                    <th>Medicine Name</th>
                                    <th>Medicine Strength</th>
                                    <th>Price per unit</th>
                                    <th>Amount</th>
                                    <th>Total Price</th>
                                    <th>Action</th>
                                </tr>
                                <?php $total_prescription=0 ?>
                                <?php foreach($sf_pres_med[$sf_order['prescription_ID']] as $key=>$sf_pres_medicine): ?>
                                    <?php if( $sf_pres_medicine['status']=='include' ): ?>
                                        <tr class="table-row unselectable">
                                        <td><?=$sf_pres_medicine['med_ID']?></td>
                                        <td><?=$sf_pres_medicine['name']?></td> 
                                        <td><?=$sf_pres_medicine['strength']?></td> 
                                        <td><?= 'LKR. '. number_format($sf_pres_medicine['current_price'],2,'.','') ?></td> 
                                        <td><?=$sf_pres_medicine['order_amount']?></td> 
                                        <td><?= 'LKR. '. number_format($sf_pres_medicine['current_price']*$sf_pres_medicine['order_amount'],2,'.','') ?></td>  
                                        <td> <a class="delete-med" id=<?= $sf_order['prescription_ID'].'-'.$sf_pres_medicine['med_ID'] ?> ><img class="delete-image" src="./media/anim_icons/delete.png"></a> </td>
                                        <?php $total_prescription = $total_prescription + $sf_pres_medicine['current_price']*$sf_pres_medicine['order_amount'] ?>
                                    <?php else: ?>
                                        <tr class="table-row-faded unselectable">
                                        <td><?=$sf_pres_medicine['med_ID']?></td>
                                        <td><?=$sf_pres_medicine['name']?></td> 
                                        <td><?=$sf_pres_medicine['strength']?></td> 
                                        <td><?= 'LKR. '. number_format($sf_pres_medicine['current_price'],2,'.','') ?></td> 
                                        <td style="color:red;"><?= "Out of Stock" ?></td> 
                                        <td style="color:red;">
                                            <?php 
                                                if ( (int)$sf_pres_medicine['available_amount']==0 ){
                                                    echo 'No items available';
                                                } elseif ( (int)$sf_pres_medicine['available_amount']==1 ){
                                                    echo '1 item available';
                                                } else {
                                                    echo $sf_pres_medicine['available_amount'].' items available'; 
                                                }
                                            ?>    
                                        </td>
                                        <td> <a class="delete-med" id=<?= $sf_order['prescription_ID'].'-'.$sf_pres_medicine['med_ID'] ?> >Delete</a> </td>
                                        <?php $NA_count = $NA_count + 1 ?>
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

    <h1 style="text-align: right;">Total Price : <?= 'LKR. '. number_format($total,2,'.','') ?></h1>

<div class='upper-container'>
    <?php echo $component->button('cancle-process','','Cancle Process','button--class-3  width-10','cancle-process');?>

    
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
        location.href="pharmacy-cancle-processing-order?id="+<?=$order_details[0]['order_ID']?>; //get
    })
    
    const btn2=document.getElementById("finish-process");
    btn2.addEventListener('click',function(){
        location.href="pharmacy-finish-processing-order?id="+<?=$order_details[0]['order_ID']?>+'&total='+<?=$total?>; //get
    })
    
    
    elementsArray = document.querySelectorAll(".delete-med");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            comp=""+elem.id; 
            comp=comp.split("-");
            location.href='pharmacy-delete-pres-med?pid='+comp[0]+'&mid='+comp[1]; 
        });
    });
    
    const btn3=document.getElementById("notify_availability");
    // btn3.onclick=openPopup();
    btn3.addEventListener('click',function(){
        // openPopup();
        location.href="pharmacy-notify-processing-order?id="+<?=$order_details[0]['order_ID']?>; //get
    })

    function show(day){
        var x = document.getElementById(day);
        if (x.hidden === true) {
            x.hidden = false;
        } else {
            x.hidden = true;
        }
    }

    <?php if( $NA_count>0 ): ?>
        notify_availability.classList.remove("hidden-btn")
    <?php endif; ?>

</script>