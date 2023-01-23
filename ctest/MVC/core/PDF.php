<?php

    namespace app\core;
    //view pdf file when location is given
    class PDF{
        public function viewPDF($location){
            header('Content-type:application/pdf');
            header('Content-Description:inline;filename="'.$location.'"');
        }
    }

    //download pdf file
    


    //create pdf file



?>