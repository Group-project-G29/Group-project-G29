
<?php
use app\core\DbModel;
use \app\core\form\Form;
use app\core\component\Component;
$component=new Component();

$pid = $patient[$number]['patient_ID']??'';
$cid = $patient[$number]['channeling_ID']??'';
$apoid = $patient[$number]['appointment_ID']??'';
?> 

<div class="column-flex">
    <div class="main-detail-title">
        <h1><?=$channeling['speciality']." - Dr. ".$doctor['name']?></h1>
    </div>
    <div class="patient-change-div">
        <div class="number-content">
            <h2>Patient Number</h2>
            <div class="number-pad">
                <div class="number-item fs-200"><?=$number+1?></div>
            </div>
        </div>
    
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


<?php $form=Form::begin('nurse-patient-test-value-edit','post'); ?>

    <input type="hidden" id="id" name="id" value="<?= $id ?>">
    <input type="hidden" id="number" name="number" value="<?= $number ?>">
    <input type="hidden" id="pid" name="pid" value="<?= $pid ?>">
    <input type="hidden" id="cid" name="cid" value="<?= $cid ?>">
    <input type="hidden" id="apoid" name="apoid" value="<?= $apoid ?>">

    <div class="inputs-div">
        <?php $x = 0; ?> 
        <?php foreach($tests as $key=>$test): ?>

            <div class="input-row">
                <div class="input-row-col-1"><?=$test['name']?></div>
                <input class="form-deatail_input" type="number" step=0.01 value="<?=$testValues[$x]['value'];?>" id="<?=$test['test_ID']?>" name="<?=$test['test_ID']?>" require>
                <div class="input-row-col-3"><?=$test['metric']?></div>
            </div>
        
        <?php  $x = $x+1;  endforeach; ?>    
            
    </div>

    <center><div class="save-button"><?php echo $component->button("add-data","submit","Save","button--class-0","add-data")?></div></center>

<?php Form::end() ?> 