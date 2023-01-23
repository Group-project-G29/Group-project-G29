<?php

use app\core\component\Component;
use app\models\Medicine;
    $component=new Component();
?>
<section>
    <div class="medicine-left-container">
        <?php  var_dump($cartItems) ?>
    </div>
    <div class="medicine-right-container">
        <?php foreach($medicines as $medicine):?>
            <div class="medicine-item">
                <img src=<?="./media/images/medicine/".$medicine['img']?>>
                <h3><?=$medicine['name']." ".$medicine['strength'] ?></h3>
                <h2><?="Rs.".$medicine['unit_price'] ?></h2>
                <input type="number" id=<?='"'."amount_".$medicine['med_ID'].'"'?>>
                <?= $component->button('','','add','button-class-3 add-medicine',$medicine['med_ID']); ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<script src="./media/js/main.js"></script>
<script>
    const addButtons=e('.add-medicine','classall');
    console.log(addButtons);
    addButtons.forEach((elem)=>{
        elem.addEventListener('click',()=>{
            let input_amount=e("amount_"+elem.id);
            let amount=input_amount.value;
            if(!amount){

            }
            else{
                location.href="patient-pharmacy?spec=medicine&cmd=add&item="+elem.id+"&amount="+amount;
            }
        })
    })
    
</script>