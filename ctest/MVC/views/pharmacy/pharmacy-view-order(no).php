<?php
    use app\core\component\Component;
    $component=new Component();

?>
<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"name","search-bar--class1","Search by advertisement name","searh");?>
    </div>
    <?php echo $component->button('new-advertisement','','Add New advertisement','button--class-0  width-10','new-advertisement');?>
    
</div>
<div class="table-container">
<table border="0">
    <tr>
        <th></th><th>Product</th><th>Unit Price</th><th>Availabilty</th><th></th>
    </tr>
    <?php foreach($advertisements as $key=>$advertisement): ?>
    <tr class="table-row">
        <td><img src=<?="./media/images/advertisement/".$advertisement['img']?> alt="No image"></td>
        <td><?=$advertisement['name']." ".$advertisement['strength']." ".$advertisement['unit']?></td>
        <td><?=$advertisement['unit_price']?></td>  
        <td><?=($advertisement['availability']=="NA")?"Not Available":"Available"; ?></td>
        <td>
            <div>
                <?php echo $component->button('update','','Update','button--class-2',$advertisement['ad_ID']) ?>
                <?php echo $component->button('delete',' ','Delete','button--class-3',$advertisement['ad_ID']) ?>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>

</div>

<script>
    const btn=document.getElementById("new-advertisement");
    btn.addEventListener('click',function(){
        location.href="handle-advertisement";
    })
    elementsArray = document.querySelectorAll(".button--class-2");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='update-advertisement?mod=update&id='+elem.id;
        });
    });
    elementsArray = document.querySelectorAll(".button--class-3");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='handle-advertisement?cmd=delete&id='+elem.id;
        });
    });
</script>