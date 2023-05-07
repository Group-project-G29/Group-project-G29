<?php

use app\core\component\Component;
use app\models\Employee;

$component = new Component();
?>

<div class="semi-header-container" style="width: 28%">
    <div class="" style="margin-left: 5vw;">
        <h1 class="fs-200 fc-color--dark">Patient Detail</h1>

        <?php $age = "18" ?>
        <h5><?php if ($PatientDetail['age'] < $age) {
                echo " Pediatric *";
            } else {
                echo "Adult *";
            } ?></h5>

        <h4><b> Patient ID : </b><?= $PatientDetail['patient_ID'] ?></h4>
        <h4><b>Name : </b><?= $PatientDetail['name'] ?></h4>
        <h4><b>NIC : </b><?= $PatientDetail['nic'] ?></h4>
    </div>


</div>

<div class="button-1" style="padding-top: 3vw;padding-left:35vw;" id=<?= $PatientDetail['patient_ID'] ?>>
    <?php echo $component->button('Set Appoinment', '', 'Set Appoinment', 'button--class-0  width-10', 'edit-details'); ?>
</div>

<div class="table-container" style="padding-top:10vw;height:35vh;">
    <table border="0" style="margin-left:0px">

        <tr class="row-height header-underline">
            <th>Clinic</th>
            <th>Doctor</th>
            <th>Day</th>
            <th>Time</th>
            <th>Fee</th>
            <th> </th>
        </tr>


        <?php foreach ($channelings as $channeling) : ?>

            <tr class=" " id=<?= $channeling['patient_ID'] ?>>
                <?php $time = "12.00" ?>

                <td><?= $channeling['career_speciality'] ?></td>
                <td><?= $channeling['name'] ?></td>
                <td><?= $channeling['day'] ?></td>
                <td><?php if ($channeling['time'] < $time) {
                        echo $channeling['time'] . " A.M";
                    } else {
                        echo $channeling['time'] . " P.M";
                    } ?> </td>
                <td>Rs.<?= $channeling['fee'] ?></td>

                <!-- <td class='table-row-0  row-height hover' style="padding-left: 1px;">Cancel</td> -->
                <!-- <td><?php echo $component->button('cancel', '', 'Cancel', 'button-3 table-row-0 row-height hover', $channeling['patient_ID']); ?></td> -->

            </tr>

        <?php endforeach; ?>




    </table>

</div>


<!-- <div class="button-0" id=<?= $channeling['patient_ID'] ?>>
    <?php echo $component->button('edit-details', '', 'Payments', 'button--class-0  width-10', 'edit-details'); ?>
</div> -->


<br>



<script>
    elementsArray = document.querySelectorAll(".button-1");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-patient-appointment?mod=view&id=' + elem.id;
        });
    });

    elementsArray = document.querySelectorAll(".button-0");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-channeling-payment?id=' + elem.id;
        });
    });

    elementsArray = document.querySelectorAll(".button-3");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-channeling-session-patient-detail-more?cmd=delete&id=' + elem.id;
        });
    });
</script>