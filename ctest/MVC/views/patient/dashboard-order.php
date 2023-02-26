
<?php
    use app\core\component\Component;
    $component=new Component();
?>

<div class="filter-holder">
    <?php 
        echo $component->filtersortby('','',[],['Speciality'=>'speciality','Doctor'=>'Doctor']);
    ?>
</div>
<div class="table-container">
<?php if($orders):?>
<table border="0">
    <tr>
        <th>Order</th><th>Date</th><th>Time</th><th>Payment Status</th><th>Order Status</th>
    </tr>

        <?php foreach($orders as $key=>$order): ?>
        <tr class="table-row" id=<?=$order['order_ID'] ?>>
            
            <td><?=$order['pickup_status']." order-".$order['order_ID']?></td>
            <td><?=$order['created_date']?></td>  
            <td><?=$order['created_time']?></td>
            <td><?=$order['payment_status']?></td>  
            <td><?=$order['processing_status']?></td>  
           
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <div class="empty-container">
            Empty
        </div>
    <?php endif; ?>
</div>

<script>
    elementsArray = document.querySelectorAll(".table-row");
    elementsArray.forEach((elem)=>{
        elem.addEventListener('click',()=>{
            location.href="patient-dashboard?spec=orders&mod=view&id="+elem.id;
        })
    })
  
</script> 