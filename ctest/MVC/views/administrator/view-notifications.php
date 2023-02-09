<?php
    use app\core\component\Component;
    $component=new Component();

//     var_dump($notifications);
// exit;

?>
<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"title","search-bar--class1","Search by Doctor ID","searh");?>
    </div>
</div>
<div class="table-container">
<table border="0">
    <tr>
    </tr>
    <?php foreach($notifications as $key=>$notification): ?>
    <tr class="table-row">
        <td class="add-dec">
            <?=$notification['doctor']?> on <?=$notification['created_date_time']?><br>
            <?=$notification['content']?><br>
            <div>
                <?php echo $component->button('update','','Mark as Read','button--class-2',$notification['noti_ID']) ?>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>

</div>

<script>
    const btn=document.getElementById("new-notification");
    btn.addEventListener('click',function(){
        location.href="pharmacy-handle-notification";
    })
    elementsArray = document.querySelectorAll(".button--class-2");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='pharmacy-update-notification?mod=update&id='+elem.id;
        });
    });
    elementsArray = document.querySelectorAll(".button--class-3");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='pharmacy-handle-notification?cmd=delete&id='+elem.id;
        });
    });
</script>