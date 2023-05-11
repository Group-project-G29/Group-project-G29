<?php
    use app\core\component\Component;
    $component=new Component();
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
            </tr>
        
            <?php foreach($orders as $key=>$order): ?>
                <?php if($order['processing_status']=='pickedup'): ?>  
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
                            <td><?= ucfirst($order['pickup_status']) ?></td> 
                            <!-- deliveryd by -> delivery rider -->
                            <td><?=$order['created_date']?></td> 
                            <td><?= $timeModel->time_format($order['created_time']) ?></td> 
                        </tr>
                <?php elseif($order['processing_status']=='deleted'): ?> 
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
                            <td><?= ucfirst($order['pickup_status']) ?></td> 
                            <td><?=$order['created_date']?></td> 
                            <td><?= $timeModel->time_format($order['created_time']) ?></td> 
                        </tr>  
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <br><br><br><h2>No Current Finished Orders</h2>
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