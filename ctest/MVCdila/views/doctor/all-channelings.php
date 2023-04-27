<?php

use app\core\Application;
use app\core\component\Component;
use app\core\form\Form;
use app\models\PreChanneilngTestsValue;
use app\models\PreChannelingTest;

    $form=new Form();
    $component=new Component();
    $preModel=new PreChanneilngTestsValue();
    
?>

<div class="background hide">

</div>
<section class="doctor-channeling">
    <?php if($channelings): ?>
        <?php foreach($channelings as $channeling):  ?>
            <?php if( Application::$app->session->get("popshow") && Application::$app->session->get("popshow")==$channeling['channeling_ID']): ?>
                <div class=<?="'"."popup-channeling-setting popup_".$channeling['channeling_ID']."  pops'"?>>
            <?php else: ?>
                <div class=<?="'"."popup-channeling-setting popup_".$channeling['channeling_ID']." hide pops'"?>>
            <?php endif; ?>
                <?=$form->editableselect("test_".$channeling['channeling_ID'],"Select Pre-channeling Test",'tinput_'.$channeling['channeling_ID'],['weight'=>'weight','height'=>'height','blood pressure'=>'blood pressure']); ?>
                <?= $component->button('btn','','Add','add-btn',$channeling['channeling_ID'] ); ?>
                <div>
                    <?php $tests=$preModel->getTestsByOp($channeling['channeling_ID']); ?>
                    <?php foreach($tests as $test):?>
                        <?php echo '<br>'.$test['name']; ?>
                    <?php endforeach;?>
                </div>
            </div>
            <div class="doctor-channeling-tile" id=<?="'".$channeling['channeling_ID']."'" ?>>
                <div class=<?="'"."grid".rand(1,4)."'"?>>
                    <h1><?=$channeling['day']?></h1>
                    <div class="img-setting-btn">
                        <img class="setting-img" id=<?=$channeling['channeling_ID'] ?> src="./media/images/channeling assistance/gear.png"?>
                    </div>
                </div> 
                <div>
                    <h3>Speciality:<?=$channeling['speciality']?></h3>
                    <h3>Starting Time:<?=$channeling['time']?></h3>
                    <h3>Room:<?=$channeling['room']?></h3>
                </div>
            </div>
        <?php endforeach;?>
        
    <?php endif; ?>

</section>

<script>
        // elementsArray = document.querySelectorAll(".doctor-channeling-tile");
        // elementsArray.forEach(function(elem) {
        //     elem.addEventListener("click", function() {
        //         location.href='channeling?spec=opened_channelings&cmd=view&id='+elem.id;
        //     });
        // });
        function hideall(){
            pops=document.querySelectorAll(".pops");
            pops.forEach((el)=>{
                 if(!el.classList.contains('hide') ) el.classList.add("hide");
            })
        }
        bg=document.querySelector(".background");
        poparray=document.querySelectorAll(".setting-img");
        poparray.forEach((el)=>{
            el.addEventListener('click',()=>{
                idval=""+el.id; 
                bg.classList.remove('hide');
                elem=document.querySelector(".popup_"+idval);
                if(elem.classList.contains('hide') )
                {
                    hideall();

                    elem.classList.remove('hide');
                }
                else elem.classList.add('hide');
            })

        })
        btns=document.querySelectorAll(".add-btn");
        pops=document.querySelectorAll(".pops");
        btns.forEach((el)=>{
            el.addEventListener('click',()=>{
                input=document.getElementById("input-test_"+el.id);
                location.href="doctor?spec=pre-channeling-test&cmd=add&id="+input.value+"&channeling="+el.id;
            })
        })
        bg.addEventListener('click',()=>{
            bg.classList.add('hide');
            pops.forEach((el)=>{
                    el.classList.add('hide');
             })
        })
         

</script>
