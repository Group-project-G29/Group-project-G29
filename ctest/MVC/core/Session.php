<?php

    namespace app\core;
    class Session{
        protected const FLASH_KEY='flash_messsage';
        // flash messages should be removed once it is used 
        public function __construct(){
            session_start();
            $flashMessages=$_SESSION[self::FLASH_KEY] ?? [];
            foreach($flashMessages as $key =>&$flashMessage){
                $flashMessage['remove']=true;
            }
            $_SESSION[self::FLASH_KEY]=$flashMessages;

        }

        public function setFlash($key,$message){
            $_SESSION[self::FLASH_KEY][$key]=[
                'remove'=>false,
                'value'=>$message
            ];
            
        }
        public function getFlash($key){
           $retrun=$_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
          
            return $retrun;
        }
        public function __destruct(){
            $flashMessages=$_SESSION[self::FLASH_KEY] ?? [];
            foreach($flashMessages as $key =>&$flashMessage){
               if($flashMessage['remove']){
                   unset($flashMessages[$key]);
               }
            }
            $_SESSION[self::FLASH_KEY]=$flashMessages;
        }
        public function set($key,$value){
            $_SESSION[$key]=$value;
        }
        public function get($key){
            return $_SESSION[$key] ?? false;
        }
        public function remove($key){
            unset($_SESSION[$key]);
        }

    }



?>