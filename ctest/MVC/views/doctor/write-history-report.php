<?php
    use app\core\component\Component;
use app\core\form\Form;

    $form = new Form();
?>

<div>
    <div>

    </div>
    <div>
        <?= $form->select($model,'reports','','',['Consultation Report'=>'consultation-report','Medical History Report'=>'medical-history-report'],'select-main') ?>
    </div>
    <div>
        <?= $form->textarea($model,'medication','medication','Passed Medication',10,130,'');?>
        
    </div>
    <div>
        <?= $form->textarea($model,'allergies','allergies','Allergies',10,130,'');?>
        
    </div>    
    <div>
        <?= $form->textarea($model,'note','note','Note',10,130,'');?>
        
    </div>


</div>
<script src="./media/js/main.js"></script>
<script>
    const mainSelect=e('select-main','id');
    mainSelect.addEventListener('change',()=>{

        location.href="/ctest/doctor-report?spec="+mainSelect.value;
    })
</script>