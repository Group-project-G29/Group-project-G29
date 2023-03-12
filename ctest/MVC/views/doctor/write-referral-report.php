<?php
    use app\core\component\Component;
use app\core\form\Form;

    $form = new Form();
    $component=new Component();
?>
<?php $form->begin('','post')  ?>
<div>
    <div>
        <div>
            <div>
                <?= $form->spanselect($model,'type','','field',['SOAP Report'=>'soap-report','Consultation Report'=>'consultation-report','Medical History Report'=>'medical-history-report','Referral'=>'referral'],'select-main') ?>

            </div>
            <?= $form->spanselect($model,'doctor','Refer to Doctor','field',$doctors,''); ?>
            <?= $form->spanselect($model,'speciality','Select speciality','field',$specialities,'select-main') ?>
            
        </div>
        <div>
            <label>Refer External Party :</label><input type="checkbox" class="checkbox" > 
        </div>
        <div class="choose-doctor hide">
            <div>
                <?= $form->field($model,'thirdparty','Refer to(Practitioner/Institute/Department)','field','text'); ?>
            
            </div>
        </div>
        <div>
            <?= $component->button('','submit','Add Report','button--class-0'); ?>
        </div>
    </div>
    <div>
        <?= $form->textarea($model,'history','history','Patient Medical History',10,130,'');?>
        
    </div>
    <div>
        <?= $form->textarea($model,'reason','reason','Reason for Referral',10,130,'');?>
        
    </div>
    <div>
        <?= $form->textarea($model,'assessment','assessment','Medical Assessment',10,130,'');?>

    </div>    
    <div>
        <?= $form->textarea($model,'note','note','Note',10,130,'');?>
        
    </div>


</div>
<?php $form->end(); ?>
<script src="./media/js/main.js"></script>
<script>
    const mainSelect=e('select-main','id');
    mainSelect.value="referral";
    mainSelect.addEventListener('change',()=>{

        location.href="/ctest/doctor-report?spec="+mainSelect.value;
    })
    const optionSelect=e('.checkbox','class');
    const chooseDiv=e('.choose-doctor','class');
    optionSelect.addEventListener('change',()=>{
        if(!optionSelect.checked){
            hide(chooseDiv);
        }
        else{
            visible(chooseDiv);
        }
    });

</script>