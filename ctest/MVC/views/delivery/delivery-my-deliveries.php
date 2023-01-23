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
    <?php echo $component->button('new-delivery','','Add New delivery','button--class-0  width-10','new-delivery');?>
    
</div>
<div class="table-container">
<table border="0">
    <tr>
        <th>Delivery ID</th><th>Date</th><th>Time</th><th>Contact</th><th>Address</th><th>Postal Code</th><th></th>
    </tr>
    <?php foreach($deliveries as $key=>$delivery): ?>
    <tr class="table-row">
        <td><?=$delivery['delivery_ID']?></td>  
        <td><?=$delivery['date']?></td>  
        <td><?=$delivery['time']?></td>  
        <td><?=$delivery['contact']?></td>  
        <td><?=$delivery['address']?></td>  
        <td><?=$delivery['postal_code']?></td>  
        <td>
            <div>
                <?php echo $component->button('update','','View','button--class-2',$delivery['delivery_ID']) ?>
                <?php echo $component->button('delete',' ','Finish','button--class-3',$delivery['delivery_ID']) ?>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>

</div>

<script>
    const btn=document.getElementById("new-delivery");
    btn.addEventListener('click',function(){
        location.href="handle-delivery";
    })
    elementsArray = document.querySelectorAll(".button--class-2");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='update-delivery?mod=update&id='+elem.id;
        });
    });
    elementsArray = document.querySelectorAll(".button--class-3");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='handle-delivery?cmd=delete&id='+elem.id;
        });
    });
</script>