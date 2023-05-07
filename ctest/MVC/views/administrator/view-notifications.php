<?php
    use app\core\component\Component;
    $component=new Component();

//     var_dump($notifications);
// exit;

?>
<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"title","search-bar--class1","Search by Doctor ID","searh");?>
        <?php // echo $component->button('new-notification','','Add New notification','button--class-0  width-20','new-notification');?>
    </div>
</div>

<div class="table-container">
<table border="0">
    <tr>
    </tr>
    <?php foreach($notifications as $key=>$notification): ?>
        <?php if($notification['is_read']==1){ 
            $isRead = "no-read";
            } ?>
    <tr class="table-row <?=$isRead?>">
        <td class="add-dec">
           
            <div class="content" ><div><?=$notification['content']?></div></div>
            <div><font color="grey"><?=$notification['created_date_time']." ".substr($notification['created_time'],0,5).(($notification['created_time']>'12:00')?'PM':'AM')?></font><br></div>
            <div class="notification-btn">

                <?php if($notification['is_read']==1){ ?>
                    <div onclick="update(<?=$notification['noti_ID']?>)"><?php echo $component->button('update','','Mark as Read','button--class-2',$notification['noti_ID']) ?></div>
                <?php } ?>

                <div onclick="deletenoty(<?=$notification['noti_ID']?>)"><?php echo $component->button('update','','Mark as Read','button--class-3',$notification['noti_ID']) ?></div>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>

</div>

<script>
    const btn=document.getElementById("new-notification");
    btn.addEventListener('click',function(){
        location.href="admin-handle-notification";
    })
    
    function deletenoty(id){
        location.href='admin-notification?cmd=delete&id='+id;
    }
    function update(id){
        location.href='admin-notification?cmd=update&id='+id;
    }
</script>