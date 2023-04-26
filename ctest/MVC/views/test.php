<?php

use app\core\component\PopUp;
use app\core\component\ScatterChart; 
    $scatter=new ScatterChart(['monday'=>['one','two'],'tuesday'=>['three']],24,['monday'=>[1,2],'tuesday'=>[3]]);
    echo $scatter;
    $popup=new PopUp("Is it true \?","popup-value","pop--class-0","/ctest/");


    

?>