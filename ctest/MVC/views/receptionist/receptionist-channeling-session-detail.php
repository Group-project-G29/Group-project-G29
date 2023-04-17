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
<div class="table-container" >
        <table class="table-container"  >
    
            <tr class="table-row-session">
                <td>Date</td>
                <td>:</td>
                <td class="table-row-data-1"><?= $channelingSession['channeling_date'] ?></td>
                <td class="table-row-data-1"></td>

            </tr>
            <tr class="table-row-session">
                <td>Room</td>
                <td >:</td>
                <td class="table-row-data-1"><?= $channelingSession['room'] ?></td>
                <td class="table-row-data-1"></td>
            </tr>
            <tr class="table-row-session">
                <td>Appoinments</td>
                <td>:</td>
                <td class="table-row-data-1"><?= $channelingSession['total_patients']-$channelingSession['remaining_appointments'] ?></td>
                <td style="background-color: #1746A2;color:white"><?= $channelingSession['total_patients'] ?></td>
            </tr>
            <tr class="table-row-session">
                <td>Free Appoinments</td>
                <td>:</td>
                <td class="table-row-data-1"><?= $channelingSession['max_free_appointments']-$channelingSession['remaining_free_appointments'] ?></td>
                <td style="background-color: #1746A2;color:white"><?= $channelingSession['max_free_appointments'] ?></td>

            </tr>

           
        </table>
    
    </div>
    <div class="table-container">
    <table border="0">

        <tr class="row-height header-underline">
        <th>Patient ID</th>
            <th>Patient Name</th>
            <th>Patient Age</th>

        </tr>


        <?php foreach ($channelingPatient as $channeling) : ?>

            <tr class='table-row  row-height hover' id=<?=$channeling['patient_ID']?> >
            <td><?= $channeling['patient_ID'] ?></td>

            <td><?= $channeling['name'] ?></td>
            <td><?= $channeling['age'] ?></td>
                

            </tr>
        <?php endforeach; ?>
    </table>
</div>
</div>

<script>
   elementsArray = document.querySelectorAll(".table-row");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-channeling-session-patient-detail-more?id=' + elem.id;
        });
    });
</script>



