<?php
    use app\models\ChartModel;
    $chartModel=new ChartModel();
?>

<section class="summary-report">
    <div class="upper-info-container">
        <div class="upper-boxes">
            <h3><?=$patients?$patients:0?></h3>
        </div>
        <div class="upper-boxes">
            <h3><?=$channelingsCount?$channelingsCount:0?></h3>
        </div>
        <div class="upper-boxes">
            <h3><?=$income?$income:0?></h3>
        </div>
    </div>
    <div class="lower-info-container">
        <div class="lower-box">
            <canvas id='myChart-patient'></canvas>
            <?=$chartModel->lineChart($patientchart['labels'],[],$patientchart['values'],[],'Patient Visit Statistics','rgb(0,0,0)','-patient');?>
        </div>
        <div class="lower-box">
              <canvas id='myChart-income'></canvas>
            <?=$chartModel->lineChart($incomechart['labels'],[],$incomechart['values'],[],'Income Statistics','rgb(0,0,0)','-income');?>
        </div>
    </div>
    

</section>