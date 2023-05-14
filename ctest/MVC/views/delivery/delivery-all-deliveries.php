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
        <th class='hidden'>Delivery ID</th>
        <th>Name</th>
        <th>Contact</th>
        <th>Address</th>
        <th>City</th>
        <th>Postal Code</th>
        <th class='hidden'>Comment</th>
        <th>Completed Date</th>
        <th class='hidden'>Completed Time</th>
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
        <td><?=$delivery['completed_date']?></td>  
        <td class='hidden'><?=$delivery['completed_time']?></td>  
    </tr>
    <?php endforeach; ?>
    </table>

</div>