<?php

use app\core\component\PopUp;
use app\core\component\ScatterChart; 
// var_dump($tests);
// exit;
?>


<section class="laboratory-body">
    <div class="bg-image-div" id="lab-bg-image-div">
        <p id="contact-topic">LABORATORY</p>
    </div>

    <div class="channeling--main_tiles">
         
            <?php
                
                foreach($tests as $test){
                    echo " <div class=\"channeling--main_tiles_tile\" id=\"{$test['name']}\">
                        <p>{$test['name']}</p>
                        <p>{$test['fee']}</p>
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
     


        const tests=document.querySelectorAll('.channeling--main_tiles_tile');
        const searchBar=document.getElementById('searchbar');
        function checker(){
            var re=new RegExp("^"+searchBar.value)
            tests.forEach((el)=>{
                comp=""+el.id;
                console.log(el.id);
                comp=comp.split("-");
            ;
            if(searchBar.value.length==0){
                // el.classList.add("none")
            }
            else if(re.test(comp[0]) || re.test(comp[1]) || re.test(comp[2])){
                el.classList.remove("none");
            }
            else{
                el.classList.add("none");
            }
            })
            if(searchBar.value.length==0){
                tests.forEach((el)=>{
                    el.classList.remove("none");
                }) 
            }
        }
        searchBar.addEventListener('input',checker);
    
</script>
