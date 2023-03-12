
<?php

    use app\core\component\Component;
    use app\core\form\Form;
    $component=new Component();
   

?>
<div class="bg">

</div>
 <div class="referral-popup" id="popup-main">
    <div class="referral-popup-wrapper">
        <h3>Your Queue No :<?=$appointment->queue_no?></h3>
        <div class="flex">
            <h4>Date:<?=$channeling['channeling_date']?></h4>
            <h4>Time:<?=$channeling['time']?></h4>
        </div>
        <h3 class="fs-50">Add any soft copy referal here. Please consider that referal should be clear and valid.</h3>
        <?php $form=Form::begin("handle-referral?cmd=add&id=".$appointment->appointment_ID,'post');?>
        <?= $form->field($model,'name','Refarrel','field-input--class1 flex','file');?>
        <?=$component->button("Done","submit","Done","button--class-0",$appointment->appointment_ID);?>
        <?php $form=Form::end();?>
    </div>        
</div>

<div class="appointment-container ">
        <?php foreach($channelings as $key=>$value): ?>
            <div class="item">
                <div class="item--left">
                    <div>
                        <img src=<?="./media/images/emp-profile-pictures/".$value['img']?>>
                    </div>
                    <div class="about-info">
                        <h3>DR. <?=$value['name']?></h3>
                        <h3><?=$value['speciality']?></h3>
                        <p><?=$value['description']?></p>
                        
                    </div>
                </div>
                <div class="item--right">
                    <div class="channeling-vital-info">
                        <h3>Time :<?=$value['time']?></h3>
                        <h3>Date :<?=$value['channeling_date']?></h3>
                        <h3>Fee :LKR <?=$value['fee']?></h3>
                    </div>
                    <?= $component->button('add-ppointment','submit','+ Add Appointment','button--class-1',$value['opened_channeling_ID']);?>
                </div>
            </div>
            
        <?php endforeach; ?>
  
</div>

<script>
       
        const bg=document.querySelector(".bg");
        popup_button=document.getElementById("done");
        bg.classList.add("background");
        popup.style.display="flex";
        url="";
        elementsArray = document.querySelectorAll(".button--class-1");
        
        elementsArray.forEach(function(elem) {
            elem.addEventListener("click", function() {
                url='patient-appointment?mod=add&id='+elem.id;
                div.style.display="flex";
                bg.classList.add("background");
            });
        });
     
   </script>