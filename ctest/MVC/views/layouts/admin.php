<?php

use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/admin-navbar.php";
?>

<body>
    
    <div class="main-container">
         <?php $sidebar=new Sidebar(['Reports'=>'/ctest/admin-reports','Channelings Sessions'=>'/ctest/schedule-channeling','Schedule Channelings'=>'/ctest/schedule-channeling?mod=add','Manage Users'=>'/ctest/admin','Advertisement'=>'/ctest/main-adds'],$select);?>
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