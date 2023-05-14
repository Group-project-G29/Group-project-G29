<?php

use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/pharmacy-navbar.php";
?>

<div class="main-container">

    <div class="layout-div-row">

        <div class="layout-div-col" id="sidebar-col"> 
            <?php $sidebar=new Sidebar(['Front Desk Orders'=>'/ctest/pharmacy-front-orders-pending','Orders'=>'/ctest/pharmacy-orders-pending', 'Previous Orders'=>'/ctest/pharmacy-orders-previous', 'Medicines'=>'/ctest/pharmacy-view-medicine','Advertisement'=>'/ctest/pharmacy-view-advertisement','Report'=>'/ctest/pharmacy-view-report','My Detail'=>'/ctest/pharmacy-view-personal-details'],$select);?>
            <?php echo $sidebar;  ?>
        </div>

        <!-- <div class="layout-div-col" > -->
        <div class="sub-container layout-div-col" id="container-col">
            <div>
                <?php if(Application::$app->session->getFlash('success')):?>
                <div class="flash-message">
                    <?php echo Application::$app->session->getFlash('success');?>
                </div>
                <?php endif;?>
            </div>
        
            {{content}}
        </div>
        <!-- </div> -->

    </div>

</div>

</body>
</html>