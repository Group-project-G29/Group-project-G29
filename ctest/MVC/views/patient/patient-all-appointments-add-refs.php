<?php
    use app\core\component\Component;
    use app\core\form\Form;

    $component=new Component();
    $popup=$component->popup("Are you sure you want to delete the channeling session","popup-value","popup--class-1","yes");
    echo $popup;
?>
<div class="bg">

</div>
<div class="referral-popup" id="popup-main">
    <div class="referral-popup-wrapper">
       
        <h3 class="fs-50">Add any soft copy referal here. Please consider that referal should be clear and valid.</h3>
        <?php $form=Form::begin("handle-referral?cmd=add&id=".$appointment->appointment_ID,'post');?>
        <?= $form->field($model,'name','Refarrel','field-input--class1 flex','file');?>
        <?=$component->button("Done","submit","Done","button--class-0",$appointment->appointment_ID);?>
        <?php $form=Form::end();?>
        <?php foreach($referrals as $referral): ?>
            <a href=<?="'"."handle-documentation?spec=referral"."&mod=view&id=".$referral['ref_ID']."'" ?>><?="Referral-.".$referral['ref_ID']?></a><a href=<?="handle-documentation?spec=referral&cmd=delete&id=".$referral['ref_ID']?>>X</a>
        <?php endforeach; ?>
    </div>        
</div>
<div class="filter-holder">
    <?php 
        echo $component->filtersortby('','',[],['Speciality'=>'speciality','Doctor'=>'Doctor']);
    ?>
</div>
<div class="table-container">
<?php if($channelings):?>
<table border="0">
    <tr>
        <th>Clinic</th><th>Doctor</th><th>Date</th><th>Time</th><th></th><th></th>
    </tr>
        <?php foreach($channelings as $key=>$channeling): ?>
        <tr class="table-row">
            
            <td><?=$channeling['speciality']?></td>
            <td><?=$channeling['name']?></td>  
            <td><?=$channeling['channeling_date']?></td>
            <td><?=$channeling['time']?></td>  
            <td>
                <div>
                    <?php echo $component->button('delete',' ','Cancel Appointment','button--class-3 btn-del',$channeling['appointment_ID']) ?>
                </div>
            </td>
            <td>
                <div>
                    <?php echo $component->button('referral',' ','Change Referrals','button--class-3 btn-chn',$channeling['appointment_ID']) ?>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <div class="empty-container">
            Empty
        </div>
    <?php endif; ?>
</div>

<script>
    elementsArray = document.querySelectorAll(".btn-del");
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
   
</script>
