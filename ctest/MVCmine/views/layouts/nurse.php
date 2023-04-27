<?php
use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/nurse-navbar.php";
?>

</nav>
    <div class="main-container">
         <?php $sidebar=new Sidebar(['Today Channelings'=>'/ctest/today-channelings','Channeling Allocations'=>'/ctest/nurse-channeling-allocation','All Channelings'=>'/ctest/all-channelings','My Detail'=>'/ctest/my-detail'],$select);?>
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