<?php
namespace app\core;

use app\models\Employee;

/**
 * @package app\core
 */

class Application{
    public \app\core\DbModel $userClass;
    public Router $router;
    public  Request $request; 
    public Response $response;
    public Session $session;
    public Database $db;
    public ?DbModel $user;
    public static Application $app;
    public Controller  $controller;
    public static string $ROOT_DIR;
    

    public function __construct($rootPath)
    {
        $this->userClass=new (\app\models\Employee::class)();
        self::$ROOT_DIR=$rootPath;
        self::$app=$this;
        $this->request=new Request(); 
        $this->response=new Response();
        $this->router=new Router($this->request,$this->response);
        $this->db=new Database();
        $this->session=new Session();
        $primaryValue=$this->session->get('user');
        if ($primaryValue){
            $role=$this->session->get('role');
            if($role=='patient'){
                $this->userClass=new (\app\models\Patient::class)();
            }
            $primaryKey=$this->userClass->primaryKey();
            $this->user=$this->userClass->findOne([$primaryKey=>$primaryValue]);
        }
    }

    public function run(){
        echo $this->router->resolve();   
    }
    public function getController(){
        return $this->controller;
    }
    public function setController(Controller $controller){
        $this->controller=$controller;

    }
    public function login(DbModel $user,$role){
        $this->user=$user;
        $primaryKey=$user->primaryKey();
        $primaryValue=$user->{$primaryKey};
        $this->session->set('user',$primaryValue);
        $this->session->set('role',$role);
        $this->session->set('userObject',$user);
        return true;
    }
    public function logout(){
        $this->user=null;
        $this->session->remove('userObject');
        $this->session->remove('user');
    }
}