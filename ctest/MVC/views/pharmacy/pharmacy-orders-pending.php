<?php
    use app\core\component\Component;
    $component=new Component();
?>

<p class="navigation-text-line-p"> 
    <a class="navigation-text-line-link" href="/ctest/pharmacy-orders-pending">orders</a>/
    <a class="navigation-text-line-link">pending orders</a> 
</p>

<div class='upper-container'>
    <!-- implement this -->
    <?php echo $component->button('pending','','Pending Orders','button--class-0-active  width-10','pending');?>
    <?php echo $component->button('processing','','Processing Orders','button--class-0-deactive  width-10','processing');?>
    <?php echo $component->button('delivering','','Packed Orders','button--class-0-deactive  width-10','delivering');?>
</div>

<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"name","search-bar--class1","Search by order ID, patient 1D","searh");?>
    </div>
</div>
   
<div class="table-container">
    <?php if($orders): ?>
        <table border="0">
            <tr>
                <th>Order ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Total Price</th>
                <th>Note</th>
                <th>PickUp Status</th>
                <th>Date</th>
                <th>Time</th>
                <th>Waiting/Rejected</th>
            </tr>
        
            <?php foreach($orders as $key=>$order): ?>
                <?php if($order['processing_status']=='pending'):?>
                        <tr class="table-row" id=<?=$order['order_ID']?> >
                            <td><?=$order['order_ID']?></td>
                            <td><?=$order['name']?></td> 
                            <td><?=$order['contact']?></td> 
                            <td><?=$order['address']?></td> 
                            <td><?=$order['total_price']?></td> 
                            <td>
                                <?php if($order['text']!=NULL): ?>
                                    <?=$order['text']?>
                                <?php else: ?>
                                    <?= 'NA' ?>
                                <?php endif; ?>
                            </td> 
                            <td><?=$order['pickup_status']?></td> 
                            <!-- deliveryd by -> delivery rider -->
                            <td><?=$order['created_date']?></td> 
                            <td><?=$order['created_time']?></td> 
                            <td></td>
                        </tr>
                <?php elseif($order['processing_status']=='waiting'): ?> 
                        <tr class="table-row_gray" id=<?=$order['order_ID']?> >
                            <td class="table-row_gray_view" id=<?=$order['order_ID']?> ><?=$order['order_ID']?></td>
                            <td class="table-row_gray_view" id=<?=$order['order_ID']?> ><?=$order['name']?></td> 
                            <td class="table-row_gray_view" id=<?=$order['order_ID']?> ><?=$order['contact']?></td> 
                            <td class="table-row_gray_view" id=<?=$order['order_ID']?> ><?=$order['address']?></td> 
                            <td class="table-row_gray_view" id=<?=$order['order_ID']?> ><?=$order['total_price']?></td> 
                            <td class="table-row_gray_view" id=<?=$order['order_ID']?> >
                                <?php if($order['text']!=NULL): ?>
                                    <?=$order['text']?>
                                <?php else: ?>
                                    <?= 'NA' ?>
                                <?php endif; ?>
                            </td> 
                            <td class="table-row_gray_view" id=<?=$order['order_ID']?> ><?=$order['pickup_status']?></td> 
                            <!-- deliveryd by -> delivery rider -->
                            <td class="table-row_gray_view" id=<?=$order['order_ID']?> ><?=$order['created_date']?></td> 
                            <td class="table-row_gray_view" id=<?=$order['order_ID']?> ><?=$order['created_time']?></td> 
                            <td><a class='delete-order' id=<?=$order['order_ID']?> onclick="openPopup_confirmation(<?=$order['order_ID']?>)">Delete Order</a></td>
                        </tr>
                <?php elseif($order['processing_status']=='accepted'): ?>  
                        <tr class="table-row_green" id=<?=$order['order_ID']?> >
                        <td><?=$order['order_ID']?></td>
                            <td><?=$order['name']?></td> 
                            <td><?=$order['contact']?></td> 
                            <td><?=$order['address']?></td> 
                            <td><?=$order['total_price']?></td> 
                            <td>
                                <?php if($order['text']!=NULL): ?>
                                    <?=$order['text']?>
                                <?php else: ?>
                                    <?= 'NA' ?>
                                <?php endif; ?>
                            </td> 
                            <td><?=$order['pickup_status']?></td> 
                            <!-- deliveryd by -> delivery rider -->
                            <td><?=$order['created_date']?></td> 
                            <td><?=$order['created_time']?></td> 
                            <td></td>
                        </tr>
                <?php elseif($order['processing_status']=='rejected'): ?> 
                        <tr class="table-row_red" id=<?=$order['order_ID']?> >
                        <td><?=$order['order_ID']?></td>
                            <td><?=$order['name']?></td> 
                            <td><?=$order['contact']?></td> 
                            <td><?=$order['address']?></td> 
                            <td><?=$order['total_price']?></td> 
                            <td>
                                <?php if($order['text']!=NULL): ?>
                                    <?=$order['text']?>
                                <?php else: ?>
                                    <?= 'NA' ?>
                                <?php endif; ?>
                            </td> 
                            <td><?=$order['pickup_status']?></td> 
                            <!-- deliveryd by -> delivery rider -->
                            <td><?=$order['created_date']?></td> 
                            <td><?=$order['created_time']?></td> 
                            <td><a class='delete-order' id=<?=$order['order_ID']?> onclick="openPopup_confirmation(<?=$order['order_ID']?>)">Delete Order</a></td>
                        </tr>  
                <?php endif; ?>    
            <?php endforeach; ?>
        </table>

    <?php else: ?>
        <br><br><br><h2>No Current Pending Orders</h2>
    <?php endif; ?>
</div>


<!-- ========================POPUP====================== -->
    <div class="popup notify-na-medicine" id="popup_notify_medicine">
            <h2>Successful !!</h2>
            <p> Notification has been sent.. </p>
            <button type="button" onclick="closePopup_notify_medicine()" id="ok_notify_medicine">OK</button>
    </div>

    <div class="popup notify-na-medicine" id="popup_deleted_order">
            <h2>Confirmed !!</h2>
            <p> order has been deleted.. </p>
            <button type="button" onclick="closePopup_deleted_order()" id="ok_deleted_order">OK</button>
    </div>

    <!-- <div class="popup popup-background" > -->
        <div class="popup confirmation" id="popup_confirmation">
            <h2>Delete this order.</h2>
            <h2>Are You Sure ?</h2>
            <div style="display: flex;">
                <div style="width:50%; margin:10px;" ><button type="button" onclick="closePopup_confirmation()" id="cancle_confirmation">CANCLE</button></div>
                <div style="width:50%; margin:10px;"><button type="button" id="ok_confirmation">OK</button></div>
            </div>
        </div>
    <!-- </div> -->
<!-- =================================================== -->

<script>
    elementsArray1 = document.querySelectorAll(".table-row");
    elementsArray1.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='pharmacy-view-pending-order?id='+elem.id; 
        });
    });

    elementsArray2 = document.querySelectorAll(".table-row_green");
    elementsArray2.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='pharmacy-view-pending-order?id='+elem.id; 
        });
    });

    elementsArray3 = document.querySelectorAll(".table-row_gray_view");
    elementsArray3.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='pharmacy-view-pending-order?id='+elem.id; 
        });
    });

    
    const btn1=document.getElementById("pending");
    btn1.addEventListener('click',function(){
        location.href="pharmacy-orders-pending"; //get
    })

    const btn2=document.getElementById("processing");
    btn2.addEventListener('click',function(){
        location.href="pharmacy-orders-processing"; //get
    })

    const btn3=document.getElementById("delivering");
    btn3.addEventListener('click',function(){
        location.href="pharmacy-orders-delivering"; //get
    })
    
    const btn4=document.getElementById("new-order");
    btn4.addEventListener('click',function(){
        location.href="pharmacy-new-order"; //get
    })
    
    // elementsArray4 = document.querySelectorAll(".delete-order");
    // elementsArray4.forEach(function(elem) {
    //     elem.addEventListener("click", function() {
    //         location.href='pharmacy-delete-rejected?id='+elem.id;
    //         openPopup_confirmation(elem.id);
    //     });
    // });

// ========================POPUP======================
    <?php if(isset($popup)): ?>
        <?php if($popup == 'notify-na-medicine'): ?>
            openPopup_popup_notify_medicine();
        <?php endif; ?>
    <?php endif; ?>
    function openPopup_popup_notify_medicine(){
        popup_notify_medicine.classList.add("open-popup");
    }
    function closePopup_notify_medicine(){
        popup_notify_medicine.classList.remove("open-popup");
    }

    <?php if(isset($popup)): ?>
        <?php if($popup == 'deleted_order'): ?>
            openPopup_deleted_order();
        <?php endif; ?>
    <?php endif; ?>
    function openPopup_deleted_order(){
        popup_deleted_order.classList.add("open-popup");
    }
    function closePopup_deleted_order(){
        popup_deleted_order.classList.remove("open-popup");
    }

    function openPopup_confirmation(order_id){
        popup_confirmation.classList.add("open-popup");
        const ok_btn = document.getElementById("ok_confirmation");
        ok_btn.onclick = ()=> {
            location.href='pharmacy-delete-rejected?id='+order_id; 
        }
    }
    function closePopup_confirmation(){
        popup_confirmation.classList.remove("open-popup");
    }
</script>