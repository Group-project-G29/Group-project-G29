<?php
    namespace app\core;
    //view pdf file when location is given
    class ImageFile{
        public function viewImage($location){
            Application::$app->response->redirect($location);
            // $location_array=explode("/",$location);
            // $name=$location_array[count($location_array)-1];
            // $type=explode(".",$name);
            // $type=$type[count($type)-1];
            // header('Content-type:image/'.$type);
            // header('Content-Description:inline;filename="'.$location.'"');
        }
    }

    //download pdf file
    


    //create pdf file



?>