<?php

use app\core\Calendar;
use app\models\Prescription;

    $pres=new Prescription();
    $calendarModel=new Calendar();
    var_dump($calendarModel->generateDays('2023-05-09','Tuesday','Monday','1 weeks','1 week'));
 

?>
<section>
      <?php echo $pres->getPrice(70);?>
</section>
