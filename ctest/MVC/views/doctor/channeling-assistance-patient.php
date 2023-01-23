<div class="channeling-patient">
    <?php

use app\core\component\Component;

 $appointment=$appointment[0]; ?>
    <?php $component=new Component(); ?>
    <div class="number-pad">
        <div class="number-item--white">
            <?=$appointment['queue_no']?>
           
        </div>
        <div class="number-item--blue">
            <?=$appointment['total_patients']?>
        </div>
    </div>
    <div class="patient-navigator">
        <img src="media/images/channeling assistance/left-chevron.png" class="previous" id=<?="previous-".$appointment['patient_ID']?>>
        <h3>Patient Name : <?=$appointment['name']?></h3>
        <img src="media/images/channeling assistance/right-chevron.png" class="next" id=<?="next-".$appointment['patient_ID']?>>
    </div>
    <div>
        Checked Patient :<input type="checkbox" name="checked" id="checkbox">
    </div>
    <div>
        <table>
            <td>Age :</td><td><?=$appointment['age']." yrs"?></td>
            <td>Gender :</td><td><?=$appointment['gender']?></td>
        </table>
    </div>
    <div>
        <?php  $component->button('referal','','Referal','button--class-3');?>
        <?php  $component->button('consultaion','','Last consultation report','button--class-3');?>
    </div>
    <div>
        <table class="flex-column">
            <td>Blood Sugar :</td><td>99 mg/dL</td>
            <td>Blood Pressure(systolic) :</td><td>120 mmHg</td>
            <td>Weight :</td><td>70 kg</td>
            <td>Height :</td><td>170.6 cm</td>
        </table>
    </div>
    <div>
        <?php  $component->button('report','','View Reports','button--class-3');?>
        <?php  $component->button('prescription','','View Prescription','button--class-3');?>
        <?php  $component->button('labtest','','View Lab Tests','button--class-3');?>
        <?php  $component->button('medical_analysis','','Medical Analysis','button--class-3');?>
    </div>

</div>

<div>
    <div>
        <?php use app\core\form\Form; ?>
        <?php $form=Form::begin('','post') ?>
        <div class="flex-column">
            <div class="flex">
                <h3>Next Visit :</h3><h3>Day</h3><input type="text"><h3>Month</h3><input type="text"><h3>Year</h3><input type="text">
            </div>
            <div class="flex">
                <h3>Set Free Appointment</h3><input type="checkbox">
            </div>
            <div class="flex">
                <h3>Expires In :</h3><h3>Day</h3><input type="text"><h3>Month</h3><input type="text"><h3>Year</h3><input type="text">
            </div>
        </div>
        <?php Form::end() ?>
    </div>
   
</div>
<script>

        const next=document.querySelector(".next");
        const previous=document.querySelector(".previous");
    
        next.addEventListener('click',()=>{
            id_component=(next.id).split("-");
            console.log(id_component);
            location.href="channeling-assistance?cmd=next&id="+id_component[1];
        })
        previous.addEventListener('click',()=>{
            id_component=(next.id).split("-");
            location.href="channeling-assistance?cmd=previous&id="+id_component[1];
        })

    </script>