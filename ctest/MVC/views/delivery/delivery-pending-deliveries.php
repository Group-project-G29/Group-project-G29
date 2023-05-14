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
        <tr>
            <th class='hidden'>Delivery ID</th>
            <th>Name</th>
            <th>Contact</th>
            <th>Address</th>
            <th>City</th>
            <th>Postal Code</th>
            <th class='hidden'>Comment</th>
            <th class='hidden'>Created Date Time</th>
            <th></th>
        </tr>
        <?php foreach($deliveries as $key=>$delivery): ?>
        <tr class="table-row" id=<?=$delivery['delivery_ID']?> >
            <td class='hidden'><?=$delivery['delivery_ID']?></td>  
            <td><?=$delivery['name']?></td>  
            <td><?=$delivery['contact']?></td>  
            <td><?=$delivery['address']?></td>  
            <td><?=$delivery['city']?></td>  
            <td><?=$delivery['postal_code']?></td>  
            <td class='hidden'>
                <?php   
                    if($delivery['comment']){
                        echo $delivery['comment'];
                    } else {
                        echo "-";
                    }
                ?>
            </td>  
            <td class='hidden'><?=$delivery['time_of_creation']?></td>
            <td><a class='get-delivery' id=<?=$delivery['delivery_ID']?>><img src="./media/anim_icons/get.png"></a></td>
        </tr>
        <?php endforeach; ?>
    </table>

</div>

<script>
    
    elementsArray2 = document.querySelectorAll(".get-delivery");
    elementsArray2.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='delivery-get-delivery?id='+elem.id;
        });
    });
    
    // elementsArray1 = document.querySelectorAll(".more-details");
    // elementsArray1.forEach(function(elem) {
    //     elem.addEventListener("click", function() {
    //         location.href='delivery-view-delivery?id='+elem.id;
    //     });
    // });
</script>