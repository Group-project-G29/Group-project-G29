<?php

use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/admin-navbar.php";
?>

<body>
    
    <div class="main-container">
<<<<<<< HEAD
         <?php $sidebar=new Sidebar(['Reports'=>'#','Channelings Sessions'=>'/ctest/schedule-channeling','Schedule Channelings'=>'/ctest/schedule-channeling?mod=add','Manage Users'=>'/ctest/admin','Manage Rooms'=>'#','Notification'=>'#','Advertisement'=>'#'],$select);?>
=======
         <?php $sidebar=new Sidebar(['Reports'=>'#','Channelings Sessions'=>'/ctest/schedule-channeling','Schedule Channelings'=>'/ctest/schedule-channeling?mod=add','Manage Users'=>'/ctest/admin','Manage Rooms'=>'#','Notification'=>'#','Advertisement'=>'/ctest/main-adds'],$select);?>
>>>>>>> 20000758
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