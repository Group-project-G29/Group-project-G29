<?php
    use app\core\component\Component;
    $component=new Component();
// var_dump($deliveries);
// exit;

?>
<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"name","search-bar--class1","Search by delivery id, contact number","searh");?>
    </div>
</div>

<div class="table-container">
<table border="0">
    <tr>
        <th>Delivery ID</th>
        <th>Name</th>
        <th>Contact</th>
        <th>Address</th>
        <th>City</th>
        <th>Postal Code</th>
        <th>Comment</th>
        <th>Created Date Time</th>
    </tr>
    <?php foreach($deliveries as $key=>$delivery): ?>
    <tr class="table-row" id=<?=$delivery['delivery_ID']?> >
        <td><?=$delivery['delivery_ID']?></td>  
        <td><?=$delivery['name']?></td>  
        <td><?=$delivery['contact']?></td>  
        <td><?=$delivery['address']?></td>  
        <td><?=$delivery['city']?></td>  
        <td><?=$delivery['postal_code']?></td>  
        <td>
            <?php   
                if($delivery['comment']){
                    echo $delivery['comment'];
                } else {
                    echo "-";
                }
            ?>
        </td>  
        <td><?=$delivery['time_of_creation']?></td>
    </tr>
    <?php endforeach; ?>
    </table>

</div>

<script>
    // const btn=document.getElementById("new-delivery");
    // btn.addEventListener('click',function(){
    //     location.href="handle-delivery";
    // })
    elementsArray = document.queryorAll(".table-row");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='delivery-view-delivery?id='+elem.id;
        });
    });
    // elementsArray = document.querySelectorAll(".button--class-3");
    // elementsArray.forEach(function(elem) {
    //     elem.addEventListener("click", function() {
    //         location.href='handle-delivery?cmd=delete&id='+elem.id;
    //     });
    // });
</script>