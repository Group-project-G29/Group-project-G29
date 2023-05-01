<?php
    use app\core\component\Component;
    use \app\core\form\Form;
    $component=new Component();
    $total = 0;
    $NA_count =0;
    // var_dump($prescriptionmodel);   
    // var_dump($ordermodel);   
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
                    <?php $NA_count = $NA_count + 1 ?>
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
                        <?php $NA_count = $NA_count + 1 ?>
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


        <!-- Add medicines for softcopies -->
        <section>
            <?php $form=new Form(); ?>

            <?php $form->begin('pharmacy-new-order-items?presid='.$sf_order['prescription_ID'],'post');?>
                <div class="prescription-field-container">
                    <div class="cls-name">
                    <?=$form->editableselect('name'.$sf_order['prescription_ID'],'Medical Product*','',$medicine_array);  ?>
                    </div> 
                    <div class="cls-amount">
                    <?=$form->editableselect('amount','Amount*','',[]); ?>
                    </div>
                    
                    <?=$component->button('submit','submit','+','button-plus','addbtn'); ?>
                </div>
            <?php $form->end(); ?>
        </section>


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
                        <?php $NA_count = $NA_count + 1 ?>
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
    <?php echo $component->button('cancle-process','','Cancle Process','button--class-3  width-10','cancle-process');?>

    <!-- <?php if( $order_type=='Softcopy-prescription' ): ?>
    <?php echo $component->button('add-more-medicine','','Add More Medicine','button--class-0  width-10','add-more-medicine');?>
    <?php endif; ?> -->

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
        location.href="pharmacy-cancle-processing-order?id="+<?=$order_details[0]['order_ID']?>; //get
    })
    
    const btn2=document.getElementById("finish-process");
    btn2.addEventListener('click',function(){
        location.href="pharmacy-finish-processing-order?id="+<?=$order_details[0]['order_ID']?>; //get
    })
    
    const btn3=document.getElementById("notify-availability");
    // btn3.onclick=openPopup();
    btn3.addEventListener('click',function(){
        // openPopup();
        location.href="pharmacy-notify-processing-order?id="+<?=$order_details[0]['order_ID']?>; //get
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
    //     location.href="pharmacy-notify-processing-order?id="+<?=$order_details[0]['order_ID']?>; //get
    // })

    // const btn4=document.getElementById("add-more-medicine");
    // btn4.addEventListener('click',function(){
    //     location.href="pharmacy-add-medicine-processing-order?id="+<?=$order_details[0]['order_ID']?>; //get
    // })



    // // For Adding medicines to the softcopies
    // const inputAmount=document.getElementById('input-amount');
    // const itemsLocal=document.querySelectorAll('.itemsamount');
    // inputAmount.addEventListener('focusout',()=>{
    //     inputvaluearray=(inputAmount.value).split(' ');
    //     value=inputvaluearray[0]+(' '+namecarry+'s');
    //     inputAmount.value=value;
    // })
    // const inptarray=document.querySelectorAll('.in');
    // const nameLocal=document.querySelector('.sl-name');
    // nameLocal.addEventListener('input',()=>{
    //     if((nameLocal.value).length==0)
    //     inptarray.forEach((el)=>{
    //         el.value='';
    //     });
    // })

    // //change on select
    // const clsname=document.querySelector(".cls-name");
    // const clsdamount=document.querySelector(".cls-dev-amount");
    // const clsfrequency=document.querySelector(".cls-frequency");
    // const clsdispense=document.querySelector(".cls-dispense");
    // const clsamount=document.querySelector(".cls-amount");
    // const mainaddbtn=document.getElementById('add-button');
    // const selectItems=document.querySelectorAll('.ed-se-item-name');
    // var allshow=[clsname,clsfrequency,clsdispense,clsdamount,clsamount];
    // var arrayshow={'device':[clsname,clsfrequency,clsdispense,clsdamount],'tablet':[clsname,clsfrequency,clsdispense,clsamount],'bottle':[clsname,clsfrequency,clsdispense,clsamount]};
    // selectItems.forEach((el)=>{
    //     el.addEventListener('click',()=>{
    //         comp=(""+el.id).split("_");
    //         allshow.forEach(elem=>{
    //             if(arrayshow[comp[1]].includes(elem)){
    //                 elem.classList.remove('hide');
    //             }
    //             else {
    //                 elem.classList.add('hide');
    //             }
            
    //         })
    //     })
    // })

</script>