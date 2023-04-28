<?php
use app\core\DbModel;
use \app\core\form\Form;
use app\core\component\Component;
$component=new Component();

$pid = $patient[$number]['patient_ID']??'';
$cid = $patient[$number]['channeling_ID']??'';
$apoid = $patient[$number]['appointment_ID']??'';
var_dump($tests);
// var_dump($number);
?> 

<?php if($patient){?>
    
    <div class="column-flex">
        <div class="main-detail-title">
            <h1><?=$channeling['speciality']." - Dr. ".$doctor['name']?></h1>
        </div>
        <div class="patient-change-div">
            <img class="pimg" style="width: 7vw;" src="./media/images/icons/previous.svg" alt="previous icon" onclick="previous(<?=$id?>,<?=$number?>)">
            
            <div class="number-content">
                <h2>Patient Number</h2>
                <div class="number-pad">
                    <div class="number-item fs-200"><?=$number+1?></div>
                </div>
                <div class="move-number-div">
                    <input type="number" id="move-number" name="move-number">
                    <button onclick="move(<?=$id?>)">Move</button>
                </div>
            </div>

            <img class="nimg" style="width: 7vw;" src="./media/images/icons/next.svg" alt="next icon" onclick="next(<?=$id?>,<?=$number?>)">
        
        </div>

        <div class="scheduled-info fs-100">
            <span>Patient Name : <?=$patient[$number]['name']?></span>
        </div>
    </div>

    <div class="patient-details">
        <span>Age : <?=$patient[$number]['age']?></span>
        <span>Gender : <?=$patient[$number]['gender'] ?></span>
        <span>Contact No : <?=$patient[$number]['contact'] ?></span>
    </div> 

    <?php if($testValues){?>
        <?php //var_dump($testValues,$number) ?>
        <div class="inputs-div">
            <?php $x = 0; ?>   
            <?php foreach($tests as $key=>$test): ?>
                
                <div class="input-row border-bottom">
                    <div class="input-row-col-1"><?=$test['name']?></div>
                    <div class="input-row-col-2"><?=$testValues[$x]['value']; $x = $x+1; ?></div>
                    <div class="input-row-col-3"><?=$test['metric']?></div>
                </div>
                
            <?php endforeach; ?>    
                
        </div>
        
    <?php } ?>

<?php } else{
    echo("<center><br><br><div class='no-clinic-desc'>No Patients</div></center>");
} ?>


<script>
    function next(id, num1){
        location.href='nurse-list-patient?id='+id+'&num='+num1+'&n=1&view=1';
    }

    function previous(id, num1){
        location.href='nurse-list-patient?id='+id+'&num='+num1+'&p=1&view=1';
    }

    function move(id){
        const num = document.getElementById("move-number").value
        const num1 = num-2;
        console.log(num1);
        location.href='nurse-list-patient?id='+id+'&num='+num1+'&n=1&view=1';

    }
    
</script>