<?php
use app\core\DbModel;
use \app\core\form\Form;
use app\core\component\Component;
$component=new Component();
$form=Form::begin('','post');

// var_dump($patient);

?> 

<div class="column-flex">
    <div class="main-detail-title">
        <h1><?=$channeling['speciality']." - Dr. ".$doctor['name']?></h1>
    </div>
    <div class="patient-change-div">
        <img class="pimg" style="width: 7vw;" src="./media/images/icons/previous.svg" alt="previous icon" onclick="previous(<?=$id?>,<?=$number?>)">
        
        <div class="number-content">
            <h2>Patients</h2>
            <div class="number-pad">
                <div class="number-item--white fs-200"><?=$number+1?></div>
                <div class="number-item--blue fs-200"><?=$channeling['total_patients']?></div>
            </div>
        </div>

        <img class="nimg" style="width: 7vw;" src="./media/images/icons/next.svg" alt="next icon" onclick="next(<?=$id?>,<?=$number?>)">
       
    </div>

    <?php // var_dump($patient) ?>
    <div class="scheduled-info fs-100">
        <span>Patient Name : <?=$patient[$number]['name']?></span>
    </div>
</div>

<div class="patient-detals">
    <span>Age : <?=$patient[$number]['age']?></span>
    <span>Gender : <?=$patient[$number]['gender'] ?></span>
    <span>Contact No : <?=$patient[$number]['contact'] ?></span>
</div> 

<div class="inputs-div">
    <!-- <div class="input-row">
        <div class="input-row-col-1">Blood Sugar : </div>
        <div class="input-row-col-2"><input type="text"></div>
        <div class="input-row-col-3">mg</div>
    </div>
    <div class="input-row">
        <div class="input-row-col-1">Preassure : </div>
        <div class="input-row-col-2"><input type="text"></div>
        <div class="input-row-col-3">mmhg</div>
    </div>
    <div class="input-row">
        <div class="input-row-col-1">Weight : </div>
        <div class="input-row-col-2"><input type="text"></div>
        <div class="input-row-col-3">kg</div>
    </div>
    <div class="input-row">
        <div class="input-row-col-1">Height : </div>
        <div class="input-row-col-2"><input type="text"></div>
        <div class="input-row-col-3">cm</div>
    </div> -->

    <div class="input-row">
        <table>
            <?php foreach($tests as $key=>$test): ?>

                <?php echo $form->spanfield($prechannelingtestmodel,'name',$test['name'].' ('.$test['metric'].')'.' :  ','field','text') ?>
            
            <?php endforeach; ?>    
        </table>
    </div>
</div>

<center><div onclick="next(<?=$id?>,<?=$number?>)" class="save-button"><?php echo $component->button("add-data","submit","Save","button--class-0","add-data")?></div></center>



<script>
    function next(id, num1){
        location.href='nurse-list-patient?id='+id+'&num='+num1+'&n=1';
    }

    function previous(id, num1){
        location.href='nurse-list-patient?id='+id+'&num='+num1+'&p=1';
    }
</script>