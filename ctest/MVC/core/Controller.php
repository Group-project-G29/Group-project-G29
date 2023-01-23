<?php 
namespace app\core;


class Controller{
    //Defualt layout
    public string $layout='main';
    public array $layoutParams=[];

    //Render both layout and view 
    public function render($view,$params=[]){
        return Application::$app->router->renderView($view,$params);

    }

    //Layout setter
    public function setLayout($layout,$params=[]){
        $this->layout=$layout;
        $this->layoutParams=$params;

    }
}