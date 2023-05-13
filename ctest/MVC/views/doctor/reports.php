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
    <div class="summary-link">
        <a href="summary-reports?spec=channeling_report">Click Here</a> to See Channeling Summary Report
    </div>
    <div class="lower-info-container">
        <div class="lower-box">
            <h2>Patients Seen in Last 12 Months</h2>
        <canvas id='myChart-patient' class="fix-width"></canvas>
            <?=$chartModel->lineChartAssis($patientchart['labels'],[],$patientchart['values'],[],'Patient Visit Statistics','lightblue','-patient',0,2000);?>
        </div>
        <div class="lower-box">
            <h2>Income in Last 12 Months</h2>
              <canvas id='myChart-income' class="fix-width"></canvas>
            <?=$chartModel->lineChartAssis($incomechart['labels'],[],$incomechart['values'],[],'Income Statistics','lightblue','-income',1000,1000000);?>
        </div>
    </div>
    <div class="l-lower-info-container">
        <div class="lower-box">
            <h2>Number of Appointments by each Channeling(30 days)</h2>
            <canvas id='myChart-each' class="fix-width"></canvas>
            <?=$chartModel->barcharth($eachchanneling['label'],[],$eachchanneling['data'],[],'Patient Visit Statistics','lightblue','-each','Number of Appiointments');?>
        </div>
         <div class="lower-box">
            <h2>Lab Test appointment and Consultation Appointment Comparison(30 days)</h2>
            <canvas id='myChart-comp' class="fix-width"></canvas>
            <?php if(!$comparison['data']) 
                $comparison=['label'=>['0'],'data'=>[0]]
            ?>
            <?=$chartModel->piechart($comparison['label'],$comparison['data'],'-comp','Appointment Type');?>
        </div>
    </div>
    

</section>