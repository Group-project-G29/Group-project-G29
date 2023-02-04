<?php
    use app\core\component\Component;
    $component=new Component();
// var_dump($orders);
// exit;

?>
<div class='upper-container'>
    <!-- implement this -->
    <?php echo $component->button('pending','','Pending Orders','button--class-0-active  width-10','pending');?>
    <?php echo $component->button('processing','','Processing Orders','button--class-0-deactive  width-10','processing');?>
    <?php echo $component->button('delivering','','Delivering Orders','button--class-0-deactive  width-10','delivering');?>
</div>

<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"name","search-bar--class1","Search by order ID, patient 1D","searh");?>
    </div>
    <?php echo $component->button('new-order','','Add New Order','button--class-0  width-10','new-order');?>
</div>
   
<div class="table-container">
<table border="0">
    <tr>
        <th>Order ID</th><th>Date</th><th>Time</th><th>Status</th><th>Patient ID</th><th>Cart ID</th>
    </tr>
    <?php if($orders): ?>
        
        <?php foreach($orders as $key=>$order): ?>
            <tr class="table-row">
                <td><?=$order['order_ID']?></td>
                <td><?=$order['time_of_creation']?></td> 
                <td><?=$order['created_time']?></td> 
                <td><?=$order['processing_status']?></td> 
                <td><?=$order['patient_ID']?></td> 
                <td><?=$order['cart_ID']?></td> 
            </tr>
        <?php endforeach; ?>
    </table>
    
    <div>
        <!-- remove this -->
        <?php
            if ( $order['processing_status'] === 'pending'){
                echo $component->button('', '', 'View Order', 'button--class-2', $order['order_ID']);
            } else if ( $order['processing_status'] === 'processing' ) {
                echo $component->button('', '', 'Cancel Process', 'button--class-3', $order['order_ID']);
            } else if ( $order['processing_status'] === 'packed' ) {
                echo $component->button('', '', 'Track Order', 'button--class-2', $order['order_ID']);
            }
                
        ?>
    </div>

    <?php endif; ?>
</div>


<!-- ==================== -->
<script>
    elementsArray = document.querySelectorAll(".table-row");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            // location.href='pharmacy-view-pending-order?id='+elem.id;
            location.href='pharmacy-view-pending-order?id='+<?=$order['order_ID']?>;
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
</script>