<?php
    use app\core\component\Component;
    $component=new Component();
    // var_dump($medicine_income[0][1]);exit;

?>

<div class="cards">
    <div class="card">
        <div class="card-content">
            <div class="number">45</div>
            <div class="card-name">No of orders on Today</div>
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <!-- <div class="number"><?=$doctorsCount?></div> -->
            <div class="card-name">Orders to be processed</div>
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <!-- <div class="number"><?=$employeesCount?></div> -->
            <div class="card-name">No of Orders in this month</div>
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <!-- <div class="number"><?=$thisMonthEarnings?></div> -->
            <div class="card-name">This Month Income</div>
        </div>
    </div>
</div>

<div class="charts">

    <div class="chart">
        <h2>Medicine Income (past 12 months)</h2>
        <canvas id="line-chart"></canvas>
    </div>

    <div class="chart" >
        <h2>Number of orders</h2>
    </div>

</div>

<!-- ======= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx1 = document.getElementById('line-chart');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: [
                '<?= $medicine_income[0][1].'-'.$medicine_income[0][0]?>',
                '<?= $medicine_income[1][1].'-'.$medicine_income[1][0]?>',
                '<?= $medicine_income[2][1].'-'.$medicine_income[2][0]?>',
                '<?= $medicine_income[3][1].'-'.$medicine_income[3][0]?>',
                '<?= $medicine_income[4][1].'-'.$medicine_income[4][0]?>',
                '<?= $medicine_income[5][1].'-'.$medicine_income[5][0]?>',
                '<?= $medicine_income[6][1].'-'.$medicine_income[6][0]?>',
                '<?= $medicine_income[7][1].'-'.$medicine_income[7][0]?>',
                '<?= $medicine_income[8][1].'-'.$medicine_income[8][0]?>',
                '<?= $medicine_income[9][1].'-'.$medicine_income[9][0]?>',
                '<?= $medicine_income[10][1].'-'.$medicine_income[10][0]?>',
                '<?= $medicine_income[11][1].'-'.$medicine_income[11][0]?>'
            ],
            datasets: [{
                label: 'Income (Rs)',
                data: [
                    <?=$medicine_income[0][2]?>,
                    <?=$medicine_income[1][2]?>,
                    <?=$medicine_income[2][2]?>,
                    <?=$medicine_income[3][2]?>,
                    <?=$medicine_income[4][2]?>,
                    <?=$medicine_income[5][2]?>,
                    <?=$medicine_income[6][2]?>,
                    <?=$medicine_income[7][2]?>,
                    <?=$medicine_income[8][2]?>,
                    <?=$medicine_income[9][2]?>,
                    <?=$medicine_income[10][2]?>,
                    <?=$medicine_income[11][2]?>
                ],
                backgroundColor:[
                    '#38B6FF'
                ],
                borderColor: [
                    '#38B6FF'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true
        }
    });


</script>

