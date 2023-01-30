<?php
    use app\core\component\Component;
    $component=new Component();
    $total = 0;
    // var_dump($orders);
    //     exit;

?>
   
<div class="table-container">
<table border="0">
    <tr>
        <th>Medicine ID</th><th>Medicine Name</th><th>Medicine Strength</th><th>Price per unit</th><th>Amount</th><th>Total Price</th>
    </tr>
    <?php foreach($orders as $key=>$order): ?>
    <tr class="table-row">
        <td><?=$order['med_ID']?></td>
        <td><?=$order['name']?></td> 
        <td><?=$order['strength']?></td> 
        <td><?=$order['unit_price']?></td> 
        <td><?=$order['amount']?></td> 
        <td><?=$order['unit_price']*$order['amount']?></td> 
        <?php $total = $total + $order['unit_price']*$order['amount'] ?>
        <?php endforeach; ?>
    </tr>
    </table>
</div>
    <h1>Total Price : <?=$total?></h1>

<div class='upper-container'>
    <?php echo $component->button('take-order','','Take Order','button--class-0  width-10','take-order');?>
</div>


<!-- ==================== -->
<script>
    const btn4=document.getElementById("take-order");
    btn4.addEventListener('click',function(){
        location.href="/ctest/pharmacy-orders-processing"; //get
    })
</script>