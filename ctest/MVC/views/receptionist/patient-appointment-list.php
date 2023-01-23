<?php
    use app\core\component\Component;
    $component=new Component();
    $popup=$component->popup("Are you sure you want to delete the appointment session","popup-value","popup--class-1","yes");
    echo $popup;
?>


<div class="table-container">
<?php if($appointments):?>
<table border="0">
    <tr>
        <th>Clinic</th><th>Doctor</th><th>Date</th><th>Time</th>
    </tr>
       
        <?php foreach($appointments as $key=>$appointment): ?>
        <tr class="table-row">
            
            <td><?=$appointment['speciality']?></td>
            <td><?=$appointment['name']?></td>  
            <td><?=$appointment['channeling_date']?></td>
            <td><?=$appointment['time']?></td>  
            <td>
                <div>
                    <?php echo $component->button('delete',' ','Cancel Appointment','button--class-3',$appointment['appointment_ID']."&patient=".$appointment['patient_ID']) ?>
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
    elementsArray = document.querySelectorAll(".button--class-3");
    url="";
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            url='receptionist-patient-appointment?cmd=delete&id='+elem.id;
            div.style.display="flex";
            bg.classList.add("background");
        });
    });
    yes.addEventListener("click",()=>{
            location.href=url;
        })
</script>