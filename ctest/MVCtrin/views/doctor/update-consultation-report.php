<?php
    use app\core\component\Component;
    use app\core\form\Form;

    $form = new Form();
    $component=new Component();
?>
<?php  $form->begin('','post')?>
<div>
    <div>
        <div>
            <?= $form->select($model,'reports','','',['SOAP Report'=>'soap-report','Consultation Report'=>'consultation-report','Medical History Report'=>'medical-history-report','Refferal'=>'referral'],'select-main') ?>
        </div>
        <div>
            <?= $component->button('','submit','Update Report','button--class-0'); ?>
        </div>
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
<?php $form->end(); ?>
<script src="./media/js/main.js"></script>
<script>
    const mainSelect=e('select-main','id');
    mainSelect.value="consultation-report";
    mainSelect.addEventListener('change',()=>{
        console.log("change");
        location.href="/ctest/doctor-report?spec="+mainSelect.value;

    })
</script>