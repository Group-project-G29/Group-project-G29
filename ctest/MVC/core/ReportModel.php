<?php
    namespace app\core;
    use app\core\ImageFile;
    use app\core\PDF;
    class ReportModel{
      

        public function openFile($location){
            $location_array=explode("/",$location);
            $name=$location_array[count($location_array)-1];
            $type=explode(".",$name);
            $type=$type[count($type)-1]; 
            if($type=="pdf"){
                $pdfModel = new PDF();
                $pdfModel->viewPDF($location);
            }
            else{
                $imageModel=new imageFile();
                $imageModel->viewImage($location);    
            }   
        }

        
    }


?>