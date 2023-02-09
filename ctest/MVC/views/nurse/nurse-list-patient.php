

<div class="column-flex">
    <div class="main-detail-title">
        <h1><?=$channeling['speciality']." - ".$doctor['name']?></h1>
    </div>
    <div class="number-content">
        <h2>Patients</h2>
        <div class="number-pad">
            <div class="number-item--white fs-200"><?=$openedchanneling['remaining_appointments']?></div>
            <div class="number-item--blue fs-200"><?=$channeling['total_patients']?></div>
        </div>
    </div>
    <div class="scheduled-info fs-100">
        <span>Age :<?=$patient[0]['age']?></span>
        <span>Gender :<?php if($patient[0]['gender'] == 'F'){echo "Femail";}else{echo "Mail";}?></span>
        <span>Contact No :<?=$patient[0]['contact'] ?></span>

    </div>
    
    

    


</div>