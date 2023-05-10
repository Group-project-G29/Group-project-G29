<?php
use app\core\component\Component;
use app\core\form\Form;

$component=new Component();
$form=new Form();
?>
<section class="patient-update">
        <div>
            <?php $form->begin('','post') ?>
            <table border='0'>
                <?=$form->spanfield($me,'name','Name :','field','text','');?>
                <?=$form->spanfield($me,'age','Age :','field','number','');?>
                <?=$form->spanselect($me,'gender','Gender :','field',['Select'=>'','Male'=>'male','Female'=>'female'],'');?>
                <?=$form->spanfield($me,'contact','Contact :','field','text','');?>
                <?=$form->spanfield($me,'email','Email :','field','text','');?>
                <?=$form->spanfield($me,'address','Address :','field','text','');?>
                <?php echo $form->spanfield($me,'img','Profile Picture','field','file') ?>
            </table>
        
            <?=$form->textarea($me,'description','description','Description :',5,100,$me->fetchAssocAllByName(['nic'=>$me->nic],"doctor")[0]['description']??'','');?>
        </div>
        <?=$component->button('btn','submit','Update','button--class-0','btn');?>
        <?php $form->end() ?>


</section>

