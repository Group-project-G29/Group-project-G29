<?php

use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/delivery-rider-navbar.php";
?>

</nav>
    <div class="main-container">
         <?php $sidebar=new Sidebar(['My Deliveries'=>'/ctest/delivery-my-deliveries','Pending Deliveries'=>'/ctest/delivery-pending-deliveries','Completed Deliveries'=>'/ctest/delivery-all-deliveries','My Detail'=>'/ctest/delivery-view-personal-details'],$select);?>
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