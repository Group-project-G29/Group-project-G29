<?php
    use app\core\component\Component;
    $component=new Component();
// var_dump($orders);
// var_dump($order_types);
// exit;

?>

<p class="navigation-text-line-p"> 
    <a class="navigation-text-line-link" href="/ctest/pharmacy-orders-pending">orders</a>/
    <a class="navigation-text-line-link">pending orders</a> 
</p>

<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"name","search-bar--class1","Search by order ID, patient 1D","searh");?>
    </div>

</div>
   
<div class="table-container">
<table border="0">
    <tr>
        <th>Order ID</th>
        <th>Order Type</th>
        <th>Name</th>
        <th>Contact</th>
        <th>Date</th>
        <th>Time</th>
    </tr>
    <?php if($orders): ?>
        
        <?php foreach($orders as $key=>$order): ?>
            <?php if($order['processing_status']=='pickedup'): ?>  
                    <tr class="table-row" id=<?=$order['order_ID']?> >
                        <td><?=$order['order_ID']?></td>
                        <td><?=$order_types[$key]?></td>
                        <td><?=$order['name']?></td> 
                        <td><?=$order['contact']?></td> 
                        <td><?=$order['created_date']?></td> 
                        <td><?=$order['created_time']?></td> 
                    </tr>
            <?php elseif($order['processing_status']=='deleted'): ?> 
                    <tr class="table-row_red" id=<?=$order['order_ID']?> >
                        <td><?=$order['order_ID']?></td>
                        <td><?=$order_types[$key]?></td>
                        <td><?=$order['name']?></td> 
                        <td><?=$order['contact']?></td> 
                        <td><?=$order['created_date']?></td> 
                        <td><?=$order['created_time']?></td> 
                    </tr>  
            <?php endif; ?>
        <?php endforeach; ?>
    </table>

    <?php endif; ?>
</div>


<!-- ==================== -->
<script>
    elementsArray = document.querySelectorAll(".table-row");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='pharmacy-view-previous-order?id='+elem.id; 
        });
    });
</script>