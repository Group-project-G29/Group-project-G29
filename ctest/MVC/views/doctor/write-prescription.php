<?php

use app\core\Application;
use app\core\component\Component;
use app\core\form\Form;
use app\models\Prescription;

    $form=new Form();
    $component=new Component();
    $prescriptoinModel=new Prescription();
 
?>
<div class="background-wp pos hide">

</div>
<div class="as-wrapper hide">
    <div class="are-sure">
        You Cannot Add or Delete Medicine After Prescription is sent to the pharmacy.
        <div class="flex">
            <?=$component->button('accept','','Okay','button--class-0 width-7','pwp-ok'); ?>
            <?=$component->button('accept','','Cancel','button--class-0 width-7','pwp'); ?>
        </div>
    </div>
</div>
<div class="switcher shake-2">
    <img src=".\media\images\common\switch.png">
</div>
<script>
    const poper=document.querySelector(".switcher");
    poper.addEventListener('click',()=>{
        location.href="doctor-move";
    })

</script>
<section class="main-dashboard">
<section>
    <?php $form->begin('','post');?>
    <section>
        <div class="refill-con">
            <div>
                <?=$form->textarea($prescriptionModel,'note','note','Note',2,100,$prescriptionModel->note,''); ?>
            </div>
            <div class="refills">
            </div>
        </div>
        <br>
        <div class="prescription-field-container">
            <div class="cls-name">
            <?=$form->editableselect('name','Medical Product*','',$medicines);  ?>
            </div>
            <div class="cls-frequency">   
            <?=$form->editableselect('frequency','Frequency*','',['Daily'=>'Daily','BID(twice a day)'=>'BID','TID(Thrice a day)'=>'TID','QID(Four times a day)'=>'QID','QHS(Every bedtime)'=>'QHS','QWK(every week)'=>'QWK']); ?>
            </div>
           
            <div class="cls-amount">
            <?=$form->editableselect('amount','Amount per Dose*','',[]); ?>
            </div>
            <div class="cls-dispense">
            <?=$form->dispenseselect('dispense','Dispense','');?>
            </div>
            <section>
                <?php $pres=$prescriptoinModel->isTherePrescription(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling'))?>
                <?php $med=$prescriptoinModel->fetchAssocAllByName(['prescription_ID'=>$pres],'prescription_medicine');?>
                <?php $res=$prescriptoinModel->fetchAssocAll(['prescription_ID'=>$prescriptionModel->isTherePrescription(Application::$app->session->get('cur_patient'),Application::$app->session->get('channeling'))]) ?>
            </section>
            
            <?=$component->button('submit','submit','Add','button--class-0 width-7','addbtn'); ?>
            <?php if($med && !$res[0]['order_ID']):?>
                <?=$component->button('sendbtn','',"Send to Pharmacy",'button--class-0 width-10','sendp')?>
            <?php endif;?>
        </div>
    </section>
    <?php $form->end(); ?>

</section>
</section>
<section class="medicine-table">
    <?php if($prescription_medicine): ?>
    <table>
        <tr><th>Item</th><th>Frequency</th><th>Amount per Dose</th><th>Dispense</th><th></th></tr>
        <?php foreach($prescription_medicine as $med): ?>
            <tr class="medicine-item">
                <td align='center'><?=$med['name']."-".$med['strength'] ?></td>
                <td><?=$med['frequency'] ?></td>
                <td><?=$med['med_amount'] ?></td>
                <td><?=$med['dispense_count']." ".$med['dispense_type'] ?></td>
                <td class="sub" style="font-size:42;" id=<?=$med['med_ID'] ?>><div class="remv-wp"><?="Remove" ?></div></td>
        
            </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</section>
<section>
    <?php if($prescription_device): ?>
    <table>
        <tr><th>Item</th><th>Frequency</th><th>Use for</th></tr>
        <?php foreach($prescription_medicine as $med): ?>
            <tr>
                <td><?=$med['name'] ?></td>
                <td><?=$med['frequency'] ?></td>
                <td><?=$med['dispense_count']." ".$med['dispense_type'] ?></td>
                
        
            </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</section>

<script>
    const inputAmount=document.getElementById('input-amount');
    const itemsLocal=document.querySelectorAll('.itemsamount');
    inputAmount.addEventListener('focusout',()=>{
        inputvaluearray=(inputAmount.value).split(' ');
        value=inputvaluearray[0]+(' '+namecarry+'s');
        inputAmount.value=value;
    })
    const inptarray=document.querySelectorAll('.in');
    const nameLocal=document.querySelector('.sl-name');
    nameLocal.addEventListener('input',()=>{
        if((nameLocal.value).length==0)
        inptarray.forEach((el)=>{
            el.value='';
        });
    })

    //change on select
    const clsname=document.querySelector(".cls-name");
    const clsdamount=document.querySelector(".cls-dev-amount");
    const clsfrequency=document.querySelector(".cls-frequency");
    const clsdispense=document.querySelector(".cls-dispense");
    const clsamount=document.querySelector(".cls-amount");
    const mainaddbtn=document.getElementById('add-button');
    const selectItems=document.querySelectorAll('.ed-se-item-name');
    var allshow=[clsname,clsfrequency,clsdispense,clsdamount,clsamount];
    var arrayshow={'device':[clsname,clsfrequency,clsdispense,clsdamount],'tablet':[clsname,clsfrequency,clsdispense,clsamount],'bottle':[clsname,clsfrequency,clsdispense,clsamount]};
    selectItems.forEach((el)=>{
        el.addEventListener('click',()=>{
            comp=(""+el.id).split("_");
            allshow.forEach(elem=>{
                if(elem){
                    if(arrayshow[comp[1]].includes(elem)){
                        elem.classList.remove('hide');
                    }
                    else {
                        elem.classList.add('hide');
                    }
                }
            
            })
        })
    })

    const remar=document.querySelectorAll('.sub');
    remar.forEach((el)=>{
        el.addEventListener('click',(event)=>{
            event.preventDefault();
            location.href="doctor-prescription?spec=prescription&cmd=delete&id="+el.id
        })
    })
    const sendp=document.getElementById('sendp');
    const popup=document.querySelector('.as-wrapper');
    const bg=document.querySelector('.pos');
    const canc=document.getElementById('pwp');
    sendp.addEventListener('click',(event)=>{
        event.preventDefault();
        popup.classList.remove('hide');
        bg.classList.remove('hide');

    })
    const send=document.getElementById('pwp-ok');
    send.addEventListener('click',(event)=>{
            event.preventDefault();
            location.href="doctor-prescription?cmd=send";
        })

    canc.addEventListener('click',(event)=>{
        event.preventDefault();
        popup.classList.add('hide');
        bg.classList.add('hide');

    })
    bg.addEventListener('click',()=>{
        popup.classList.add('hide');
        bg.classList.add('hide');
    })
</script>
