<?php

use app\core\component\PopUp;
use app\core\component\ScatterChart; 
    
?>


<section class="channeling--main">
  
    <div class="channeling--main_title">
        <h2 class="fs-200 fc-color--dark">Select Channeling Speciality</h2>
       
    </div>
    <div class="channeling--main_tiles">
         
            <?php
                
                foreach($specialities as $speciality){
                    echo " <div class=\"channeling--main_tiles_tile\" id=\"{$speciality['speciality']}\">
                            <img src=\"./media/images/patient/{$speciality['speciality']}.png\">
                            
                        <p>{$speciality['speciality']}</p>
                            </div>";
                }
            ?>
         
    </div>
    
</section>
<script>
    
    document.querySelectorAll(".channeling--main_tiles_tile").forEach(function(el){
            url="";
            el.addEventListener("click" , function(){
                location.href="/ctest/patient-channeling-category-view?spec="+el.id;
                bg.classList.add("background");

            }) 
        })
     
    
    </script>
