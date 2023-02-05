<?php

use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/patient-navbar.php";
?>
    <div class="bg">

</div>
    <div class="main-container">
<<<<<<< HEAD
         <?php $sidebar=new Sidebar(['Appointments'=>'patient-dashboard?spec=appointments','My Documentation'=>'patient-dashboard?spec=documentation','My Orders'=>'patient-dashboard?spec=orders','My Payments'=>'patient-dashboard?spec=payments','Medical Analysis'=>'patient-dashboard?spec=medical-analysis','My Detail'=>'patient-dashboard?spec=my-detail'],$select);?>
=======
         <?php $sidebar=new Sidebar(['Appointments'=>'#','My Documentation'=>'#','My Orders'=>'#','My Payments'=>'#','Medical Analysis'=>'#','My Detail'=>'#'],$select);?>
>>>>>>> c9612a7f9b4ccb2af04ac81f9858298b29d36a1e
         <?php echo $sidebar;  ?>
        
        
        <div class="sub-container">
            <div>
                <?php if(Application::$app->session->getFlash('success')):?>
                 <div class="flash-message">
                     <?php echo Application::$app->session->getFlash('success');?>
                 </div>
                <?php endif;?>
<<<<<<< HEAD
                <?php if(Application::$app->session->getFlash('error')):?>
                 <div class="flash-message">
                     <?php echo Application::$app->session->getFlash('error');?>
                 </div>
                <?php endif;?>
=======
>>>>>>> c9612a7f9b4ccb2af04ac81f9858298b29d36a1e
            </div>



            {{content}}
        </div>
    </div>
 
</body>
</html>