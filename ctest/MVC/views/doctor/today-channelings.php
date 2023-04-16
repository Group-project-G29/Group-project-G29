<div >

        <?php

        use app\core\Application;
        use app\models\Appointment;

            foreach($opened_channeling as $key=>$value){
                $channeling=$channeling_model->findOne(['channeling_ID'=>$value['channeling_ID']]);
                $day="";
                $appointmentModel=new Appointment();
                if($value['channeling_date']==Date('Y-m-d') && $value['status']=='Opened' || $value['opened_channeling_ID']==41){
                echo "<div class='today_channeling_tiles' id=".$value['opened_channeling_ID'].">
                        <div class='today-tile-time'>".$channeling->time." ".((($channeling->time)>='12.00')?'PM':'AM')."</div>
                        <div class='today-tile-content'><h3>".$channeling->speciality." channeling</h3>
                                                        <h4>Room :".$channeling->room."</h4>
                        </div>
                        
                       
                    </div>";
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