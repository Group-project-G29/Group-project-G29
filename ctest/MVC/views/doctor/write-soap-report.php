<?php
    use app\core\component\Component;
use app\core\form\Form;
?>

<?php $form = new Form();?>

<div>
    <div>

    </div>
    <div>
        <?= $form->select($model,'reports','','',['SOAP report'=>'soap-report','Consultation Report'=>'consultation-report','Medical History Report'=>'medical-history-report'],'select-main') ?>
    </div>
    <div>
        <?= $form->textarea($model,'subjective','subjective','Subjective',10,130,'');?>
        
    </div>
    <div>
        <?= $form->textarea($model,'objective','objective','Objective',10,130,'');?>
        
    </div>    
    <div>
        <?= $form->textarea($model,'assessment','assessment','Assessment',10,130,'');?>
        
    </div>
    <div>
        <?= $form->textarea($model,'plan','plan','Plan',10,130,'');?>
        
    </div>
    <div>
        <?= $form->textarea($model,'additional_note','additional_note','Additional Note',10,130,'');?>
        
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