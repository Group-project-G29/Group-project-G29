<?php
    use app\core\component\Component;
    use app\models\Employee;

    $component=new Component();
//      var_dump($channelingSession);
// exit;
?>
<div class="header-container">
<div class="header-name"><h2>Dr.<?=$channelingSession['name']?></h2></div>
<div class="semi-header-name"><h3><?=$channelingSession['day']?> Channeling</h3></div>
</div>
<div class="detail-container" >
        <table class="table-session" >
        <tr class="table-row-session">
                <td>Schedule For </td>
                <td>:</td>
                <td class="table-row-data">Every <?= $channelingSession['day'] ?></td>
            </tr>
            <tr class="table-row-session">
                <td>Doctor Name</td>
                <td>:</td>
                <td class="table-row-data"><?= $channelingSession['name'] ?></td>
            </tr>
            <tr class="table-row-session">
                <td>Date</td>
                <td>:</td>
                <td class="table-row-data"><?= $channelingSession['channeling_date'] ?></td>
            </tr>
            <tr class="table-row-session">
                <td>Room</td>
                <td>:</td>
                <td class="table-row-data"><?= $channelingSession['room'] ?></td>
            </tr>
            <tr class="table-row-session">
                <td>Appoinments</td>
                <td>:</td>
                <td class="table-row-data"><?= $channelingSession['total_patients']-$channelingSession['remaining_appointments'] ?></td>
                <td style="background-color: #1746A2;color:white"><?= $channelingSession['total_patients'] ?></td>
            </tr>
            <tr class="table-row-session">
                <td>Free Appoinments</td>
                <td>:</td>
                <td class="table-row-data"><?= $channelingSession['max_free_appointments']-$channelingSession['remaining_free_appointments'] ?></td>
                <td style="background-color: #1746A2;color:white"><?= $channelingSession['max_free_appointments'] ?></td>

            </tr>

           
        </table>
        <br>
        <div class="button" id=<?=$channelingSession['emp_ID']?>>
        <?php echo $component->button('edit-details','','View Patients','button--class-0  width-10','edit-details');?>
    </div>
    </div>
</div>

<script>
  elementsArray = document.querySelectorAll(".button");
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'receptionist-channeling-session-patient-detail?id='+elem.id;  //pass the variable value
    });
  });
</script>



