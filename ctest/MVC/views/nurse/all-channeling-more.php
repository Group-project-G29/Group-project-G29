<?php
use app\core\DbModel;
use \app\core\form\Form;
use app\core\Application;
use app\core\component\Component;

$component = new Component();
?>

<?php
// var_dump($clinic);
// echo("  <br> <br>");
// var_dump($openClinic[0]);
?>
<div class="column-flex">
    <div class="main-detail-title">
        <h1><?=$channeling[0]['speciality']." - ".$channeling[0]['day']?></h1>
    </div>
    <div class="patient-change-div">
        <div class="number-content">
            <h2>Patients</h2>
            <div class="number-pad">
                <div class="number-item fs-200"><?php if($channeling[0]['total_patients'] == -1){echo("Unlimit");}else{echo($channeling[0]['total_patients']);}?></div>
            </div>
        </div>

    </div>
    <div class="scheduled-info fs-100">
        <span>Room :<?=$channeling[0]['room']?></span>
        <span>Starts In:<?=$channeling[0]['time']?></span>

    </div>
    <div>
        <span class="fs-100">Assigned Nurses <?php $nurses=$nurse; ?></span>
        <div class="nurse-container">
            <?php foreach($nurse as $nurse):?>
                <div class="nurse-item">
                    <img src=<?='media/images/emp-profile-pictures/'.$nurse['img']?>>
                    <h3><?=$nurse['name']?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    


</div>

<?php if($openedchanneling){?>

    <div class="table-div">
    <table border="0">
        <thead>
            <tr>
                <th>Session</th>
                <th>Date</th>
                <th>Remaining Appointments</th>
                <!-- <th>Remaining Free Appointments</th> -->
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php $session = 1; ?>
        <?php foreach($openedchanneling as $key=>$clinic): ?>

            <tr class="table-row row-height" id="<?= $clinic['opened_channeling_ID'] ?>">
                <td><?php echo("Session - ".$session); ?></td>
                <td><?= $clinic['channeling_date'] ?></td>
                <td><?php if($clinic['remaining_appointments']== -1){echo("Unlimit");}else{echo($clinic['remaining_appointments']);} ?></td>
                <!-- <td><?= $clinic['remaining_free_appointments'] ?></td> -->
                <td><?= $clinic['status'] ?></td>

                <?php $session = $session + 1; ?>
            </tr>
            
        <?php endforeach; ?>

        <!-- <script>
            elementsArray = document.querySelectorAll(".table-row");
            elementsArray.forEach(function(elem) {
                elem.addEventListener("click", function() {
                    // location.href='all-channeling-session?channeling='+elem.id;
                    location.href="nurse-list-patient?id="+elem.id+"&view=1";
                });
            });
        </script> -->
    </table>
    </div>

<?php } ?>








