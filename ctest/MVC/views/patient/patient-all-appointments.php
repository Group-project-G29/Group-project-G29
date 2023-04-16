<?php
    use app\core\component\Component;
    use app\core\form\Form;
use app\models\Appointment;

    $component=new Component();
    $popup=$component->popup("Are you sure you want to delete the channeling session","popup-value","popup--class-1","yes");
    echo $popup;
    $appointmentModel=new Appointment();
?>

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
            <?php if($appointmentModel->isInPass($channeling['appointment_ID'])): ?>
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
                            <?php echo $component->button('referral',' ','Change Referrals','button--class-4',$channeling['appointment_ID']) ?>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
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
    const updates=document.querySelectorAll('.button--class-4');
    console.log(updates);
    updates.forEach(function(elem){
        elem.addEventListener('click',()=>{
            location.href="patient-appointment?spec=referral&mod=update&id="+elem.id;
        })
    })
</script>
