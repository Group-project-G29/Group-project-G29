
<?php
    use app\core\Application;
    include "../views/layouts/navbar/doctor-navbar.php";

?>
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

 
</body>
</html>

