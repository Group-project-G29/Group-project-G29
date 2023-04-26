<?php
    use app\core\component\Component;
    $component=new Component();
    // var_dump($advertisements);exit;
?>
<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"title","search-bar--class1","Search by advertisement title","searh");?>
    </div>
    <?php echo $component->button('new-advertisement','','Add New advertisement','button--class-0  width-10','new-advertisement');?>
    
</div>
<div class="table-container">
<table border="0">
    
    <?php foreach($advertisements as $key=>$advertisement): ?>
    <tr class="table-row" style="width: 95vw;">
        <td><img src=<?="./media/images/advertisements/".$advertisement["img"] ?> alt="Loading image" style="height: 250px; width: 300px;" ></td>
        <td style="width: 40vw; text-align:start;"><b style="font-size: 1.2rem; line-height: 4vh;"><?=$advertisement['title']?></b><br>
            <?=$advertisement['description']?></td>
        <td><?php echo $component->button('update','','Update','button--class-2',$advertisement['ad_ID']) ?></td>
        <td><?php echo $component->button('delete',' ','Delete','button--class-3',$advertisement['ad_ID']) ?></td>  
        <td></td>    
         
    </tr>
    <?php endforeach; ?>
    </table>

</div>

<script>
    const btn=document.getElementById("new-advertisement");
    btn.addEventListener('click',function(){
        location.href="admin-handle-advertisement";
    })
    elementsArray = document.querySelectorAll(".button--class-2");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='admin-update-advertisement?mod=update&id='+elem.id;
        });
    });
    elementsArray = document.querySelectorAll(".button--class-3");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='admin-handle-advertisement?cmd=delete&id='+elem.id;
        });
    });
</script>