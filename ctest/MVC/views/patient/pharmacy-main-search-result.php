<?php

use app\core\Application;
use app\core\component\Component;
use app\core\form\Form;
use app\models\Medicine;
    $component=new Component();
    $form=new Form();
    $model=new Medicine();
?>
<script src="./media/js/main.js">

</script>
<section class="medicine-main-container">
    <div class="medicine-left-container">
       
    </div>
    <div class="medicine-right-container">
    <?php
        use app\models\OpenedChanneling;
        $openedChanneling=new OpenedChanneling();


        $component=new Component();
        
        ?>        
        <div class="medicine-right-subcontainer">
            <?php foreach($medicines as $medicine):?>
                <?php if($model->checkStock($medicine['med_ID'])): ?>  <!--check whether medicine in stock or out-->
                    <div class="medicine-item">
                        <?php if($medicine['img']): ?>
                            <img src=<?="./media/images/medicine/".$medicine['img']?>>
                        <?php else:?>
                            <img src=<?="./media/images/medicine/"."default.jpg"?>>
                        <?php endif;?>
                        <h2><?=$medicine['name']." ".$medicine['strength'] ?></h2>
                        <h3><?="LKR ".number_format($medicine['unit_price'],'2','.','') ?></h3>
                        <?php if($model->isResctricted($medicine['med_ID'])): ?>
                        <div class="amount-item">
                            <label>Amount :</label>
                            <input type="number" id=<?='"'."amount_".$medicine['med_ID'].'"'?>>
                        </div>
                            <?= $component->button('','','add','button--class-add-cart add-medicine',$medicine['med_ID']); ?>
                        <?php else:?>
                            <?="Needs Prescription to buy"?>
                        <?php endif;?>
                    </div>
                <?php else : ?>
                    <div class="medicine-item">
                        <?php if($medicine['img']): ?>
                            <img src=<?="./media/images/medicine/".$medicine['img']?>>
                        <?php else:?>
                            <img src=<?="./media/images/medicine/"."default.jpg"?>>
                        <?php endif;?>
                        <h2><?=$medicine['name']." ".$medicine['strength'] ?></h2>
                        <h3><?="LKR ".$medicine['unit_price'] ?></h3>
                        <h3 color="red">Out of Stock</h3>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
        </div>
    </div>
</section>
<script src="./media/js/main.js"></script>
<script>
    const addButtons=e('.add-medicine','classall');
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
   
    // const cart=e('.cart','class');
    // const cartBody=e('.');
</script>
