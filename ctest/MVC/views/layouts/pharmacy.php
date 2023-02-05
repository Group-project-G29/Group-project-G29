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

=======
>>>>>>> 20001843
            {{content}}
        </div>
    </div>
 
</body>
</html>