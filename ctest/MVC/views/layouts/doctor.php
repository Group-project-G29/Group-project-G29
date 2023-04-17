<?php

use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/doctor-navbar.php";

?>

    <div class="main-container">
         <?php $sidebar=new Sidebar(['Today Channelings'=>'doctor','All Channelings'=>'doctor?spec=all','Report'=>'summary-reports','My Detail'=>'doctor-my-detail?cmd=view'],$select);?>
         <?php echo $sidebar;  ?>
        
        
        <div class="sub-container">
            <div>
                <?php if(Application::$app->session->getFlash('success')):?>
                 <div class="flash-message">
                     <?php echo Application::$app->session->getFlash('success');?>
                 </div>
                <?php endif;?>
            </div>

            <div>
                {{content}}
            </div>

        </div>
    </div>
 
</body>
</html>