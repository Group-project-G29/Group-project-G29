<?php
    use app\core\component\Component;
    use \app\core\form\Form;
    $component=new Component();
    $total = 0;
    $NA_count =0;
    // var_dump($order_details);   
    // var_dump($order_medicines);   
    // exit;
    // echo 'front pending';
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

<!-- Add medicines for softcopies -->
<section>
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
                        <?=$component->button('submit','submit','+','button-plus','addbtn'); ?>
                    </td>
                </tr>
            </table><center>
        </div>
    <?php $form->end(); ?>
</section>

<div class="table-container">
    <table border="0">
        <?php if($order_medicines!==NULL): ?>
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
                    </tr>
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
                    </tr>
                    <?php $NA_count = $NA_count + 1 ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <br><br><br><h2>Add medicines to your order</h2>
        <?php endif; ?>
        
    </table>
</div>
<h1 style="text-align: right;">Total Price : <?=$total?></h1>


<div class='upper-container'>
    <?php echo $component->button('delete','','Delete Order','button--class-3  width-10','delete');?>
    <?php echo $component->button('finished','','Process','button--class-0  width-10','finished');?>
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
        location.href="pharmacy-delete-front-processing-order?id="+<?=$order_details['order_ID']?>+'&total='+<?=$total?>; //get
    })

    const btn2=document.getElementById("finished");
    btn2.addEventListener('click',function(){
        location.href="pharmacy-finish-front-processing-order?id="+<?=$order_details['order_ID']?>+'&total='+<?=$total?>; //get
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