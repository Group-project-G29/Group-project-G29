<?php

use app\core\Application;
use app\core\component\Component;
use app\core\form\Form;
use app\models\AdminNotification;
use app\models\Channeling;
use app\models\OpenedChanneling;
use app\models\PreChanneilngTestsValue;
use app\models\PreChannelingTest;

    $form=new Form();
    $component=new Component();
    $preModel=new PreChanneilngTestsValue();
    $openeChanneling=new OpenedChanneling();
    $adminN=new AdminNotification();
    
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
            <div class="popup-button-flex">
                <?=$form->editableselect("test_".$channeling['channeling_ID'],"Select Pre-channeling Test",'tinput_'.$channeling['channeling_ID'],['weight'=>'weight','height'=>'height','blood pressure'=>'blood pressure']); ?>
                <?= $component->button('btn','','Add','add-btn button--class-0',$channeling['channeling_ID'] ); ?>
            </div>
                <div >
                    <?php $tests=$preModel->getTestsByOp($channeling['channeling_ID']); ?>
                    <?php $channelings=$openeChanneling->getOpenedChannelings($channeling['channeling_ID']); ?>
                    <div class="test-names">
                    <?php foreach($tests as $test):?>
                        <div class="popup-test">
                            <?php echo '<br>'.$test['name']; ?>
                        </div>
                    <?php endforeach;?>
        
                    <div class="popup-channelings">
                    <h3>Currently Opened Channelings</h3>
                    <?php foreach($channelings as $ch):?>
                           <?php $channelingModel=new  Channeling() ?>
                            <?php $time=$channelingModel->fetchAssocAll(['channeling_ID'=>$ch['channeling_ID']])[0]['time']; ?>
                            <div>
                                <?php echo '<br>'."Channeling On ".$ch['channeling_date']." at time ".substr($time,0,5).(($time>='12:00')?'PM':'AM'); ?>
                                <?php if($ch['status']=='closed' || $ch['status']=='cancelled'): ?>
                                    <?=$component->button('btn-op','','Request to Channeling','open',$ch['opened_channeling_ID']); ?>
                                <?php else:?>
                                    
                                    <?php if($adminN->isThereNoti($ch['opened_channeling_ID'])!='close'): ?>
                                    Cancellation Request has been sent
                                    <?=$component->button('btn-close','','Request to Close Channeling','close',$ch['opened_channeling_ID']); ?>
                                    <?php endif;?>
                                    <?php if($adminN->isThereNoti($ch['opened_channeling_ID'])!='cancel'): ?>
                                        <?=$component->button('btn-cancel','','Request to Cancel Channeling','cancel',$ch['opened_channeling_ID']); ?>
                                        Closing Request had been sent
                                    <?php endif;?>
            
                                <?php endif;?>        
                            </div>
                            <?php endforeach;?>
                        </div>
                    </div>
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
        closes=document.querySelectorAll('.close');
        cancels=document.querySelectorAll('.cancel');
        opens=document.querySelectorAll('.open');
        closes.forEach((el)=>{
            el.addEventListener('click',()=>{
                location.href="notification?cmd=close&id="+el.id;
            })
        })
        opens.forEach((el)=>{
            el.addEventListener('click',()=>{
                location.href="notification?cmd=open&id="+el.id;
            })
        })
        cancels.forEach((el)=>{
            el.addEventListener('click',()=>{
                location.href="notification?cmd=cancel&id="+el.id;
            })
        })
         

</script>
