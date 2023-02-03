<?php

use app\core\component\Component;
use app\core\form\Form;

    $component=new Component();
    $form=new Form();
    $form->begin('','POST');
    echo $component->button('pay-btn','submit','Complete Payment','btn-class--1','');
    $form->end();
?>
