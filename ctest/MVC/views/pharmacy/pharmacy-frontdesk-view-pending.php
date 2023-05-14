<?php
    use app\core\component\Component;
    use \app\core\form\Form;
    $component=new Component();
    $total = 0;
    $NA_count =0;
    use app\core\Time;
    $timeModel = new Time();
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
    <div>
        <?php echo $component->button('delete','','Delete Order','button--class-3  width-10','delete');?>
        <?php echo $component->button('finished','','Process','button--class-0  width-10 hidden-btn','finished');?>
    </div>
</div>

<!-- Add medicines for softcopies -->
<section class="editable-selects-cont">
    <?php $form=new Form(); ?>

    <?php $form->begin('pharmacy-new-front-items?id='.$order_details['order_ID'],'post');?>
        <div class="prescription-field-container">
            <center><table border=0>
                <tr>
                    <td>
                        <div class="cls-name">
                            <?=$form->editableselect('name','Medical Product*','',$medicine_array);  ?>
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
            </table><center>
            <?php
                if (isset($err)){
                    if($err === "incorrect_medicine"){
                        echo '<p class="err-msg"><em><b>*Incorrect Medicine Name Entered</b></em></p>';
                    }
                }
            ?>
        </div>
    <?php $form->end(); ?>
</section>

<div class="table-container">
    <table border="0">
        <?php if($order_medicines!==NULL): ?>
            <tr>
                <th>Medicine ID</th><th>Medicine Name</th><th>Medicine Strength</th><th>Price per unit</th><th>Amount</th><th>Total Price</th><th>Action</th>
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
                        <td> <a class="delete-med" id=<?= $order['order_ID'].'-'.$order['med_ID'] ?> >Delete</a> </td>
                        <?php $total = $total + $order['current_price']*$order['order_amount'] ?>
                    </tr>
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
                        <td></td>
                    </tr>
                    <?php $NA_count = $NA_count + 1 ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <br><br><br><h2>Add medicines to your order</h2>
        <?php endif; ?>
        
    </table>
</div>
<h1 style="text-align: right;">Total Price : <?= 'LKR. '. number_format($total,2,'.','') ?></h1>


<div class='upper-container push-left'>
    <?php 
        if ($total!=0){
            
        }
    ?>
</div>

<!-- <div class="popup" id="popup">
        <h2>Successful !!</h2>
        <p> Notification has been sent.. </p>
        <button type="button" onclick="closePopup()" id="ok">OK</button>
</div> -->



<!-- ==================== -->
<script>
    const btn1=document.getElementById("delete");
    btn1.addEventListener('click',function(){
        location.href="pharmacy-delete-front-processing-order?id="+<?=$order_details['order_ID']?>+'&total='+<?=$total?>; 
    })

    const btn2=document.getElementById("finished");
    btn2.addEventListener('click',function(){
        location.href="pharmacy-finish-front-processing-order?id="+<?=$order_details['order_ID']?>+'&total='+<?=$total?>; 
    })

    elementsArray = document.querySelectorAll(".delete-med");
    elementsArray.forEach(function(elem) {
        comp=""+elem.id; 
        comp=comp.split("-");
        elem.addEventListener("click", function() {
            location.href='pharmacy-delete-front-med?id='+comp[0]+'&mid='+comp[1]; 
        });
    });

    <?php if( $total>0 ): ?>
        finished.classList.remove("hidden-btn")
    <?php endif; ?>

</script>