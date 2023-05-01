<?php
    use app\core\component\Component;
    $component=new Component();

?>

<p class="navigation-text-line-p"> 
    <a class="navigation-text-line-link" href="/ctest/pharmacy-orders-pending">orders</a>/
    <a class="navigation-text-line-link">processing orders</a> 
</p>

<div class='upper-container'>
    <?php echo $component->button('pending','','Pending Orders','button--class-0-deactive  width-10','pending');?>
    <?php echo $component->button('processing','','Processing Orders','button--class-0-active  width-10','processing');?>
    <?php echo $component->button('delivering','','Packed Orders','button--class-0-deactive  width-10','delivering');?>
</div>

<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"name","search-bar--class1","Search by order ID, patient 1D","searh");?>
    </div>
    <?php 
    echo $component->button('new-order','','Add New Order','button--class-0  width-10','new-order');
    ?>
</div>
   
<div class="table-container">
    <?php if($orders): ?>
        <table border="0">
            <tr>
                <th>Order ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
            
            <?php foreach($orders as $key=>$order): ?>
                <tr class="table-row" id=<?=$order['order_ID']?> >
                    <td><?=$order['order_ID']?></td>
                    <td><?=$order['name']?></td> 
                    <td><?=$order['contact']?></td> 
                    <td><?=$order['created_date']?></td> 
                    <td><?=$order['created_time']?></td> 
                </tr>
            <?php endforeach; ?>
        </table>

    <?php else: ?>
        <br><br><br><h2>No Current Processing Orders</h2>
    <?php endif; ?>
</div>


<!-- ==================== -->
<script>

    elementsArray = document.querySelectorAll(".table-row");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='pharmacy-view-processing-order?id='+elem.id; 
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