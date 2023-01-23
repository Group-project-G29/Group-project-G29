<?php

use app\core\component\Component;
use app\models\Employee;

$component = new Component();
//      var_dump($channelingSession);
// exit;
?>
<div class="header-container">
    <div class="header-name" style="text-align: center;">
        <h2>Dr.<?= $channelingPatient['name'] ?></h2>
    </div>
    <div class="semi-header-name" style="text-align: center;">
        <h3><?= $channelingPatient['day'] ?> Channeling</h3>
    </div>
    <div class="semi-name">
        <h5>ppp</h5>
    </div>
    <div class="">
        <h1>Patients</h1>
    </div>.

    <table class="table-session">
        <tr class="table-row-session">
            <td class="table-row-data"><?= $channelingSession['remaining_free_appointments'] ?></td>
            <td style="background-color: #1746A2;color:white"><?= $channelingSession['max_free_appointments'] ?></td>

        </tr>
    </table>
</div>
<div class="table-container">
    <table border="0">

        <tr class="row-height header-underline">
            <th>channeling</th>
            <th>Date</th>
            <th>Time</th>
            <th>Room</th>
            <th>Status</th>


        </tr>


        <?php foreach ($channelingmore as $key => $channeling) : ?>

            <tr class='table-row  row-height hover'>
                <td><?= $channeling['speciality'] ?></td>
                <td>All <?= $channeling['day'] ?> </td>
                <td><?= $channeling['time'] ?> </td>
                <td><?= $channeling['room'] ?> </td>
                <td> </td>

            </tr>
        <?php endforeach; ?>




    </table>
</div>
<br>
<div class="button">
    <?php echo $component->button('edit-details', '', 'View Patients', 'button--class-0  width-10', 'edit-details'); ?>
</div>


<script>
    elementsArray = document.querySelectorAll(".button");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-all-channeling-session-patient-detail?id=' + elem.id;
        });
    });
</script>