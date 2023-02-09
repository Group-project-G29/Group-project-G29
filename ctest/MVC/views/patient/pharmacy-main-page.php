<script src="./media/js/main.js">

</script>
<?php

use app\core\Application;
use app\core\component\CartView;
use app\core\component\Component;
    $component=new Component();


?>

<style>
    
    .fade{-webkit-animation-name:fade;-webkit-animation-duration:1.5s;animation-name:fade;animation-duration:1.5s}
    @-webkit-keyframes fade{from{opacity:.4}to{opacity:1}}
    @keyframes fade{from{opacity:.4}to{opacity:1}}
    @media only screen and (max-width:300px){.prev,.next,.text{font-size:11px}}
    

</style>
<div class="slideshow-container">
    <?php foreach($advertisements as $advertisement): ?>
        <div class="mySlides fade">
            <?="./media/images/advertisements/pharmacy/".$advertisement['img'] ?>
            <img src=<?="./media/images/advertisements/pharmacy/".$advertisement['img']?> style="width:100%">
        </div>
        <?php endforeach ; ?>
        
    </div>
    
    <section class="pharmacy-main-container">
<<<<<<< HEAD
        
        <div class="search-medicine-container">
            <?php echo $component->searchbar('',"name","search-bar--class1","Search by medicine name","search");?>
            <?php if(Application::$app->session->get('user')):?>
                <div class="main-cart-container">
                    <?= $component->cartview() ?>
                </div>
            <?php endif; ?>
            <script>
                const searchbar=e('search');
                const btn=e('bsearch');
                btn.addEventListener("click",()=>{
                    location.href="patient-pharmacy?cmd=search&value="+searchbar.value+"&page=1";
                });
            </script>
        </div>
=======
    <div class="ph-main-stripe">
        <img src="./media/images/patient/striper.png">
    </div>  
   
>>>>>>> 20002051
   

     <div class="ph-main-services">
        <div class="pharmacy-main-page--upload-prescription">
            <div class="service-content--1">
                <h3>Upload Your precriptoin here</h3>
<<<<<<< HEAD
=======
                <h4>We provide medicine to any valid prescription</h4>
>>>>>>> 20002051
                <?= $component->button('upload-precription','',"Upload Prescription","button-class--yellow"); ?>
            </div>
        </div>
        <div class="pharmacy-main-page--track-orders"> 
            <div class="service-content--2">
                <h3>Track you orders here</h3>
<<<<<<< HEAD
=======
                <h4>Track yout order at comfort of your own home.</h4>
>>>>>>> 20002051
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
    const trackOrderBtn=e('track-order');
    trackOrderBtn.addEventListener('click',()=>{
        location.href="patient-pharmacy?spec=order-main";
    })
</script>


<script>
    const trackOrderBtn=e('track-order');
    trackOrderBtn.addEventListener('click',()=>{
        location.href="patient-pharmacy?spec=order-main";
    })
</script>


