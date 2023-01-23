<?php

use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/patient-navbar.php";
?>
    <div class="bg">

</div>
    <div class="main-container">
         <?php $sidebar=new Sidebar(['Appointments'=>'#','My Documentation'=>'#','My Orders'=>'#','My Payments'=>'#','Medical Analysis'=>'#','My Detail'=>'#'],$select);?>
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