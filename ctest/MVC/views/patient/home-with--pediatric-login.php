
<style>
    .fade{-webkit-animation-name:fade;-webkit-animation-duration:1.5s;animation-name:fade;animation-duration:1.5s}
    @-webkit-keyframes fade{from{opacity:.4}to{opacity:1}}
    @keyframes fade{from{opacity:.4}to{opacity:1}}
    @media only screen and (max-width:300px){.prev,.next,.text{font-size:11px}}
</style>    
<?php
    use \app\core\form\Form;
    $form=Form::begin('','post');
    use app\core\Application;
    use app\models\Advertisement;

    $ad=new Advertisement();
?>
<?php
  
 if((Application::$app->session->get('user'))){
    unset($_SESSION['user']);
    Application::$app->response->redirect("/ctest/");
} ?>

<section>
    <div class="patient-login-container">
    <div>
                <?php if(Application::$app->session->getFlash('success')):?>
                 <div class="flash-message-login">
                     <?php echo Application::$app->session->getFlash('success');?>
                 </div>
                <?php endif;?>
            </div>
        <section class="patient-login">
            <div class="patient-login">
                <h1 class="fs-200 fc-color--dark">Patient Login<h1>
            </div>
            <div class="patient-login-fields">
            <?php echo $form->field($model,'username','Guardian NIC','field','text') ?>
            <?php echo $form->field($model,'name','Patient Name','field','text') ?>
            <?php echo $form->field($model,'password','Password','field','password') ?>
            <div class="patient-login-lower-text">Adult Patient? click<a href="/ctest/"> here</a></div>
            <div class="patient-login-lower-text">Forgot Password? click<a href="/ctest/register"> here</a></div>
            <div><input class="button-lighter" type="submit" value="Log In"></div>
            </div>
            <?php Form::end() ?>

        </section>
    </div>
    <div class="slideshow-container">
         <?php $advertisements=$ad->fetchAssocAll(['type'=>'main']); ?>
        <?php foreach($advertisements as $advertisement): ?>
        <div class="mySlides fade">
            <img src=<?="'"."media/images/advertisements/".$advertisement['img']."'"?> style="width:100%">
          
        </div>
        <?php endforeach;?>
    </div>
</section>
<section class="homepage-main-container">
    <section class="homepage-main-container-sub">
        <div class="main-container-tile" id="doctor-patient-appointment?spec=doctor">
            <img src="./media/images/patient/Meet Doctor.png" id="">
            <h3 class="fs-100">Meet Doctor</h3>
        </div>
        <div class="main-container-tile" id="patient-channeling-category-view">
            <img src="./media/images/patient/Channeling.png">
            <h3>Channelings</h3>
        </div>
        <div class="main-container-tile" id="patient-pharmacy?spec=main">
            <img src="./media/images/patient/Pharmacy.png">
            <h3>Pharmacy</h3>
        </div>
        <div class="main-container-tile" id="patient-lab-main">
            <img src="./media/images/patient/Laboratory.png">
            <h3>Laboratory</h3>
        </div>
        <div class="main-container-tile" id="contact-us">
            <img src="./media/images/patient/operator.png">
            <h3>Contact Us</h3>

        </div>
    </section>
    <div style="font-size:32; letter-spacing:0.6vw; margin-top:2vh; ">
        <h1 style="font-weight:900;"><span style="color:#38B6FF;">Healing Hand.</span><span style="color:#1746A2">Caring Heart!</span></h1>
    </div>
    <section class="l-img">
        <div class="main-img">
            <div class="s-img">
                <img src="./media/images/patient/medicalrecord.jpg">
            </div>
            <div>
                <h3>E-Medical Reports</h3>
            </div>
            <div>
                Manage all your medical documents in one place. We provide E-prescriptions, Medical Reports, Lab Reports.
            </div>
        </div>
        <div class="main-img">
            <div class="s-img">
                <img src="./media/images/patient/deliveryman.jpg">

            </div>
            <div>
                <h3>Medicine Delivery</h3>
            </div>
            <div>
                Medicine delivery to your door step. Also you can make pickup orders too.
            </div>
        </div>
        <div class="main-img">
            <div class="s-img">
                <img src="./media/images/patient/Hospital.jpg">
            </div>
            <div>
                <h3>Best Services</h3>
            </div>
            <div>
                Best array of services from medication to live channeling consultation.
            </div>
        
    </section>
        
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

   
    <!-- <div class="main-page-para--1" >
        <p>
            Best care from a leading medical institute in Sri Lanka.
            We are a proud family providing best medical care for 30 years.
            Visit us in any medical need. We are here to help you.
        </p>
    </div> -->
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


<script>
    let elementsArray = document.querySelectorAll(".main-container-tile");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='/ctest/'+elem.id;
        });
    });

    const patientLogin=document.querySelector(".patient-login-container");
    const mainContainer=document.querySelector(".homepage-main-container");
    patientLogin.style.transitionDuration="2s";
    mainContainer.addEventListener('mouseover',()=>{
        patientLogin.style.opacity=0;
    })

    mainContainer.addEventListener('mouseout',()=>{
        patientLogin.style.opacity=1;
    })

    window.addEventListener('scroll',function(){
        patientLogin.style.opacity=0;
    })
  
</script>