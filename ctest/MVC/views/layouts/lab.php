<?php
use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/pharmacy-navbar.php";
?>

</nav>
    <div class="main-container">
         <?php $sidebar=new Sidebar(['Requests'=>'/ctest/lab-test-request','Tests'=>'/ctest/lab-view-all-test','Advertisement'=>'/ctest/lab-view-advertisement','My Detail'=>'/ctest/lab-view-personal-details'],$select);?>
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