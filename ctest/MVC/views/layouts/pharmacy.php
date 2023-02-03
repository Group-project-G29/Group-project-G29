<?php

use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/pharmacy-navbar.php";
?>

</nav>
    <div class="main-container">
<<<<<<< HEAD
         <?php $sidebar=new Sidebar(['Orders'=>'/ctest/pharmacist','Medicines'=>'/ctest/pharmacist','Advertisement'=>'#','Report'=>'#','My Detail'=>'#'],$select);?>
=======
         <?php $sidebar=new Sidebar(['Orders'=>'/ctest/pharmacy-orders-pending','Medicines'=>'/ctest/pharmacy-view-medicine','Advertisement'=>'/ctest/pharmacy-view-advertisement','Report'=>'/ctest/pharmacy-view-report','My Detail'=>'/ctest/pharmacy-view-personal-details'],$select);?>
>>>>>>> 20000804
         <?php echo $sidebar;  ?>
        
        
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
>>>>>>> 20000804
            {{content}}
        </div>
    </div>
 
</body>
</html>