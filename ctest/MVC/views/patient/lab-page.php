<?php

use app\core\component\Component;
use app\core\component\PopUp;
use app\core\component\ScatterChart;
use app\core\form\Form;
use app\models\Employee;
use app\models\LabReport;

$form=new Form();
$component=new Component();
$employeeModel=new Employee();
$labReportModel=new LabReport();
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
        <div class="labtest-container">
                <?="Test :".$seltest[0]['name']."<br> Hospital Fee :LKR ".$seltest[0]['hospital_fee'].".00 <br>Test Fee :LKR ".$seltest[0]['test_fee'].".00" ?>
        </div>
        <?php endif;?>
    </div>
    <?php $form->end(); ?>
    <div class="request-big-container">
        <center><h2>Lab Requests</h2></center>
        <div class="request-list">
        <?php if($requests): ?>
            <?php foreach($requests as $request):?>
                <?php $doctor=$employeeModel->fetchAssocAll(['nic'=>$request['doctor']])[0]['name'] ?>
                <div class="requests"> 
                    <?="Dr. ".$doctor." ".$request['name']." Report Request" ?>
                    <?php if($labReportModel->isreport($request['request_ID'])):?>
                        <?php $report=$labReportModel->fetchAssocAll(['request_ID'=>$request['request_ID']]);?>
                        <br><a href=<?="handle-labreports?spec=lab-report&cmd=view&id=".$report[0]['report_ID']?>>
                            <?=$request['name']." Report-".$report[0]['upload_date']?>
                        </a>
                    <?php else:?>
                       <div class="flex"><br> Request Pending <img src="./media/anim_icons/lab-report.gif"></div>
                    <?php endif;?>
                </div>
            <?php endforeach;?>
        <?php endif;?>
        </div>
    </div>
</section>