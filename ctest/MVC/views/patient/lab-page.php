<?php

use app\core\component\Component;
use app\core\component\PopUp;
use app\core\component\ScatterChart;
use app\core\form\Form;

$form=new Form();
$component=new Component();
?>


<section class="lab-main">
    <?php $form->begin('','post'); ?>
    <div class="search-medicine">
        Check Our Available Medical Tests
        <div class="lab-test-search">
            <?=$form->editableselect('name','','field',$tests); ?>
            <?=$component->button('','submit','Search','button--class-0',''); ?>
        </div>
        <?php if($seltest): ?>
        <div>
                <?="Test :".$seltest[0]['name']."<br> Hospital Fee :LKR ".$seltest[0]['hospital_fee'].".00 <br>Test Fee :LKR ".$seltest[0]['test_fee'].".00" ?>
        </div>
        <?php endif;?>
    </div>
    <?php $form->end(); ?>

</section>