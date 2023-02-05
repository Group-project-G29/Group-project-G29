<?php

use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/pharmacy-navbar.php";
?>

</nav>
    <div class="main-container">
         <?php $sidebar=new Sidebar(['Orders'=>'/ctest/pharmacist','Medicines'=>'/ctest/pharmacist','Advertisement'=>'#','Report'=>'#','My Detail'=>'/ctest/pharmacy-view-personal-details'],$select);?>
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
<<<<<<< HEAD

=======
>>>>>>> c9612a7f9b4ccb2af04ac81f9858298b29d36a1e
=======
>>>>>>> 20000804
            {{content}}
        </div>
    </div>
 
</body>
</html>