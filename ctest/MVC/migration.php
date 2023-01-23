<?php
    namespace app;
    use app\core\Application;
    
    $app=new Application(__DIR__);
    $app->db->applyMigrations();

?>