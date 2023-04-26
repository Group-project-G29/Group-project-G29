<script src="./media/js/main.js">

</script>
<?php

use app\core\Application;
use app\core\component\CartView;
use app\core\component\Component;
use app\core\form\Form;

    $component=new Component();
    $form=new Form();




?>

<style>
    
    .fade{-webkit-animation-name:fade;-webkit-animation-duration:1.5s;animation-name:fade;animation-duration:1.5s}
    @-webkit-keyframes fade{from{opacity:.4}to{opacity:1}}
    @keyframes fade{from{opacity:.4}to{opacity:1}}
    @media only screen and (max-width:300px){.prev,.next,.text{font-size:11px}}
    

</style>
<div class="bg">

</div>
<div class="slideshow-container">
    <?php foreach($advertisements as $advertisement): ?>
        <div class="mySlides fade">
            <?="./media/images/advertisements/pharmacy/".$advertisement['img'] ?>
            <img src=<?="./media/images/advertisements/pharmacy/".$advertisement['img']?> style="width:100%">
        </div>
        <?php endforeach ; ?>
        
    </div>
    
    <div class="prescription-popup hide" id="popup-main">
        <div class="prescription-popup-wrapper">
            <h3 class="fs-50">Add prescriptions here. Prescrition should be a valid one or else it would be rejected.</h3>
            <h3>Make sure to capture any authentication detail on the Prescription when uploading prescription.</h3>
            <?php $form=Form::begin("patient-pharmacy?spec=prescription&cmd=add",'post');?>
            <input type='file' name='prescription[]' multiple/> 
            <?=$component->button("Done","submit","Done","button--class-0",);?>
            <?php $form=Form::end();?>
        </div>        
    </div>
    <section class="pharmacy-main-container">
    <div class="ph-main-stripe">
        <img src="./media/images/patient/striper.png">
    </div>  
   
   
     <div class="ph-main-services">
        <div class="pharmacy-main-page--upload-prescription">
            <div class="service-content--1">
                <h3>Upload Your precriptoin here</h3>
                <h4>We provide medicine to any valid prescription</h4>
                <?= $component->button('upload-precription','',"Upload Prescription","button-class--yellow",'upload-prescription'); ?>
            </div>
        </div>
        <div class="pharmacy-main-page--track-orders"> 
            <div class="service-content--2">
                <h3>Track you orders here</h3>
                <h4>Track yout order at comfort of your own home.</h4>
                <?= $component->button('track-order','',"Track Your Order",'button-class--yellow','track-order'); ?>
            </div>
        </div>
     </div>
       
</section>

<div>
    <a class="prev" onclick="plusSlides(-1)"></a>
    <a class="next" onclick="plusSlides(1)"></a>

</div>
<div style="text-align:center">
  <span class="dot" onclick="currentSlide(1)"></span> 
  <span class="dot" onclick="currentSlide(2)"></span> 
  <span class="dot" onclick="currentSlide(3)"></span> 
</div>

   

</div>
<script>
var slideIndex = 1;showSlides(slideIndex);
function plusSlides(n){showSlides(slideIndex += n);}
function currentSlide(n){showSlides(slideIndex = n);}
function showSlides(n){
                        var i;
                        var slides = document.getElementsByClassName("mySlides");
                        var dots = document.getElementsByClassName("dot");
                        if (n > slides.length){slideIndex = 1;}
                        if (n < 1){slideIndex = slides.length;}
                        for (i = 0;i < slides.length;i++){
                          slides[i].style.display = "none";
                        }
                        for (i = 0;i < dots.length;i++){
                           dots[i].className = dots[i].className.replace(" active","");}
                           slides[slideIndex-1].style.display = "block";
                           dots[slideIndex-1].className += " active";}
</script>
<script>
var slideIndex = 0;
showSlides();
function showSlides(){
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  for (i = 0;i < slides.length;i++){slides[i].style.display = "none";}
  slideIndex++;
  if (slideIndex > slides.length){slideIndex = 1;}
  for (i = 0;i < dots.length;i++){
    dots[i].className = dots[i].className.replace(" active","");}
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";setTimeout(showSlides,10000)};
  
</script>
<script src="./media/js/main.js">

</script>

<script>
    const bg=document.querySelector(".bg");
    const trackOrderBtn=e('track-order');
    trackOrderBtn.addEventListener('click',()=>{
        location.href="patient-pharmacy?spec=order-main";
    })
    const uploadPres=e('upload-prescription');
    const popUp=e('.prescription-popup','class');
    uploadPres.addEventListener('click',()=>{
        bg.classList.add("background");
        popUp.classList.remove('hide');
    })
    
</script>


