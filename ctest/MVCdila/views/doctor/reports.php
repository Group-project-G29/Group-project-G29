<?php
    use app\models\ChartModel;
    $chartModel=new ChartModel();
?>

<section class="summary-report">
    <div class="upper-info-container">
        <div class="upper-boxes">
            <h3>
                <?=$patients?$patients:0?>
            </h3>
            <h4>This Month Patients</h4>
        </div>
        <div class="upper-boxes">
            <h3>
                <?=$channelingsCount?$channelingsCount:0?>
            </h3>
            <h4>Opened Channeling</h4>
        </div>
        <div class="upper-boxes">
            <h3>
                <?="LKR".($income?$income:0).".00"?>
            </h3>
            <h4>This Month Income</h4>
        </div>
    </div>
    <div class="lower-info-container">
        <div class="lower-box">
            <canvas id='myChart-patient' class="fix-width"></canvas>
            <?=$chartModel->lineChart($patientchart['labels'],[],$patientchart['values'],[],'Patient Visit Statistics','rgb(0,0,0)','-patient');?>
        </div>
        <div class="lower-box">
              <canvas id='myChart-income' class="fix-width"></canvas>
            <?=$chartModel->lineChart($incomechart['labels'],[],$incomechart['values'],[],'Income Statistics','rgb(0,0,0)','-income');?>
        </div>
    </div>
    

</section>