<?php

use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/receptionist-navbar.php";

?>
<div class="bg">

</div>
    <div class="main-container">
<<<<<<< HEAD
<<<<<<< HEAD
         <?php $sidebar=new Sidebar(['Today Channelings'=>'#','All Channelings'=>'#','Patients'=>'/ctest/receptionist-handle-patient?mod=view','My Detail'=>'#'],$select);?>
=======
         <?php $sidebar=new Sidebar(['Today Channelings'=>'#','All Channelings'=>'#','Patients'=>'/ctest/receptionist-handle-patient?mod=view','My Detail'=>'/ctest/receptionist-view-personal-details'],$select);?>
>>>>>>> 20000804
=======
         <?php $sidebar=new Sidebar(['Today Channelings'=>'/ctest/receptionist-today-channelings','All Channelings'=>'/ctest/receptionist-all-channelings','Patients'=>'/ctest/receptionist-handle-patient?mod=view','My Detail'=>'/ctest/receptionist-view-personal-details'],$select);?>
>>>>>>> c9612a7f9b4ccb2af04ac81f9858298b29d36a1e
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
<<<<<<< HEAD
=======

>>>>>>> c9612a7f9b4ccb2af04ac81f9858298b29d36a1e
        </div>
    </div>
 
</body>
</html>