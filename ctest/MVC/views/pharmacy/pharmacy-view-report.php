<?php
    use app\core\component\Component;
    $component=new Component();
?>

<div class="cards">
    <div class="card">
        <div class="card-content">
            <div class="number"><?=$order_count['Online orders'] + $order_count['Frontdesk Orders']?></div>
            <div class="card-name">No of orders on Today</div>
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="number"><?= $to_be_processed_orders ?></div>
            <div class="card-name">Orders to be processed via Online</div>
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="number"><?= $no_of_orders_this_month ?></div>
            <div class="card-name">No of Orders in this month</div>
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="number"><?= 'LKR. '. number_format($income_in_this_month,2,'.','') ?></div>
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
        <h2>Today Orders</h2>
        <?php if( $order_count['Online orders']==0 && $order_count['Frontdesk Orders']==0 ): ?>
            <center><div>
                <h1>No orders yet</h1>
            </div></center>
        <?php else: ?>
            <canvas id="itemChart" height="240px"></canvas>
        <?php endif; ?>
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
                datasets: [
                    {
                        label: 'Income from Online Orders (LKR)',
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
                    }, {
                        label: 'Income from Frontdesk Orders (LKR)',
                        data: [
                            <?=$frontdesk_medicine_income[0][2]?>,
                            <?=$frontdesk_medicine_income[1][2]?>,
                            <?=$frontdesk_medicine_income[2][2]?>,
                            <?=$frontdesk_medicine_income[3][2]?>,
                            <?=$frontdesk_medicine_income[4][2]?>,
                            <?=$frontdesk_medicine_income[5][2]?>,
                            <?=$frontdesk_medicine_income[6][2]?>,
                            <?=$frontdesk_medicine_income[7][2]?>,
                            <?=$frontdesk_medicine_income[8][2]?>,
                            <?=$frontdesk_medicine_income[9][2]?>,
                            <?=$frontdesk_medicine_income[10][2]?>,
                            <?=$frontdesk_medicine_income[11][2]?>
                        ],
                        backgroundColor:[
                            '#1746A2'
                        ],
                        borderColor: [
                            '#1746A2'
                        ],
                        borderWidth: 2
                    }, {
                        label: 'Total Income (LKR)',
                        data: [
                            

                            <?=$medicine_income[0][2]+$frontdesk_medicine_income[0][2]?>,
                            <?=$medicine_income[1][2]+$frontdesk_medicine_income[1][2]?>,
                            <?=$medicine_income[2][2]+$frontdesk_medicine_income[2][2]?>,
                            <?=$medicine_income[3][2]+$frontdesk_medicine_income[3][2]?>,
                            <?=$medicine_income[4][2]+$frontdesk_medicine_income[4][2]?>,
                            <?=$medicine_income[5][2]+$frontdesk_medicine_income[5][2]?>,
                            <?=$medicine_income[6][2]+$frontdesk_medicine_income[6][2]?>,
                            <?=$medicine_income[7][2]+$frontdesk_medicine_income[7][2]?>,
                            <?=$medicine_income[8][2]+$frontdesk_medicine_income[8][2]?>,
                            <?=$medicine_income[9][2]+$frontdesk_medicine_income[9][2]?>,
                            <?=$medicine_income[10][2]+$frontdesk_medicine_income[10][2]?>,
                            <?=$medicine_income[11][2]+$frontdesk_medicine_income[11][2]?>
                        ],
                        backgroundColor:[
                            'red'
                        ],
                        borderColor: [
                            'red'
                        ],
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true
            }
        }
    );

    const order_count = <?= json_encode($order_count); ?>;

    var ctx = document.getElementById('itemChart').getContext('2d');
    var itemChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Online orders','Frontdesk Orders'],
            datasets: [{
                // label: 'My Pie Chart',
                data: [<?=$order_count['Online orders']?>,<?=$order_count['Frontdesk Orders']?>],
                backgroundColor: [
                    '#1746A2',
                    '#38B6FF'
                ]
            }]
        },
        options: {
            title: {
                display: false,
                text: 'Categories of Requests',
                fontSize: 24,
                fontColor: '#000',
                fontFamily: 'inter'
            },
            legend: {
                display: true,
                position: 'top',
                labels: {
                    boxWidth: 10,
                    fontSize: 10,
                }
            },
            layout:{
                padding: 0
           }
        }
    });

</script>
