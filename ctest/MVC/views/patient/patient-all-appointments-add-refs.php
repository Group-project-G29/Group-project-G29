<?php
    use app\core\component\Component;
    use app\core\form\Form;
use app\models\Appointment;

    $component=new Component();
    $popup=$component->popup("Are you sure you want to delete the channeling session","popup-value","popup--class-1","yes");
    echo $popup;
    $appointmentModel=new Appointment();
?>
<div class="background">

</div>
<div class="referral-popup" id="popup-main">
    <div class="referral-popup-wrapper">
       
        <h3 class="fs-50">Add any soft copy referal here. Please consider that referal should be clear and valid.</h3>
        <?php $form=Form::begin("handle-referral?cmd=add&id=".$appointment->appointment_ID,'post');?>
        <div class="small-column">
        <div class="flex">
            <?= $form->spanfield($model,'name','','','file');?>
            <?=$component->button("Done","submit","Add Referral","button--class-0",$appointment->appointment_ID);?>
        </div>
        <div>
        <?php $form=Form::end();?>
        <?php foreach($referrals as $referral): ?>
            <div class="flex variable-small-container">
                <a href=<?="'"."handle-documentation?spec=referral"."&mod=view&id=".$referral['ref_ID']."'" ?>><?="Referral-.".$referral['ref_ID']?></a><a href=<?="handle-documentation?spec=referral&cmd=delete&id=".$referral['ref_ID']?>>X</a>
            </div>
        <?php endforeach; ?>
        </div>
        </div>
    </div>        
</div>
<!-- <div class="filter-holder">
    <?php 
        echo $component->filtersortby('','',[],['Speciality'=>'speciality','Doctor'=>'Doctor']);
    ?>
</div> -->
<div class="appointment-container">
<?php if($channelings):?>
    
        <?php foreach($channelings as $key=>$channeling): ?>
            <?php if($appointmentModel->isInPass($channeling['appointment_ID'])): ?>
                <?php if($channeling['type']=='consultation'):?>
                <div class="btn-content">
                <div class="patient-appointment-tile--1">
                    <img src="media/images/common/delete.png" id=<?='"'.$channeling['appointment_ID'].'"'?> class="image">
                    <h3><?='Doctor :'.$channeling['name']?></h3>  
                    <h3><?='Speciality :'.$channeling['speciality']?></h3>
                    <h4><?='Channeling date :'.$channeling['channeling_date']?></h4>
                    <h4><?='Channeling time :'.$channeling['time']?></h4> 
                    
                </div>
                <div>
                    <?php echo $component->button('referral',' ','Change Referrals','button--class-app',$channeling['appointment_ID']) ?>
                </div>
                </div>
                <?php else:?>
                    <div class="btn-content">
                     <div class="patient-appointment-tile--2">
                    <img src="media/images/common/delete.png" id=<?='"'.$channeling['appointment_ID'].'"'?>  class="image">
                    <h3><?='Time :'.$channeling['name']?></h3>  
                    <h3><?='Speciality :'.$channeling['speciality']?></h3>
                    <t4><?='Channeling date :'.$channeling['channeling_date']?></t4>
                    <t4><?='Channeling time :'.$channeling['time']?></t4> 
                        <div>
                            <?php echo $component->button('referral',' ','Change Referrals','button--class-4',$channeling['appointment_ID']) ?>
                        </div>
                    
                </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-container">
            Empty
        </div>
    <?php endif; ?>
</div>

<script>
    elementsArray = document.querySelectorAll(".image");
    const bgo=document.querySelector('.background');
    bgo.addEventListener('click',()=>{
        location.href="patient-dashboard?spec=appointments";
    })
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            url='handle-appointment?cmd=delete&id='+elem.id;
            div.style.display="flex";
            bg.classList.add("background");
        });
    });
    yes.addEventListener("click",()=>{
        location.href=url;
    })
    const updates=document.querySelectorAll('.button--class-app');
    console.log(updates);
    updates.forEach(function(elem){
        elem.addEventListener('click',()=>{
            location.href="patient-appointment?spec=referral&mod=update&id="+elem.id;
        })
    })
</script>
