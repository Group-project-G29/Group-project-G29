<?php

use app\core\Application;
use app\core\component\Sidebar;
use app\models\Employee;
include "../views/layouts/navbar/pharmacy-navbar.php";
?>

<div class="main-container">

    <div class="layout-div-row">

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