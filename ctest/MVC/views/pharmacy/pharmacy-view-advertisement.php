<?php
    use app\core\component\Component;
    $component=new Component();

?>
<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"title","search-bar--class1","Search by advertisement title","searh");?>
    </div>
    <?php echo $component->button('new-advertisement','','Add New Advertisement','button--class-0  width-10','new-advertisement');?>
    
</div>
<div class="table-container">
<table border="0">
    <tr>
    </tr>
    <?php foreach($advertisements as $key=>$advertisement): ?>
    <tr class="table-row">
        <td class="row-img-col"><img class="row-img" src=<?="./media/images/advertisements/pharmacy/".$advertisement['img']?> alt="No image"></td>
        <td class="add-dec">
            <?=$advertisement['title']?><br><hr>
            <?=$advertisement['description']?><br>
            <?=$advertisement['remark']?><br>
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
        location.href="pharmacy-handle-advertisement";
    })
    elementsArray = document.querySelectorAll(".button--class-2");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='pharmacy-update-advertisement?mod=update&id='+elem.id;
        });
    });
    elementsArray = document.querySelectorAll(".button--class-3");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='pharmacy-handle-advertisement?cmd=delete&id='+elem.id;
        });
    });
</script>