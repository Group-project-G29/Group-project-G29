<?php

use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/pharmacy-navbar.php";
?>

</nav>
    <div class="main-container">
<<<<<<< HEAD
         <?php $sidebar=new Sidebar(['Orders'=>'/ctest/pharmacy-orders-pending','Medicines'=>'/ctest/pharmacy-view-medicine','Advertisement'=>'/ctest/pharmacy-view-advertisement','Report'=>'/ctest/pharmacy-view-report','My Detail'=>'/ctest/pharmacy-view-personal-details'],$select);?>
         <?php echo $sidebar;  ?>
        
=======
         <?php $sidebar=new Sidebar(['Orders'=>'/ctest/pharmacist','Medicines'=>'/ctest/pharmacist','Advertisement'=>'#','Report'=>'#','My Detail'=>'/ctest/pharmacy-view-personal-details'],$select);?>
         <?php echo $sidebar;  ?>
        
        
>>>>>>> c9612a7f9b4ccb2af04ac81f9858298b29d36a1e
        <div class="sub-container">
            <div>
                <?php if(Application::$app->session->getFlash('success')):?>
                 <div class="flash-message">
                     <?php echo Application::$app->session->getFlash('success');?>
                 </div>
                <?php endif;?>
            </div>

<<<<<<< HEAD

=======
>>>>>>> c9612a7f9b4ccb2af04ac81f9858298b29d36a1e
            {{content}}
        </div>
    </div>
 
</body>
</html>