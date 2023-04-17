<?php
    namespace app\core;
    use Dompdf\Dompdf;
    use Dompdf\Options;
    //view pdf file when location is given

    class PDF{
        public Dompdf $dompdf;
        public function __construct(){
            $options=new Options;
            $options->setChroot(__DIR__);
            $options->setIsRemoteEnabled(true);
            $this->dompdf=new Dompdf($options);
        }
        public function viewPDF($location){
            Application::$app->response->redirect($location);
            // header('Content-type:application/pdf');
            // header('Content-Description:inline;filename="'.$location.'"');
        }
        
        public function createPDF($html,$name){

            $this->dompdf->loadHtml($html);
            $this->dompdf->render();
            $this->dompdf->stream($name,["Attachment"=>0]);

    
        }


    }

    //download pdf file
    


    //create pdf file



?>