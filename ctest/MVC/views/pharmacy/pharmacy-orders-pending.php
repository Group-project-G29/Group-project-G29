<?php
    use app\core\component\Component;
    $component=new Component();
    use app\core\Time;
    $timeModel = new Time();
    // var_dump($orders);exit;
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
        <?php echo $component->searchbar($model,"name","search-bar--class1","Search by order ID, Name","search");?>
    </div>
</div>
   
<div class="table-container">

    <?php if($orders): ?>
        <div class="pending-orders-container">
                        <div class="order-header-row">
                            <div class="order_id">Order ID</div>
                            <div class="name">Name</div>
                            <div class="contact">Contact</div>
                            <div class="address">Address</div>
                            <!-- <div class="total_price">Total Price</div> -->
                            <div class="pickup_status">PickUp Status</div>
                            <div class="date">Date</div>
                            <div class="time">Time</div>
                            <div class="delete_status">Waiting/Rejected</div>
                        </div>
        
            <?php foreach($orders as $key=>$order): ?>
                <?php if($order['processing_status']=='pending'):?>

                    <div class="orders-pending-with-note search-class" id=<?= $order['order_ID'].'-'.$order['ordered_person'] ?> >
                        <div class="orders-pending-table-row">
                            <div class="orders-pending-data">
                                <div class="order_id"><?=$order['order_ID']?></div>
                                <div class="name"><?=$order['ordered_person']?></div>
                                <div class="contact"><?=$order['contact']?></div>
                                <div class="address"><?=$order['address']?></div>
                                <!-- <div class="total_price"><?=$order['total_price']?></div> -->
                                <div class="pickup_status"><?= ucfirst($order['pickup_status']) ?></div>
                                <div class="date"><?=$order['created_date']?></div>
                                <div class="time"><?= $timeModel->time_format($order['created_time']) ?></div>
                                <div class="delete_status"></div>
                            </div>
                            <?php if($order['text']!=NULL): ?>
                                <br><div class="orders-pending-note">
                                    Note* - <?=$order['text']?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php elseif($order['processing_status']=='waiting'): ?> 
                    <div class="orders-pending-with-note_row_gray search-class" id=<?= $order['order_ID'].'-'.$order['ordered_person'] ?> >
                        <div class="orders-pending-table-row">
                            <div class="orders-pending-data">
                                <div class="order_id orders-pending-with-note_row_gray_view" id=<?=$order['order_ID']?> ><?=$order['order_ID']?></div>
                                <div class="name orders-pending-with-note_row_gray_view" id=<?=$order['order_ID']?> ><?=$order['ordered_person']?></div>
                                <div class="contact orders-pending-with-note_row_gray_view" id=<?=$order['order_ID']?> ><?=$order['contact']?></div>
                                <div class="address orders-pending-with-note_row_gray_view" id=<?=$order['order_ID']?> ><?=$order['address']?></div>
                                <!-- <div class="total_price orders-pending-with-note_row_gray_view" id=<?=$order['order_ID']?> ><?=$order['total_price']?></div> -->
                                <div class="pickup_status orders-pending-with-note_row_gray_view" id=<?=$order['order_ID']?> ><?= ucfirst($order['pickup_status']) ?></div>
                                <div class="date orders-pending-with-note_row_gray_view" id=<?=$order['order_ID']?> ><?=$order['created_date']?></div>
                                <div class="time orders-pending-with-note_row_gray_view" id=<?=$order['order_ID']?> ><?= $timeModel->time_format($order['created_time']) ?></div>
                                <div class="delete_status delete-order" id=<?=$order['order_ID']?> onclick="openPopup_confirmation(<?=$order['order_ID']?>)">Delete Order</div>
                            </div>
                            <?php if($order['text']!=NULL): ?>
                                <br><div class="orders-pending-note">
                                    Note* - <?=$order['text']?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php elseif($order['processing_status']=='accepted'): ?>  
                    <div class="orders-pending-with-note row_green search-class" id=<?= $order['order_ID'].'-'.$order['ordered_person'] ?> >
                        <div class="orders-pending-table-row">
                            <div class="orders-pending-data">
                                <div class="order_id"><?=$order['order_ID']?></div>
                                <div class="name"><?=$order['ordered_person']?></div>
                                <div class="contact"><?=$order['contact']?></div>
                                <div class="address"><?=$order['address']?></div>
                                <!-- <div class="total_price"><?=$order['total_price']?></div> -->
                                <div class="pickup_status"><?= ucfirst($order['pickup_status']) ?></div>
                                <div class="date"><?=$order['created_date']?></div>
                                <div class="time"><?= $timeModel->time_format($order['created_time']) ?></div>
                                <div class="delete_status"></div>
                            </div>
                            <?php if($order['text']!=NULL): ?>
                                <br><div class="orders-pending-note">
                                    Note* - <?=$order['text']?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php elseif($order['processing_status']=='rejected'): ?> 
                    <div class="row_red search-class" id=<?= $order['order_ID'].'-'.$order['ordered_person'] ?> >
                        <div class="orders-pending-table-row">
                            <div class="orders-pending-data">
                                <div class="order_id"><?=$order['order_ID']?></div>
                                <div class="name"><?=$order['ordered_person']?></div>
                                <div class="contact"><?=$order['contact']?></div>
                                <div class="address"><?=$order['address']?></div>
                                <!-- <div class="total_price"><?=$order['total_price']?></div> -->
                                <div class="pickup_status"><?= ucfirst($order['pickup_status']) ?></div>
                                <div class="date"><?=$order['created_date']?></div>
                                <div class="time"><?= $timeModel->time_format($order['created_time']) ?></div>
                                <div class="delete_status delete-order" id=<?=$order['order_ID']?> onclick="openPopup_confirmation(<?=$order['order_ID']?>)"><a>Delete Order</a></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>    
            <?php endforeach; ?>
        </div>                        
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
    elementsArray1 = document.querySelectorAll(".orders-pending-with-note");
    elementsArray1.forEach(function(elem) {
        elem.addEventListener("click", function() {
            comp=""+elem.id; 
            comp=comp.split("-");
            location.href='pharmacy-view-pending-order?id='+comp[0]; 
        });
    });

    elementsArray2 = document.querySelectorAll(".table-row_green");
    elementsArray2.forEach(function(elem) {
        elem.addEventListener("click", function() {
            comp=""+elem.id; 
            comp=comp.split("-");
            location.href='pharmacy-view-pending-order?id='+comp[0]; 
        });
    });

    elementsArray3 = document.querySelectorAll(".orders-pending-with-note_row_gray_view");
    elementsArray3.forEach(function(elem) {
        elem.addEventListener("click", function() {
            comp=""+elem.id; 
            comp=comp.split("-");
            location.href='pharmacy-view-pending-order?id='+comp[0]; 
        });
    });

    const orders=document.querySelectorAll('.search-class');
    const searchBar=document.getElementById('search');
    searchBar.addEventListener('input',checker);
    function checker(){
        var re=new RegExp(("^"+searchBar.value).toLowerCase())
        orders.forEach((el)=>{
        comp=""+el.id; 
        comp=comp.split("-");
        
        if(searchBar.value.length==0){
            // el.classList.add("none")
        }
        else if(re.test(comp[0].toLowerCase()) || re.test(comp[1].toLowerCase()) ){
            el.classList.remove("none");
        }
        else{
            el.classList.add("none");
            
        }
        })
        if(searchBar.value.length==0){
            orders.forEach((el)=>{
                el.classList.remove("none");
            }) 
        }
    }

    
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