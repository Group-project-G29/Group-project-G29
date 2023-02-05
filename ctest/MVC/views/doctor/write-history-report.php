<?php
    use app\core\component\Component;
use app\core\form\Form;

    $form = new Form();
    $component=new Component();
?>
<?php $form->begin('','post'); ?>
<div>
    <div>
        <div>
            <?= $form->select($model,'reports','','',['SOAP Report'=>'soap-report','Consultation Report'=>'consultation-report','Medical History Report'=>'medical-history-report','Refferal'=>'referral'],'select-main') ?>
        </div>
        <div>
            <?= $component->button('','submit','Add Report','button--class-0'); ?>
            
        </div>
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
<?php $form->end(); ?>
<script src="./media/js/main.js"></script>
<script>
    const mainSelect=e('select-main','id');
    mainSelect.value="medical-history-report";
    mainSelect.addEventListener('change',()=>{

        location.href="/ctest/doctor-report?spec="+mainSelect.value;
    })
</script>