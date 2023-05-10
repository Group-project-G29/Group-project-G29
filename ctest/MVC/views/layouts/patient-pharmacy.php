<?php

use app\core\Application;
use app\core\component\Component;
use app\core\component\Sidebar;
use app\models\Employee;
$component=new Component();
include "../views/layouts/navbar/patient-pharmacy-navbar.php";
?>

<div class="main-container--iso">
            <div>
                <?php if(Application::$app->session->getFlash('success')):?>
                 <div class="flash-message">
                     <?php echo Application::$app->session->getFlash('success');?>
                 </div>

                <?php endif;?>
                  <?php if(Application::$app->session->getFlash('error')):?>
                    <div class="flash-message--error disap">
                        <?php echo Application::$app->session->getFlash('error');?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="bg">
    
            </div>

            {{content}}
</div>
     
