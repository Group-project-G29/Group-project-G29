<?php

namespace app\core;



/**
 *  @package app\core
 */

class Router{
   
    protected array $routes=[];
    public Request $request;
    public Response $response;

    public function __construct(Request $request,Response $response){
        $this->request=$request;
        $this->response=$response;
    }
    //Make array of controllers     array(['get']=>array([controller_name_in_url]=>$function)
    public function get($path,$callback){
        $this->routes['get'][$path]=$callback;
        
       
    }  
    //Make array of controllers for post request
    public function post($path,$callback){
        $this->routes['post'][$path]=$callback;
        
      
    }
    //Execute function in controller array  by processsing URL  
    public function resolve(){
        $path=$this->request->getPath(); // Get the URL
        $method=$this->request->method(); // Get the Method
        // var_dump($path);
        // var_dump($method);
        $callback=$this->routes[$method][$path] ?? false;
        // var_dump($callback);
        if($callback==false){
            $this->response->setStatusCode(404);
            return $this->renderContent("Not found");
            
        }
        if(is_string($callback)){
            return $this->renderView($callback);
        }
        if(is_array($callback)){
            // var_dump($callback);
            // exit;
            Application::$app->controller=new $callback[0]();
            $callback[0]=Application::$app->controller;
        }
        return call_user_func($callback,$this->request,$this->response);
    }

    public function renderView($view,$params=[]){
        $layoutContent=$this->layoutContent();
        $viewContent=$this->renderOnlyView($view,$params);
        return str_replace('{{content}}',$viewContent,$layoutContent);
       
    }
    public function renderContent($viewContent){
        $layoutContent=$this->layoutContent();
        return str_replace('{{content}}',$viewContent,$layoutContent);
       
    }

    protected function layoutContent(){
        $layout=Application::$app->controller->layout;
        $layoutParams=Application::$app->controller->layoutParams;
        foreach($layoutParams as $key=>$value){
            $$key=$value;
            
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }
    
    protected function renderOnlyView($view,$params){
        foreach($params as $key=>$value){
            $$key=$value;
            
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}

