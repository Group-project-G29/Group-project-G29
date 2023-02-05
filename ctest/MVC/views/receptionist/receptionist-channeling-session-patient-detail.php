<?php

use app\core\component\Component;
use app\models\Employee;

$component = new Component();
//      var_dump($channelingSession);
// exit;
?>
<div class="header-container">
    <div class="semi-header-container">
    <div class="header-name">
        <h2>Dr.<?= $channelingDoc['name'] ?></h2>
    </div>
    <div class="semi-header-name">
        <h3><?= $channelingDoc['speciality']?> <?=$channelingDoc['day'] ?> Channeling</h3>
    </div>
    <div class="semi-name">
        <h5>channeling ID = <?= $channelingDoc['channeling_ID']?></h5>
    </div>
    <div class="topic">
        <h1>Patients</h1>
    </div>.

    <table class="table-session">
        <tr class="table-row-session">
        <td class="table-row-data" style="padding:15px 15px 15px 15px;" ><?= $channelingDoc['max_free_appointments'] ?></td>
        <td class="table-row-data" style="background-color: #1746A2;color:white"><?= $channelingAppo['remaining_free_appointments'] ?></td>

        </tr>
    </table>
    </div>
</div>
<div class="table-container">
    <table border="0">

        <tr class="row-height header-underline">
            <th>Patient</th>
        </tr>


        <?php foreach ($channelingPatient as $channeling) : ?>

            <tr class='table-row  row-height hover' id=<?=$channeling['patient_ID']?> >
                <td><?= $channeling['name'] ?></td>
                

            </tr>
        <?php endforeach; ?>
    </table>
</div>
<br>

<script>
    elementsArray = document.querySelectorAll(".table-row");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-channeling-session-patient-detail-more?id=' + elem.id;
        });
    });
</script>