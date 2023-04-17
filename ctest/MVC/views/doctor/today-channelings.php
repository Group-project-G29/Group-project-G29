<div class="doc-all-channeling">

        <?php

        use app\core\Application;
        use app\models\Appointment;

            foreach($opened_channeling as $key=>$value){
                $channeling=$channeling_model->findOne(['channeling_ID'=>$value['channeling_ID']]);
                $day="";
<<<<<<< HEAD
             
                if($channeling->day=='Friday'){
                if($channeling->type=="day" && $channeling->count=="1") $day=$channeling->starting_date ?? ''; else $day="All ".$channeling->day;
                echo "<tr class='table-row  row-height hover' id=".$value['opened_channeling_ID'].">
                        <td>".$channeling->speciality."-".$channeling->day."</td><td>".$day."</td><td>".$channeling->time."</td><td>".$value['remaining_appointments']."</td><td>".$value['status']."</td>
                    </tr>";
=======
                $appointmentModel=new Appointment();
                if(($value['channeling_date']==Date('Y-m-d') && ($value['status']=='Opened' || $value['status']=='started')) || $value['opened_channeling_ID']==41){
                echo "<div class='today_channeling_tiles' id=".$value['opened_channeling_ID'].">
                        <div class='grid".rand(1,4)." font-32'>".$channeling->time." ".((($channeling->time)>='12.00')?'PM':'AM')."</div>
                        <div class='today-tile-content'><h3>".$channeling->speciality." channeling</h3>
                                                        <h4>Room :".$channeling->room."</h4>
                                                        <h4>Appointments :".$appointmentModel->getAppointmentCount($value['opened_channeling_ID'])."</h4>
                        </div>
                        
                       
                    </div>";
>>>>>>> 20002051
                }
            }
        
        ?>
        
  
</div>
<script>
        elementsArray = document.querySelectorAll(".today_channeling_tiles");
        elementsArray.forEach(function(elem) {
            elem.addEventListener("click", function() {
                location.href='channeling?channeling='+elem.id;
            });
        });
</script>