<?php

use app\core\component\Component;
use app\core\form\Form;
    $form=new Form();
    $component=new Component();
?>

<section>
    <?php $form->begin('','post'); ?>
    <div class="prescription-field-container">
        <div class="cls-name">
        <?=$form->editableselect('name','Medical Product*','',$medicines); ?>
        </div>
         <div class="cls-frequency">   
        <?=$form->editableselect('frequency','Frequency*','',['frequency1'=>'frequency1']); ?>
        </div>  
        <div class="cls-amount">
        <?=$form->editableselect('amount','Amount per Dose*','',[]); ?>
        </div>
        <div class="cls-dispense">
        <?=$form->dispenseselect('dispense','Dispense','');?>
        </div>
        <div class="cls-route ">
        <?=$form->editableselect('route','Route','',['Oral'=>'Oral','Rectal'=>'Rectal','Intravenouse'=>'Intravenouse']); ?>
        </div>
        <?=$component->button('submit','submit','Add','','addbtn'); ?>
    </div>
    <?php $form->end(); ?>

</section>
<section>
    <?php if($prescription_medicine): ?>
    <table>
        <tr><th>Item</th><th>Frequency</th><th>Amount per Dose</th><th>Dispense</th><th>Route</th></tr>
        <?php foreach($prescription_medicine as $med): ?>
            <tr>
                <td><?=$med['name']."-".$med['strength'] ?></td>
                <td><?=$med['frequency'] ?></td>
                <td><?=$med['med_amount'] ?></td>
                <td><?=$med['dispense_count']." ".$med['dispense_type'] ?></td>
                <td><?=$med['route'] ?></td>
        
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
    const clsstrength=document.querySelector(".cls-strength");
    const clsfrequency=document.querySelector(".cls-frequency");
    const clsdispense=document.querySelector(".cls-dispense");
    const clsroute=document.querySelector(".cls-route");
    const mainaddbtn=document.getElementById('add-button');
    const selectItems=document.querySelectorAll('.ed-se-item-name');
    var allshow=[clsname,clsfrequency,clsstrength,clsroute,clsdispense];
    var arrayshow={'device':[clsname,clsfrequency,clsdispense],'tablet':[clsname,clsfrequency,clsdispense,clsroute],'bottle':[clsname,clsstrength,clsfrequency,clsdispense]};
    selectItems.forEach((el)=>{
        el.addEventListener('click',()=>{
            comp=(""+el.id).split("_");
            allshow.forEach(elem=>{
                if(arrayshow[comp[1]].includes(elem))
                elem.classList.remove('hide');
                
                else 
                elem.classList.add('hide');
            
            })
        })
    })
</script>
<script src="./node_modules/chart.js/dist/chart.umd.js"></script>
<script type="module">
import Chart from './node_modules/chart.js/dist/chart.js/auto';

(async function() {
  const data = [
    { year: 2010, count: 10 },
    { year: 2011, count: 20 },
    { year: 2012, count: 15 },
    { year: 2013, count: 25 },
    { year: 2014, count: 22 },
    { year: 2015, count: 30 },
    { year: 2016, count: 28 },
  ];

  new Chart(
    document.getElementById('acquisitions'),
    {
      type: 'bar',
      data: {
        labels: data.map(row => row.year),
        datasets: [
          {
            label: 'Acquisitions by year',
            data: data.map(row => row.count)
          }
        ]
      }
    }
  );
})();
</script>