<?php

use app\core\component\Component;
use app\core\form\Form;
use app\models\Medicine;

    $form=new Form();
    $component=new Component();
    $val="delivery";
    $medicineModel=new Medicine();

?>
<section class="pharmacy-order-container">
  
    <div class="pharmacy-main-form">
        <?php $form=Form::begin("patient-medicine-order?spec=order&cmd=complete",'post');?> 
        <div class="reg-body_fields">
            <?php echo $form->field($delivery,'name','Name of the Recipient*','field','text') ?>
            <?php echo $form->field($delivery,'contact','Recipient Contact*','field','text') ?>
            Delivery<input type='radio' class="delivery-rbtn" id='delivery' name='pickup_status' value='delivery' checked>
            Pickup<input type='radio' id='pickup' class="delivery-rbtn"  name='pickup_status' value='pickup'>
            <div class="nothing">
                <?php echo $form->field($delivery,'address','Recipient Address*','field','text') ?>
                <?php echo $form->spanselect($delivery,'city','City*','field',['select'=>'','Ciyathra'=>'Ciyathra','Howitz'=>'Howitz'],'')?>
                <?php echo $form->spanselect($delivery,'postal_code','Postal Code*','field',['select'=>'','20290-D'=>'20290','40034-K'=>'40034'],'')?>
                <?php echo $form->textarea($delivery,'comment','comment','Any Delivery Instruction',5,20); ?>
            </div>
            <div class="button-container"><input class="button--class-1" style="margin-bottom:3vh;" type="submit" value="Complete Order"></div>
        </div>
    
    
        <?php Form::end() ?>   

    </div>

</section>
<script src="./media/js/main.js">

</script>
<script>
    radioBtn=document.querySelectorAll('.delivery-rbtn');

    formDiv=document.querySelector(".nothing");
    
    function hide(element,hideClass='hide',visibleClass='field'){
      
        element.classList.add(hideClass);
    }
    function visible(element,hideClass='hide',visibleClass='field'){
        element.classList.remove(hideClass);
        
    }
    pickupBtn=document.getElementById('pickup');
    deliveryBtn=document.getElementById('delivery');
    pickupBtn.addEventListener('change',()=>{
        hide(formDiv);
    })
    deliveryBtn.addEventListener('change',()=>{
        visible(formDiv);
    })
    
</script>
