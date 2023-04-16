<?php
use app\core\component\Component;
use app\core\form\Form;

$component=new Component();
$form=new Form();
?>
<section>
    <?php if($patient->type!='pediatric'):?>
        <div>
            <?php $form->begin('','post') ?>
            <table border='0'>
                <?=$form->spanfield($patient,'name','Name :','field','text','');?>
                <?=$form->spanfield($patient,'nic','NIC :','field','text','');?>
                <?=$form->spanfield($patient,'age','Age :','field','number','');?>
                <?=$form->spanselect($patient,'gender','Gender :','field',['Select'=>'','Male'=>'male','Female'=>'female'],'');?>
                <?=$form->spanfield($patient,'contact','Contact :','field','text','');?>
                <?=$form->spanfield($patient,'email','Email :','field','text','');?>
                <?=$form->spanfield($patient,'address','Address :','field','text','');?>
            </table>
        </div>
        <?=$component->button('btn','submit','Update','btn-classs--0','btn');?>
        <?php $form->end() ?>
        <?php else:?>
    <?php endif;?>


</section>

<script>
    const btn=document.getElementById('btn');
    btn.addEventListener('click',()=>{
        location.href="patient-my-detail?mod=update"
    })
</script>