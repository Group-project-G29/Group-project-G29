<?php

use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/patient-navbar.php";
?>
    <div class="bg">

</div>
    <div class="main-container">

         <?php $sidebar=new Sidebar(['Appointments'=>'patient-dashboard?spec=appointments','My Documentation'=>'patient-dashboard?spec=documentation','My Orders'=>'patient-dashboard?spec=orders','My Payments'=>'patient-dashboard?spec=payments','Medical Analysis'=>'patient-dashboard?spec=medical-analysis','My Detail'=>'patient-dashboard?spec=my-detail'],$select);?>

         <?php echo $sidebar;  ?>
        
        
        <div class="sub-container">
            <div>
                <?php if(Application::$app->session->getFlash('success')):?>
                 <div class="flash-message">
                     <?php echo Application::$app->session->getFlash('success');?>
                 </div>
                <?php endif;?>

            </div>



            {{content}}
        </div>
    </div>
 
</body>
</html>