
<?php

use app\core\component\Component;
use app\core\Application;
    $component=new Component();
    use app\models\OpenedChanneling;
        $openedChanneling=new OpenedChanneling();
       
   
       
    
?>
<div class="appointment-container ">
        <?php foreach($channelings as $key=>$value): ?>
            <div class="item">
                <div class="item--left">
                    <div>
                        <img src=<?="./media/images/emp-profile-pictures/".$value['img']?>>
                    </div>
                    <div class="about-info">
                        <h3>DR. <?=$value['name']?></h3>
                        <h3><?=$value['speciality']?></h3>
                        <p><?=$value['description']?></p>
                        
                    </div>
                </div>
                <div class="item--right">
                    <div class="channeling-vital-info">
                        <h3>Time :<?=$value['time']?></h3>
                        <h3>Date :<?=$value['channeling_date']?></h3>
                        <h3>Fee :LKR <?=$value['fee']?></h3>
                    </div>
                    <?php if($openedChanneling->isPatientIn(Application::$app->session->get('user'),$value['opened_channeling_ID'])):?>
                    <?php echo "Already have an appointment" ?>
                    <?php else: ?>
                        <?= $component->button('add-appointment','','+ Add Appointment','button--class-1',$value['opened_channeling_ID']);?>
                    <?php endif; ?>
                </div>
            </div>
            
        <?php endforeach; ?>
  
</div>

<script>

        let elementsArray = document.querySelectorAll(".button--class-1");
  
        elementsArray.forEach(function(elem) {
            elem.addEventListener("click", function() {
                location.href='patient-appointment?cmd=add&id='+elem.id;
               ;
            });
        });
     
   </script>