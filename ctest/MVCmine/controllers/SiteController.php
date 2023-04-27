<?php
    namespace app\controllers;
    
    use app\core\Application;
    use app\core\Controller;
    use app\core\Request;
    class SiteController extends Controller{
        public function home(){
            $params=[
                'name'=>"Yasiru"
            ];
            return $this->render('home',$params);
        }
        public function handleContact(Request $request){
            $body=$request->getBody();
            
            
        }
        public function contact(){
            return $this->render('contact'); 
        }
        public function doctor(){
        
            $this->setLayout('doctor');
            return $this->render('doctor/doctor');
        }
        public function nurse(){
            $this->setLayout('doctor');
            return $this->render('nurse/nurse');
        }
    } 
