<?php
use app\core\component\Component;
$component=new Component();
?>
<section class="patient-my-detail">
    <?php if($patient['type']!='pediatric'):?>
        <div class="detail-container">
            <div class="update-main-info">
                <h2><?=$patient['name']?></h2>
                <h3><?="Account ID: ".$patient['patient_ID']?></h3>
            </div>
            <table border='0'>
                <tr><th></th><th></th></tr>
                <tr><td>NIC :</td><td><?=$patient['nic']?></td></tr>
                <tr><td>Age :</td><td><?=$patient['age']?></td></tr>
                <tr><td>Gender :</td><td><?=$patient['gender']?></td></tr>
                <tr><td>Contact Number :</td><td><?=$patient['contact']?></td></tr>
                <tr><td>Email :</td><td><?=$patient['email']?></td></tr>
                <tr><td>Address :</td><td><?=$patient['address']?></td></tr>
            </table>
        </div>
        <?=$component->button('btn','','Update','button--class-0','btn');?>
        <?php else:?>
    <?php endif;?>


</section>

<script>
    const btn=document.getElementById('btn');
    btn.addEventListener('click',()=>{
        location.href="patient-my-detail?mod=update"
    })
</script>