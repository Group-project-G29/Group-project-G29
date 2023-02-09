<?php

use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/receptionist-navbar.php";

?>
<div class="bg">

</div>
    <div class="main-container">

         <?php $sidebar=new Sidebar(['Today Channelings'=>'/ctest/receptionist-today-channelings','All Channelings'=>'/ctest/receptionist-all-channelings','Patients'=>'/ctest/receptionist-handle-patient?mod=view','My Detail'=>'/ctest/receptionist-view-personal-details'],$select);?>
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