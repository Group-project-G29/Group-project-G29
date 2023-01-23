<?php
    namespace app\core;
    

    abstract class FileModel extends Model{
        abstract public function fileDestination():Array;

        public function fileStore(){
            
            $fileDestination=$this->fileDestination();
            foreach($fileDestination as $filename=>$destination){
                $fileTempName=$_FILES[$filename]['tmp_name'];
                move_uploaded_file($fileTempName,$destination);
            }
        }

        
    }


?>