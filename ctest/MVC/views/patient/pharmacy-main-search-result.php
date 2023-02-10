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
                        <img src=<?="./media/images/medicine/".$medicine['img']?>>
                        <h3><?=$medicine['name']." ".$medicine['strength'] ?></h3>
                        <h2><?="Rs.".$medicine['unit_price'] ?></h2>
                        <div>
                            <label>Amount :</label>
                            <input type="number" id=<?='"'."amount_".$medicine['med_ID'].'"'?>>
                        </div>
                        <?= $component->button('','','add','button-class-3 add-medicine',$medicine['med_ID']); ?>
                    </div>
                <?php else : ?>
                    <div class="medicine-item">
                        <img src=<?="./media/images/medicine/".$medicine['img']?>>
                        <h3><?=$medicine['name']." ".$medicine['strength'] ?></h3>
                        <h2><?="Rs.".$medicine['unit_price'] ?></h2>
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
    const updateButtons=e('.update-buttons','classall');
    
    updateButtons.forEach((elem)=>{
        elem.addEventListener('click',()=>{
            let element=(""+elem.id).split("_")[1];
            let input_amount=e("amount2_"+element);
            let amount=input_amount.value;
            if(!amount){

            }
            else{
                location.href="patient-pharmacy?spec=medicine&cmd=add&item="+element+"&amount="+amount;
            }
        })
    })
    const paymentbtn=e('proceed-to-payment');
    paymentbtn.addEventListener('click',()=>{
        location.href="patient-medicine-order?spec=order&mod=view";
    })
    // const cart=e('.cart','class');
    // const cartBody=e('.');
</script>
