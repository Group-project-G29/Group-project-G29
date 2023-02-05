<?php
namespace app\core;
use app\models\Patient;
use app\models\Employee;
// core application where session and main function are handled
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
    

    public function __construct($rootPath){
        $this->userClass=new Employee(); //default user is employee
        self::$ROOT_DIR=$rootPath;
        self::$app=$this;
        $this->request=new Request(); 
        $this->response=new Response();
        $this->router=new Router($this->request,$this->response);
        $this->db=new Database();
        $this->session=new Session();
        $primaryValue=$this->session->get('user'); //user unique identification employee=>email and patient=>patient_ID
        if ($primaryValue){
            $role=$this->session->get('role'); //if account is a patient defult userClass is changed to patient class
            if($role=='patient'){
                $this->userClass=new Patient();
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
        $this->user=$user;//change state of the application by changing user
        $primaryKey=$user->primaryKey();
        $primaryValue=$user->{$primaryKey}; //$user->email
        $this->session->set('user',$primaryValue);  //session user and role is set, role to identify employee or patient
        $this->session->set('role',$role);
        $this->session->set('userObject',$user); //userObject is set
        return true;
    }
    public function logout(){
        //unset all information relation to user
        $this->user=null;
        $this->session->remove('userObject');
        $this->session->remove('role');
        $this->session->remove('user');
    }
}