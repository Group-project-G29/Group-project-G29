<?php
    use app\core\component\Component;
    use app\core\form\Form;

    $form = new Form();
?>

<div>
    <div>

    </div>
    <div>
        <?= $form->select($model,'reports','','',['SOAP Report'=>'soap-report','Consultation Report'=>'consultation-report','Medical History Report'=>'medical-history-report'],'select-main') ?>
    </div>
    <div>
        <?= $form->textarea($model,'examination','examination','Examination',10,120,'');?>
        
    </div>
    <div>
        <?= $form->textarea($model,'consultation','consultation','Consultation',18,120,'');?>
        
    </div>    
    <div>
        <?= $form->textarea($model,'recommendation','recomendation','Recommendation',18,120,'');?>
        
    </div> 


</div>

<script src="./media/js/main.js"></script>
<script>
    const mainSelect=e('select-main','id');
    mainSelect.value="consultation-report";
    mainSelect.addEventListener('change',()=>{
        console.log("change");
        location.href="/ctest/doctor-report?spec="+mainSelect.value;

    })
</script>