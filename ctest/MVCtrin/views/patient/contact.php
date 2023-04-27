<?php

use app\core\component\PopUp;
use app\core\component\ScatterChart; 
?>


<section class="contact-us-body">
    <div class="bg-image-div">
        <p id="contact-topic">CONTACT US</p>
    </div>

    <div class="contact-row">
        <div class="contact-col-description">
            <p>The channeling center is a medical institute that provides medical consultation and medication to patients. Private channeling centers are profit-driven institutes.</p>
        </div>

        <div class="contact-col-description">
            <p>Patients can meet doctors face to face to get medical consultations. Patients need to place an appointment to get a number on the clinic patient list.</p>
        </div>
    </div>

    <div class="contact-row">
        <div id="contact-map">
            <img id="contact-map-photo" src="https://th.bing.com/th/id/R.8ce82187dff2dc56d65255a1388ac3c6?rik=RkJPl6z5a0fJPQ&pid=ImgRaw&r=0">
        </div>

        <div class="contact-details">
                <img id="contact-phone" src="https://th.bing.com/th/id/R.ef0060cdd18902117c6cf1547c752e35?rik=%2f39qdD0UTRKF0Q&riu=http%3a%2f%2fpluspng.com%2fimg-png%2fphone-png-phone-png-file-1969.png&ehk=nu7XnV%2bKqiLV3YpguKMwlHuqPmmrXN1azO52CXCu868%3d&risl=&pid=ImgRaw&r=0">
                <p>Telephone Numbers</p>
                <p>0112765876</p>
                <p>0112765876</p>
        </div>

        <div class="contact-details">
                <img id="contact-email" src="https://th.bing.com/th/id/R.9547261541e93756194b51bc704ead65?rik=UqdU%2fFetHG2dXQ&riu=http%3a%2f%2fcdn.onlinewebfonts.com%2fsvg%2fimg_500737.png&ehk=3Ri7ovVdevl2qL%2be4Oz9P%2fpAsz5jq77px%2fo9dSaHRqg%3d&risl=&pid=ImgRaw&r=0">
                <p>E-mail</p>
                <p>anspaughcare@gmail.com</p>
                <p>ans.care@gmail.com</p>
        </div>

        <div class="contact-details">
                <img id="contact-location" src="https://th.bing.com/th/id/R.6c54525cc78d1514e93c17a2fe27d315?rik=I7Qj6l5sIg1xug&riu=http%3a%2f%2fgetdrawings.com%2fvectors%2faddress-icon-vector-3.png&ehk=cFsyj5ZV4g%2bLoUB2JcwdZQ3cleqhaJ9BgsI9ItXV9%2bQ%3d&risl=&pid=ImgRaw&r=0">
                <p>Address</p>
                <p>NO.14, Reid Avenue,</p>
                <p>Colombo 07.</p>
        </div>

   </div>

   <div class="contact-row">
        <div class="contact-col-description">
            <div>
                <p>Stay Connected with us..</p><br>
            </div>
            <div class="contact-row">
                <div class="contact-icon">
                    <img class="contact-icon-img" src="https://th.bing.com/th/id/R.3780a91a5e969ab4763cc952f198cb62?rik=nqAFpszEIvX5og&riu=http%3a%2f%2fcdn.onlinewebfonts.com%2fsvg%2fimg_432951.png&ehk=EPWOprAvZd4RPNHAK%2fSas0azfbxsc137X2DzQWHaAFI%3d&risl=&pid=ImgRaw&r=0">
                </div>
                <div class="contact-icon">
                    <img class="contact-icon-img" src="https://th.bing.com/th/id/R.af1159ea3dd22f4b3d84a87d869127b9?rik=iz68IEBoErXAHw&riu=http%3a%2f%2fpluspng.com%2fimg-png%2finstagram-icon-png-instagram-icon-1600.png&ehk=%2bAo2wF7lgRZJrtXPe3ev37c8JDCD6NBcj%2fGufhuhjQE%3d&risl=&pid=ImgRaw&r=0">
                </div>
                <div class="contact-icon">
                    <img class="contact-icon-img" src="https://th.bing.com/th/id/R.56bf037c7207416777ad41a73b6d2e3a?rik=SKPLpqoP%2b6cDlQ&pid=ImgRaw&r=0">
                </div>
            </div>
        </div>

        <div class="contact-col-description">
            <img id="contact-image" src="https://th.bing.com/th/id/R.7af7dc76d48738af83e11599ffbdd2c7?rik=IXqS1XBgE32GXg&pid=ImgRaw&r=0">
        </div>
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
