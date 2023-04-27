<?php

use app\core\component\Component;

    var_dump($channeling);
    $component=new Component();
?>
    
<?php foreach($op_channelings as $channeling): ?>
    <div>
        <div>
            <?php var_dump($channeling)?>
        </div>
        <div>
            <?php if($channeling['status']=='Opened'):?>
                <?=$component->button('','','Close Channeling Session','close-button',$channeling['opened_channeling_ID']) ?>
            <?php else:?>
                <h3><?=$channeling['status']." "?>Channeling </h3>
                <div>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach;?>
<script>
    const btns=document.querySelectorAll(".close-button");
    btns.forEach((el)=>{
        el.addEventListener('click',()=>{
            location.href="schedule-channeling?spec=opened_channeling&cmd=close&id="+el.id;
        })
    })
</script>