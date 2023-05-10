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

<div class="notification-container">

    
    <?php foreach($notifications as $key=>$notification): ?>
        <?php 
            if($notification['is_read']==1){ 
                $isRead = "no-read";
            }else{
                $isRead = "";
            }

            $nic = $notification['doctor'];
            $senderName = $employeeModel->customFetchAll("SELECT name FROM `employee` WHERE nic=$nic;");
        ?>
        <div class="notification-div <?=$isRead?>" onclick="update(<?=$notification['noti_ID']?>)">
            
            <div class="doctor-name">Sender : <?=$senderName[0]['name']?></div>
            <div class="content"><?=$notification['content']?></div>

            <div class="time-and-btn">
                <div class="content-time"><?=$notification['created_date_time']?></div>
                <div class="notification-btn">
                    
                    <!-- <?php if($notification['is_read']==1){ ?>
                        <div onclick="update(<?=$notification['noti_ID']?>)"><?php echo $component->button('update','','Mark as Read','button--class-2',$notification['noti_ID']) ?></div>
                    <?php } ?> -->
                        
                    <div onclick="deletenoty(<?=$notification['noti_ID']?>)"><?php echo $component->button('update','','Delete','button--class-3',$notification['noti_ID']) ?></div>
                </div>
            </div>

        </div>
    <?php endforeach; ?>


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