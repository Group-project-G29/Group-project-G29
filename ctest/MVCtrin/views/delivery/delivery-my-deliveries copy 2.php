<?php
    use app\core\component\Component;
    $component=new Component();
?>
<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"name","search-bar--class1","Search by delivery id, contact number","searh");?>
    </div>
</div>

<div class="table-container">
<table border="0">
    <?php foreach($deliveries as $key=>$delivery): ?>
    <tr class="table-row">
        <th>Order ID : <?=$delivery['delivery_ID']?></th>  
        <td><?=$delivery['name']?><br>
        <?=$delivery['contact']?><br>
        <?=$delivery['address']?><br>
        <?=$delivery['city']?><br>
        <?=$delivery['postal_code']?><br>
        <?php   
            if($delivery['comment']){
                echo $delivery['comment'];
            } else {
                echo "-";
            }
        ?><br>
        <?=$delivery['time_of_creation']?></td>

        <td><a href='delivery-view-delivery?id=' + <?=$delivery['delivery_ID']?> >More Details</a></td>
        <td><a>Pass Delivery</a></td>
    </tr>
    <?php endforeach; ?>
</table>

</div>

<script>

    // elementsArray = document.querySelectorAll(".table-row");
    // elementsArray.forEach(function(elem) {
    //     elem.addEventListener("click", function() {
    //         location.href='delivery-view-delivery?id='+elem.id;
    //     });
    // });

</script>