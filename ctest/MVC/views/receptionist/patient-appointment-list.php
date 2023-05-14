<?php
    use app\core\component\Component;
use app\models\Appointment;

    $component=new Component();
    $popup=$component->popup("Are you sure you want to delete the channeling session","popup-value","popup--class-1","yes");
    echo $popup;
    $appointmentModel=new Appointment();
?>
<div class="patient-detail"style=" padding:1vw;height:10vw" > 
    <div class="" style="margin-left: 5vw;">
        <h1 class="fs-200 fc-color--dark">Patient Detail</h1>

        <?php $age = "18" ?>
        <h5><?php if ($patient[0]['age'] < $age) {
                echo " Pediatric *";
            } else {
                echo "Adult *";
            } ?></h5>

        <h4><b> Name  : </b><?= $patient[0]['name'] ?></h4>
        <h4><b>NIC : </b><?= $patient[0]['nic'] ?></h4>
        <h4><b>Age : </b><?= $patient[0]['age'] ?></h4>
    </div>


</div>

<div class="table-container">
<?php if($appointments):?>
<table border="0">
    <tr>
        <th>Queue Number</th><th>Doctor</th><th>Date</th><th>Time</th><th></th>
    </tr>
        <?php foreach($appointments as $key=>$channeling): ?>
            <?php if($appointmentModel->isInPass($channeling['appointment_ID'])): ?>
                <tr class="table-row">
                    
                    <td><?=$channeling['queue_no']?></td>
                    <td><?=$channeling['name']?></td>  
                    <td><?=$channeling['channeling_date']?></td>
                    <td><?=$channeling['time']?></td>  
                    <td>
                        <div>
                            <?php echo $component->button('delete',' ','Cancel Appointment','button--class-3',$channeling['appointment_ID']."&patient=".$channeling['patient_ID']) ?>
                        </div>
                    </td>
                    <td>
                        <div>
                            <?php echo $component->button('delete',' ','Pay','button--class-0 width-10',$channeling['patient_ID']) ?>
                        </div>
                    </td>
                </tr>
            <?php endif;?>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <img src="media/images/common/noappointment.png"  style="width:10vw;margin:3vw">
        <div class="empty-container">

          <p>Looks like there's no Appointment</p>  
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
        pay=document.querySelectorAll(".button--class-0");
     
 pay.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='patient-detail?mod=view&id='+elem.id;
          
        });
    });
  
</script>