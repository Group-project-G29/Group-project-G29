
<div class="cards">
    <div class="card">
        <div class="card-content">
            <div class="number"><?=$patientsCount?></div>
            <div class="card-name">Patients</div>
        </div>
        <div class="icon-box">
            <img src="./media/images/icons/bed-pulse-solid.svg" alt="">
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="number"><?=$doctorsCount?></div>
            <div class="card-name">Doctors</div>
        </div>
        <div class="icon-box">
            <img src="./media/images/icons/user-doctor-solid.svg" alt="">
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="number"><?=$employeesCount?></div>
            <div class="card-name">Employees</div>
        </div>
        <div class="icon-box">
            <img src="./media/images/icons/users-solid.svg" alt="">
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="number"><?=$thisMonthEarnings?></div>
            <div class="card-name">Earnings</div>
        </div>
        <div class="icon-box">
            <img src="./media/images/icons/rupee-sign-solid.svg" alt="">
        </div>
    </div>
</div>

<div class="charts">
    <div class="chart">
        <h2>Earnings (past 12 months)</h2>
        <canvas id="line-chart"></canvas>
    </div>
    <div class="chart" id="bar-chart-div">
        <h2>Number of patients (past 12 months)</h2>
        <canvas id="bar-chart"></canvas>
    </div>
</div>

<div class="charts-2">
    <div class="chart">
        <h2>Total Earnings</h2>
        <canvas id="Doughnut-chart"></canvas>
    </div>
    <div class="chart">
        <h2 class="chart-topic">Download Reports For This Month</h2>
        <div class="reports">
            <div class="reports-topic">Patiyents Statistics Reports</div>
            <button class="btn" onclick="paytient()">Download</button>
        </div>
        <div class="reports">
            <div class="reports-topic">income Reports</div>
            <button class="btn" onclick="payment()">Download</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx1 = document.getElementById('line-chart');

    new Chart(ctx1, {
        type: 'line',
        data: {
        labels: ['Jan', 'Feb', 'Mar', 'App', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Earnings in (Rs)',
            data: [<?=$valuesE[0]?>, <?=$valuesE[1]?>, <?=$valuesE[2]?>, <?=$valuesE[3]?>, <?=$valuesE[4]?>, <?=$valuesE[5]?>, <?=$valuesE[6]?>, <?=$valuesE[7]?>, <?=$valuesE[8]?>, <?=$valuesE[9]?>, <?=$valuesE[10]?>, <?=$valuesE[11]?>],
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
<script>
    const ctx2 = document.getElementById('bar-chart');

    new Chart(ctx2, {
        type: 'bar',
        data: {
        labels: ['Jan', 'Feb', 'Mar', 'App', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: '',
            data: [<?=$valuesP[0]?>, <?=$valuesP[1]?>, <?=$valuesP[2]?>, <?=$valuesP[3]?>, <?=$valuesP[4]?>, <?=$valuesP[5]?>, <?=$valuesP[6]?>, <?=$valuesP[7]?>, <?=$valuesP[8]?>, <?=$valuesP[9]?>, <?=$valuesP[10]?>, <?=$valuesP[11]?>],
            borderWidth: 1
        }]
        },
        options: {
            responsive: true
        }
    });
</script>
<script>
    const ctx3 = document.getElementById('Doughnut-chart');

    new Chart(ctx3, {
        type: 'doughnut',
        data: {
        labels: ['<?=$employeeEarnings[0]['type']?>', '<?=$employeeEarnings[1]['type']?>', '<?=$employeeEarnings[2]['type']?>'],
        datasets: [{
            label: 'Employees',
            data: [<?=$employeeEarnings[0]['SUM(amount)']?>, <?=$employeeEarnings[1]['SUM(amount)']?>, <?=$employeeEarnings[2]['SUM(amount)']?>],
            // backgroundColor:[
            //     'red',
            //     'green',
            //     'blue'
            // ],
            borderWidth: 1
        }]
        },
        options: {
            responsive: true
        }
    });
</script>

<script>
    function paytient(){
        location.href='test1?cmd=patients';
    }

    function payment(){
        location.href='test1?cmd=payments';
    }
</script>