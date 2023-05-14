<?php
use app\core\DbModel;
use \app\core\form\Form;
use app\core\component\Component;
$component=new Component();

$pid = $patient[$number]['patient_ID']??'';
$cid = $patient[$number]['channeling_ID']??'';
$apoid = $patient[$number]['appointment_ID']??'';
// var_dump($number);
// var_dump($patient[$number]);
?>



<?php if($patient){?>

    <div class="detail-row">

        <div class="column-flex">
            <div class="main-detail-title">
                <h1><?=$channeling['speciality']." - Dr. ".$doctor['name']?></h1>
            </div>
            <div class="patient-change-div">
                <img class="pimg" style="height: 7vw;" src="./media/images/channeling assistance/left-chevron.png" alt="previous icon" onclick="previous(<?=$id?>,<?=$number?>)">
                
                <div class="number-content">
                    <h2>Patient Number</h2>
                    <div class="number-pad">
                        <div class="number-item fs-200"><?=$patient[$number]['queue_no']?></div>
                    </div>
                    <div class="move-number-div">
                        <input type="number" id="move-number" name="move-number">
                        <button onclick="move(<?=$id?>)">Move</button>
                    </div>
                    <div class="patient-numbers">
                        <div class="ptient-count">Total Patients : <span><?=$payedAppoCount?></span></div>
                        <div class="ptient-count">Previous Patient Number : <span><?=$prevNum?></span></div>
                    </div>
                    <div class="patient-checkbox">
                        <?php if($patient[$number]['status']!='seeing'){ ?>
                        <label for="Checkbox">Check Patient</label>
                        <input type="checkbox" class="Checkbox" name="Checkbox" id="Checkbox"/>
                        <?php } else{ ?>
                        <div>Patient checked</div>
                        <?php } ?>
                    </div>
                </div>
    
                <img class="nimg" style="height: 7vw;" src="./media/images/channeling assistance/right-chevron.png" alt="next icon" onclick="next(<?=$id?>,<?=$number?>)">
            
            </div>
    
        </div>

    </div>

    <div class="detail-row">
        <div class="scheduled-info fs-100">
            <center><span>Patient Name : <?=$patient[$number]['name']?></span></center>
        </div>
    
        <div class="patient-details">
            <span>Age : <?=$patient[$number]['age']?></span>
            <span>Gender : <?=$patient[$number]['gender'] ?></span>
            <span>Contact No : <?=$patient[$number]['contact'] ?></span>
        </div> 
    </div>

    
    

    <?php if($testValues){?>
        <div class="detail-row">
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
        </div>

    <?php } else if($tests){ ?>
        <div class="detail-rown">
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
        </div> 
    <?php } ?>

<?php } else{
    echo("<center><br><br><div class='no-clinic-desc'>No Patients</div></center>");
}  ?>





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
        const num = document.getElementById("move-number").value;
        const num1 = num;
        location.href='nurse-list-patient?id='+id+'&num='+num1+'&cmd=move&prevNum='+<?=$number+1?>;

    }
   

    const checkActive = document.getElementById("Checkbox");
    checkActive.addEventListener("click", ()=>{
        if(checkActive.checked){
            location.href='nurse-list-patient?id='+<?=$id?>+'&num='+<?=$number-1?>+'&n=1&prevNum='+<?=$prevNum?>+'&check=1&apoid='+<?=$apoid?>;
        }
    });
    
</script>
