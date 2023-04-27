<?php //var_dump($channelings); ?>

<section class="channeling--main">
  
    <div class="channeling--main_title">
        <h2 class="fs-200 fc-color--dark">Select Channeling Day</h2>
    </div>
    <!-- <div class="channeling--main_tiles"> -->
        
         
    <!-- </div> -->
    
    
</section>

<div class="channeling--main_tiles-div">
    <div class="channeling--main_tiles-day" id="Monday">               
        <p>Monday</p>
    </div>
    <div class="channeling--main_tiles-day" id="Tuesday">               
        <p>Tuesday</p>
    </div>
    <div class="channeling--main_tiles-day" id="Wednesday">               
        <p>Wednesday</p>
    </div>
    <div class="channeling--main_tiles-day" id="Thursday">               
        <p>Thursday</p>
    </div>
    <div class="channeling--main_tiles-day" id="Friday">               
        <p>Friday</p>
    </div>
    <div class="channeling--main_tiles-day" id="Saturday">               
        <p>Saturday</p>
    </div>
    <div class="channeling--main_tiles-day" id="Sunday">               
        <p>Sunday</p>
    </div>
</div>

<script>
    
    document.querySelectorAll(".channeling--main_tiles-day").forEach(function(el){
        url="";
        el.addEventListener("click" , function(){
            location.href="all-channelings?spec=<?=$speciality?>&day="+el.id;

        }) 
    })
    

</script>