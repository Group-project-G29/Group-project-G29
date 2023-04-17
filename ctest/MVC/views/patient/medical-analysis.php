<?php

use app\models\ChartModel;
use app\models\LabReport;

    $keys=array_keys($mainArray);
    $labreports=new LabReport();
    $chartModel=new ChartModel();
    

?>

<section class="medical-analysis-section">
    <section class="medical-analysis-types">
        <?php foreach($keys as $key): ?>
            <div class="test-key" id=<?="'".join('-',explode(' ',$key))."'" ?>>
                <?php echo $key; ?>
            </div>
        <?php endforeach ;?>
    </section>
    <section class="medical-analysis-charts">
        <?php foreach($keys as $key): ?>
            <div class="test-chart hide" id=<?="'".join('-',explode(' ',$key))."'" ?>>
                <?php $row=$labreports->makeChartInputs($mainArray[$key]); ?>
                <canvas id=<?="'myChart".join('-',explode(' ',$key))."'"?>></canvas>
                <?=$chartModel->barchart($row['labels'],[],$row['values'],[],[],'',join('-',explode(' ',$key)),join('-',explode(' ',$key))); ?>
            </div>
        <?php endforeach ;?>
        
     </section>
</section>

<script>
    const testItems=document.querySelectorAll(".test-key");
    const testCharts=document.querySelectorAll(".test-chart");
    testItems.forEach((elem)=>{
        elem.addEventListener('click',()=>{
            clicked_item=""+elem.id;
            testCharts.forEach((elem)=>{
                if(""+elem.id==clicked_item){
                    elem.classList.remove('hide');
                }
                else{
                    elem.classList.add('hide');
                }
            })
        })
    })
</script>
