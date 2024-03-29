<?php
    use app\core\component\Component;
    $component=new Component();
    use app\core\Time;
    $timeModel = new Time();
// var_dump($orders);
// exit;

?>

<p class="navigation-text-line-p"> 
    <a class="navigation-text-line-link" href="/ctest/pharmacy-orders-pending">orders</a>/
    <a class="navigation-text-line-link">pending orders</a> 
</p>

<div class='upper-container'>
    <!-- implement this -->
    <?php echo $component->button('pending','','Pending Orders','button--class-0-deactive  width-10','pending');?>
    <?php echo $component->button('packed','','Packed Orders','button--class-0-deactive  width-10','packed');?>
    <?php echo $component->button('finished','','Finished Orders','button--class-0-active  width-10','finished');?>
</div>

<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"name","search-bar--class1","Search by order ID, patient 1D","search");?>
    </div>
    <?php 
    echo $component->button('new-order','','Add New Order','button--class-0  width-10','new-order');
    ?>
</div>
   
<div class="table-container">
<table border="0">
    <tr>
        <th>Order ID</th>
        <th>Name</th>
        <th>Age</th>
        <th>Contact</th>
        <th>Doctor</th>
        <th>Date</th>
        <th>Time</th>
        <th>Total Price</th>
    </tr>
    <?php if($orders): ?>
        <?php foreach($orders as $key=>$order): ?>
            <tr class="table-row search-class" id=<?= $order['order_ID'].'-'.$order['name'] ?> >
                <td><?=$order['order_ID']?></td>
                <td><?=$order['name']?></td> 
                <td><?=$order['age']?></td> 
                <td><?=$order['contact']?></td> 
                <td><?=$order['doctor']?></td> 
                <td><?=$order['date']?></td> 
                <td><?=$order['time']?></td> 
                <td><?= 'LKR. '. number_format($order['total'],2,'.','') ?></td> 
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
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

    <div class="popup confirmation" id="popup_confirmation">
            <h2>Delete this order.</h2>
            <h2>Are You Sure ?</h2>
            <div style="display: flex;">
                <div style="width:50%; margin:10px;" ><button type="button" onclick="closePopup_confirmation()" id="cancle_confirmation">CANCLE</button></div>
                <div style="width:50%; margin:10px;"><button type="button" id="ok_confirmation">OK</button></div>
            </div>
    </div>
<!-- =================================================== -->

<script>
    elementsArray1 = document.querySelectorAll(".table-row");
    elementsArray1.forEach(function(elem) {
        comp=""+elem.id; 
        comp=comp.split("-");
        elem.addEventListener("click", function() {
            location.href='pharmacy-view-front-orders-finished?id='+comp[0]; 
        });
    });

    // elementsArray2 = document.querySelectorAll(".table-row_green");
    // elementsArray2.forEach(function(elem) {
    //     elem.addEventListener("click", function() {
    //         location.href='pharmacy-view-pending-order?id='+elem.id; 
    //     });
    // });

    // elementsArray3 = document.querySelectorAll(".table-row_gray_view");
    // elementsArray3.forEach(function(elem) {
    //     elem.addEventListener("click", function() {
    //         location.href='pharmacy-view-pending-order?id='+elem.id; 
    //     });
    // });
    
    
    const btn1=document.getElementById("pending");
    btn1.addEventListener('click',function(){
        location.href="pharmacy-front-orders-pending"; //get
    })

    const btn2=document.getElementById("finished");
    btn2.addEventListener('click',function(){
        location.href="pharmacy-front-orders-finished"; //get
    })

    const btn3=document.getElementById("packed");
    btn3.addEventListener('click',function(){
        location.href="pharmacy-front-orders-packed"; //get
    })

    const btn4=document.getElementById("new-order");
    btn4.addEventListener('click',function(){
        location.href="pharmacy-new-order"; //get
    })

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