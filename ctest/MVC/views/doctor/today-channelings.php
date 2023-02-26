<div class="table-container">

    <table border="0">
        <tr class="row-height header-underline">
            <th>Channeling</th> <th>Date</th> <th>Time</th> <th>Appointments</th> <th>Status</th>
        </tr>
        <?php

        use app\core\Application;
        use app\models\Appointment;

            foreach($opened_channeling as $key=>$value){
                $channeling=$channeling_model->findOne(['channeling_ID'=>$value['channeling_ID']]);
                $day="";
                $appointmentModel=new Appointment();
                if($channeling->day=='Friday'){
                if($channeling->type=="day" && $channeling->count=="1") $day=$channeling->starting_date ?? ''; else $day="All ".$channeling->day;
                echo "<tr class='table-row  row-height hover' id=".$value['opened_channeling_ID'].">
                        <td>".$channeling->speciality."-".$channeling->day."</td><td>".$day."</td><td>".$channeling->time."</td><td>".$appointmentModel->getTotoalPatient(Application::$app->session->get('channeling'))."</td><td>".$value['status']."</td>
                    </tr>";
                }
            }
        
        ?>
        
    </table>
  
</div>
<script>
        elementsArray = document.querySelectorAll(".table-row");
        elementsArray.forEach(function(elem) {
            elem.addEventListener("click", function() {
                location.href='channeling?channeling='+elem.id;
            });
        });
</script>