<?php

namespace app\core;

use PDOException;
use PDORow;

require_once "../views/env.php";
 class Database{
    public  \PDO $pdo;
   
    public function __construct(){
        $dsn="mysql:host=127.0.0.1;port=3306;dbname=new";
        $user="root";
        $password="";
        try{
            $this->pdo=new \PDO($dsn,$user,$password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
           
        }
        catch(PDOException){
            echo "failed to connect to database";
        }
    }  
    // public function applyMigrations(){
    //     $this->createMigrationsTable();
    //     $this->getAppliedMigrations();
    //     $files=scandir(Application::$ROOT_DIR.'/migrations');

    // }
    // public function createMigrationsTable(){
    //     $this->pdo->exec("CREATE TABLE IF NOT EXISTS  migrations(
    //         id INT AUTO_INCREMENT PRIMARY KEY,
    //         migration VARCHAR(255),
    //         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)
    //         ENGINE=INNODB;");
    // }
    // public function getAppliedMigrations(){
    //     $statement=$this->pdo->prepare("SELECT migration FROM migrations");
    //     $statement->execute();

    //     return $statement->fetchALL(\PDO::FETCH_COLUMN);
    // }
    public function prepare($sql){
        return $this->pdo->prepare($sql);
    }

}


    

 
 
?>