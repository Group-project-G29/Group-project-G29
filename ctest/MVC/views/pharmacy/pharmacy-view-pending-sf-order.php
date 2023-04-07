<?php
    /** @var $model \app\models\User */
    use app\core\component\Component;
    use \app\core\form\Form;
    $component=new Component();
    $total = 0;
    // var_dump($orders);
    // exit;
?>

<div class="detail">
    <h3>Patient Name : <?=$orders[0]['name']?></h3>
    <h3>Contact Number : <?=$orders[0]['contact']?></h3>
    <h3>Address : <?=$orders[0]['address']?></h3>
    <h3>Age : <?=$orders[0]['age']?></h3>
    <h3>Gender : <?=$orders[0]['gender']?></h3>
    <hr>
    <h3>Order ID : <?=$orders[0]['order_ID']?></h3>
    <h3>Ordered Date & Time :<?=$orders[0]['created_date']?> <?=$orders[0]['created_time']?></h3>
    <h3>Pickup Status : <?=$orders[0]['pickup_status']?></h3>
</div>



<div class="table-container">

    <img src="https://th.bing.com/th/id/OIP.JJIj5kC8a8c6vvYoxEBuUwHaH0?pid=ImgDet&rs=1" >
    <!-- <img src="<?=$orders[0]["location"]?>" alt="Prescription here. This is an image from web."> -->
    <!-- <?php echo($orders[0]["location"]) ?><br> -->

    <?php
        // echo '<img src="' . $orders[0]["location"] . '"alt="Prescription here. This is an image from web."/>';
        ?>
</div>


<section class="form-body" style="padding-bottom:100px">
    
<div class="main_title">
    <h2 class="fs-150 fc-color--dark">Enter the Medicines</h2>
</div>
<?php if (isset($curr_pres_orders)): ?>
    <div class="table-container">
        <table border="0">
            <?php foreach($curr_pres_orders as $key=>$curr_pres_order): ?>
            <tr class="table-row">
                <td><?=$curr_pres_order['med_ID']?></td>  
                <td><?=$curr_pres_order['amount']?></td>  
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php endif; ?>

<div class="form-body-fields">
    
<!-- component and call when click the add button -->
<div class="new-order-add-item"> 
    <?php
    $component=new Component();
    $form=Form::begin('pharmacy-new-order-items?cmd=nr&presid='.$orders[0]["prescription_ID"],'post');
    ?> 
        <div class="new-order-add-item-col">
            <?php echo $form->editableselect('medicine','Medicine Name','field', ['panadol'=>48, 'vitamin-C'=>50]) ?>
        </div>
        <div class="new-order-add-item-col">
            <?php echo $form->editableselect('amount','Amount','field',[]) ?>
        </div>
        <div class="new-order-add-item-col">
            <?php echo $component->button("add-another","submit","Add Another","button--class-00","add-another")?>
            <!-- <a class="add-more" >+ Add More Item</a> -->
        </div>
    </div>
    
    <div><?php echo $component->button("add-order","submit","Confirm Medicines","button--class-0","add-order")?></div>
    
</div>
<?php Form::end() ?>   
    


<script>
    // elementsArray1 = document.querySelectorAll("add-more");
    // elementsArray1.forEach(function(elem) {
    //     elem.addEventListener("click", function() {
    //         location.href='delivery-view-delivery?id='+elem.id;
    //     });
    // });

    // const btn1=document.getElementById("take-order");
    // btn1.addEventListener('click',function(){
    //     location.href="pharmacy-take-pending-order?id="+<?=$order['order_ID']?>; //get
    // })
    
</script>