<?php

use app\core\Application;
use app\core\component\Component;
use app\core\component\Sidebar;
use app\models\Employee;
$component=new Component();
include "../views/layouts/navbar/patient-navbar-plus.php";
?>

<div class="main-container--iso">
            <div>
                <?php if(Application::$app->session->getFlash('success')):?>
                 <div class="flash-message">
                     <?php echo Application::$app->session->getFlash('success');?>
                 </div>
                <?php endif;?>
            </div>
            <div class="bg">
    
            </div>

            {{content}}
</div>
     
