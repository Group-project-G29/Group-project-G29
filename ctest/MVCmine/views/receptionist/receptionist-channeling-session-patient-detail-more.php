<?php

use app\core\component\Component;
use app\models\Employee;

$component = new Component();
?>
<div class="header-container">
    <h2>Patient Detail</h2>

    <div class="semi-header-container">
        <h5>Patient ID = <?= $PatientDetail['patient_ID'] ?></h5>
        <h5>Name = <?= $PatientDetail['name'] ?></h5>
        <h5>NIC = <?= $PatientDetail['nic'] ?></h5>

        <?php $age="18"?>
        <h5><?php if ($PatientDetail['age']< $age){
        echo "Categoty = Pediatric";}
        else{echo "Categoty = Adult";}?></h5>

        <div class="button">
            <?php echo $component->button('edit-details', '', 'Edit', 'button--class-0  width-10', 'edit-details'); ?>
        </div>

       

    </div>
</div>
<div class="table-container">
    <table border="0">

        <tr class="row-height header-underline">
            <th>Clinic</th>
            <th>Doctor</th>
            <th>Day</th>
            <th>Time</th>
            <th>Fee</th>
            <th> </th>
        </tr>


        <?php foreach ($channelings as $channeling) : ?>

            <tr>
                <td><?= $channeling['career_speciality'] ?></td>
                <td><?= $channeling['name'] ?></td>
                <td><?= $channeling['day'] ?></td>
                <td><?= $channeling['time'] ?></td>
                <td><?= $channeling['fee'] ?></td>
                <td class='table-row-0  row-height hover' style="padding-left: 1px;">Cancel</td>

            </tr>

        <?php endforeach; ?>




    </table>

</div>
<div class="button-1" id=<?= $channeling['patient_ID'] ?>>
    <?php echo $component->button('Set Appoinment', '', 'Set Appoinment', 'button--class-0  width-10', 'edit-details'); ?>
    
</div>

<div class="button-0" id=<?= $channeling['patient_ID'] ?>>
    <?php echo $component->button('edit-details', '', 'Payments', 'button--class-0  width-10', 'edit-details'); ?>
</div>


<br>



<script>
    elementsArray = document.querySelectorAll(".button-1");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-channeling-set-appointment?id=' + elem.id;
        });
    });
</script>

<script>
    elementsArray = document.querySelectorAll(".button-0");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-channeling-payment?id=' + elem.id;
        });
    });
</script>