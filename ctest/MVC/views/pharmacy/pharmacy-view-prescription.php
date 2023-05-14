<?php
    use app\core\component\Component;
    use \app\core\form\Form;
    $component=new Component();
    // var_dump($prescription);exit;
?>

<div class="detail">
    <h3>Order ID : <?=$prescription[0]['order_ID']?></h3>
    <h3>Date :<?=$prescription[0]['created_date']?></h3>
    <h3>Time :<?=$prescription[0]['created_time']?></h3>
    <h3>Pickup Status : <?=$prescription[0]['pickup_status']?></h3>
    <h3>Payment Status : <?=$prescription[0]['payment_status']?></h3>
    <hr>
    <h3>Patient Name : <?=$prescription[0]['name']?></h3>
    <h3>Contact Number : <?=$prescription[0]['contact']?></h3>
    <h3>Address : <?=$prescription[0]['address']?></h3>
</div>

<center>
    <div class="view-prescription-separate" >
        <img src=<?="./media//patient/prescriptions/".$prescription[0]['location']?> >
    </div>
</center>

<!-- get the image with the location -->