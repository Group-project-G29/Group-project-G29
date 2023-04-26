<?php
use app\core\DbModel;
use \app\core\form\Form;
use app\core\component\Component;
$component=new Component();

$pid = $patient[$number]['patient_ID']??'';
$cid = $patient[$number]['channeling_ID']??'';
$apoid = $patient[$number]['appointment_ID']??'';
// var_dump($tests);
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
                <div class="patient-numbers">
                    <div class="ptient-count">Total Patients : <span><?=$reAppo?></span></div>
                    <div class="ptient-count">Previous Patient Number : <span><?=$prevNum?></span></div>
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

        <center><div onclick="edit(<?=$id?>,<?=$number?>,<?=$pid?>)" class="save-button"><?php echo $component->button("edit-data","submit","Edit","button--class-0","edit-data")?></div></center>

    <?php } else if($tests){ ?>
        <?php $form=Form::begin('nurse-patient-test-value-save','post'); ?>

            <input type="hidden" id="id" name="id" value="<?= $id ?>">
            <input type="hidden" id="number" name="number" value="<?= $number ?>">
            <input type="hidden" id="pid" name="pid" value="<?= $pid ?>">
            <input type="hidden" id="cid" name="cid" value="<?= $cid ?>">
            <input type="hidden" id="apoid" name="apoid" value="<?= $apoid ?>">

            <div class="inputs-div">
                    
                <?php foreach($tests as $key=>$test): ?>

                    <div class="input-row">
                        <div class="input-row-col-1"><?=$test['name']?></div>
                        <input class="form-deatail_input" type="number" step=0.01 id="<?=$test['test_ID']?>" name="<?=$test['test_ID']?>" require>
                        <div class="input-row-col-3"><?=$test['metric']?></div>
                    </div>
                
                <?php endforeach; ?>    
                    
            </div>

            <center><div class="save-button"><?php echo $component->button("add-data","submit","Save","button--class-0","add-data")?></div></center>

        <?php Form::end() ?>  
    <?php } ?>

<?php } else{
    echo("<center><br><br><div class='no-clinic-desc'>No Patients</div></center>");
} ?>


<script>
    function next(id, num1){
        location.href='nurse-list-patient?id='+id+'&num='+num1+'&n=1';
    }

    function previous(id, num1){
        location.href='nurse-list-patient?id='+id+'&num='+num1+'&p=1';
    }

    function edit(id, num1, pid){

        location.href='nurse-patient-test-value-edit?id='+id+'&num='+num1+'&n=1&pid='+pid;
    }

    function move(id){
        const num = document.getElementById("move-number").value
        const num1 = num-2;
        console.log(num1);
        location.href='nurse-list-patient?id='+id+'&num='+num1+'&n=1&prevNum='+<?=$number+1?>;

    }
    
</script>