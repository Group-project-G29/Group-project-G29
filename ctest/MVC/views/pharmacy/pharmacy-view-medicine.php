<?php
    use app\core\component\Component;
    $component=new Component();

?>
<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"name","search-bar--class1","Search by medicine name","searh");?>
    </div>
    <?php echo $component->button('new-medicine','','Add New Medicine','button--class-0  width-10','new-medicine');?>
    
</div>
<div class="table-container">
<table border="0">
    <tr>
        <th></th><th>Product</th><th>Unit Price</th><th>Availabilty</th><th></th>
    </tr>
    <?php foreach($medicines as $key=>$medicine): ?>
    <tr class="table-row">
        <td><img src=<?="./media/images/medicine/".$medicine['img']?> alt="No image"></td>
        <td><?=$medicine['name']." ".$medicine['strength']." ".$medicine['unit']?></td>
        <td><?=$medicine['unit_price']?></td>  
        <td><?=($medicine['availability']=="NA")?"Not Available":"Available"; ?></td>
        <td>
            <div>
                <?php echo $component->button('update','','Update','button--class-2',$medicine['med_ID']) ?>
                <?php echo $component->button('delete',' ','Delete','button--class-3',$medicine['med_ID']) ?>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>

</div>

<script>
    const btn=document.getElementById("new-medicine");
    btn.addEventListener('click',function(){
        location.href="handle-medicine";
    })
    elementsArray = document.querySelectorAll(".button--class-2");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='update-medicine?mod=update&id='+elem.id;
        });
    });
    elementsArray = document.querySelectorAll(".button--class-3");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='handle-medicine?cmd=delete&id='+elem.id;
        });
    });
</script>