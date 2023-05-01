<?php

use app\models\Prescription;

    $pres=new Prescription();
 

?>
<section>
      <?php echo $pres->getPrice(70);?>
</section>