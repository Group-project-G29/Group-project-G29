<?php

use app\models\ChartModel;
use app\models\LabReport;

    $keys=array_keys($mainArray);
    $labreports=new LabReport();
    $chartModel=new ChartModel();
    

?>

<section class="medical-analysis-section">
    <section class="medical-analysis-types">
        <table>
            <?php foreach($keys as $key): ?>
                <tr>
                    <td class="test-key" id=<?="'".join('-',explode(' ',$key))."'" ?>>
                        <center>
                        <div >
                            <?php echo $key; ?>
                    
                        </div>
                        </center>
                    </td>
                <tr>
            <?php endforeach ;?>
        </table>
    </section>
    <section class="medical-analysis-charts">
        <?php foreach($keys as $key): ?>
            <div class="test-chart" id=<?="'".join('-',explode(' ',$key))."'" ?>>
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
 
            testCharts.forEach((elem)=>{
                
                    elem.style.display='none';
            })
   
    testItems.forEach((elemq)=>{
        elemq.addEventListener('click',()=>{
            testItems.forEach((elemq)=>{
                elemq.classList.remove('medical-analysis-types-select');
        
            })
            clicked_item=""+elemq.id;
            elemq.classList.add('medical-analysis-types-select');
            testCharts.forEach((elem)=>{
                if(""+elem.id==clicked_item){
                    elem.style.display='block';


                }
                else{
                    elem.style.display='none'
                }
            })
        })
    })
</script>
