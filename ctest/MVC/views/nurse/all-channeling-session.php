
<?php
//  var_dump($channeling);exit;
// var_dump("<br><br>");
// var_dump($openedchanneling);
// var_dump("<br><br>");
//  var_dump($doctor);exit;
// var_dump("<br><br>");
// var_dump($nurse);
// var_dump("<br><br>");

?>


<div class="column-flex">
    <div class="main-detail-title">
        <h1><?=$channeling->speciality." - ".$doctor[0]['name']?></h1>
    </div>
    <div class="patient-change-div">
        <div class="number-content">
            <h2>Patients</h2>
            <div class="number-pad">
                <div class="number-item fs-200"><?=$channeling->total_patients?></div>
            </div>
        </div>

    </div>
    <div class="scheduled-info fs-100">
        <span>Room :<?=$channeling->room?></span>
        <span>Starts In:<?=$channeling->time?></span>

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
    <button class="click button--class-0 start_button" id=<?=$openedchanneling->opened_channeling_ID?>>Patients List</button>

    


</div>
<script>
    const btn=document.querySelector(".click");
    btn.addEventListener('click',()=>{
        location.href="nurse-list-patient?id="+btn.id;
    })

</script>