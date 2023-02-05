<?php
use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/pharmacy-navbar.php";
?>

</nav>
    <div class="main-container">
<<<<<<< HEAD
<<<<<<< HEAD
         <?php $sidebar=new Sidebar(['Requests'=>'/ctest/','Tests'=>'/ctest/','Advertise'=>'/','My Detail'=>'/','My Detail'=>'/'],$select);?>
=======
         <?php $sidebar=new Sidebar(['Requests'=>'/ctest/lab-test-request','Tests'=>'/ctest/lab-view-all-test','Advertise'=>'/','My Detail'=>'/ctest/lab-view-personal-details'],$select);?>
>>>>>>> c9612a7f9b4ccb2af04ac81f9858298b29d36a1e
=======
         <?php $sidebar=new Sidebar(['Requests'=>'/ctest/','Tests'=>'/ctest/','Advertise'=>'/','My Detail'=>'/','My Detail'=>'/'],$select);?>
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