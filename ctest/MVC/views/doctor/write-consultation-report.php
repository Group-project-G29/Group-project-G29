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
        <?= $form->textarea($model,'consultation','consultation','Consultation',10,120,'');?>
        
    </div>
    <div>
        <?= $form->textarea($model,'examination','examintation','Examination',18,120,'');?>
        
    </div>    



</div>

<script src="./media/js/main.js"></script>
<script>
    const mainSelect=e('select-main','id');
    mainSelect.addEventListener('change',()=>{
        console.log("change");
        location.href="/ctest/doctor-report?spec="+mainSelect.value;
    })
</script>