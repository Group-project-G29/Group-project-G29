<!DOCTYPE html>
<div class="channeling-patient">
    <?php
use app\core\component\Component;
use app\models\Referral;
use app\core\Application;
use app\core\form\Form;
use app\models\Appointment;
use app\models\ChartModel;
use app\models\Employee;
use app\models\LabReport;
use app\models\LabTest;
use app\models\LabTestRequest;
$employeeModel=new Employee();
$labreportModel=new LabReport();
$appointment=$appointment[0]; 
$referralModel=new Referral();
$appointmentModel=new Appointment();
$labTestModel= new LabTest();
$labrequestModel=new LabTestRequest();
$form=new Form();
$chart=new ChartModel();
$class='';
$popup=Application::$app->session->get('popup')??null;
if( (isset($popup)) || $popup=='unset' ) $class='hide';
Application::$app->session->set('popup','unset');
?>
    <?php $component=new Component(); ?>
    <div class='background--1 hide'> </div>
    <div class=<?='"labtest-popup'.' '."$class".'"'?> id=<?="'".$class."'"?>>
        <h3>Add lab test here</h3>
        <?php $form->begin('/ctest/doctor-labtest','post'); ?>
        <div class="labtest-request-con1">
            <div>
                <?=  $form->editableselect('name','Test Name*','',$labTestModel->getAllTests()); ?>
                <?= $form->textarea(new LabTestRequest,'note','note','Note',3,28,'');?>
            </div>
            <div>
                <?= $component->button('btn','submit','Request','',''); ?>
            </div>
        </div>
        <?php $form->end(); ?>
        <div class="scrollable-labtest-container">
            <?php $labrequests=$labrequestModel->getLabTestRequests();?>
            <h3>Lab test requests </h3>
            <?php foreach($labrequests as $labrequest): ?>
                <?php if(!$labreportModel->isreport($labrequest['request_ID'])): ?>
                    <div>
                        <h4><?=$labrequest['name']."  date :".$labrequest['requested_date_time']?></h4>
                        <?=$component->button('btn','','Cancel','rqst-rmv',$labrequest['request_ID']); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

    </div>
    <div class="labreport-popup hide" id="popup-report">
        <div class="labreport-popup-wrapper">
            <h3 class="fs-50">Add Lab reports here.</h3>
            <?php $form2=Form::begin("upload-reports",'post');?>
            <?php echo $form->editableselect('type','Report Type','field',['CBC'=>'CBC(Complete Blood Count)']) ?>
            <input type='file' name='report[]' multiple/> 
            <?=$component->button("Done","submit","Done","button--class-0",);?>
            <?php $form2=Form::end();?>
        </div>        
    </div>
    <div class="assistance-container">
        <div class="assistance-subcontainer ">
            
            <div class="flex-0">
                <?= $component->button('referral','','Referral','button--class-doc1 btn-1','referrals');?>
                <?=  $component->button('consultaion','','Recent reports ','button--class-doc1 btn-1',"last-consultation");?>
            </div>
            <div class="variable-container--1">
                <div class="wrapper--referrals">
                    <div class="variable-container">
                        <table>
                            <tr class="fixer">
                                <th>Referral</th><th>Added Date</th><th></th><th></th>
                            </tr>
                            <?php foreach($referrals['sent'] as $referral): ?>
                    
                                <tr class="border-bottom--1">
                                    <td><a href=<?="/ctest/doctor-report?spec=referral&mod=view&id=".$referral['ref_ID']?>><?=$referral['issued_doctor']?("Dr. ".$employeeModel->getDocName($referral['issued_doctor']))."'s":"Softcopy-".$referral['ref_ID']."' "."Referral"?></a></td><td><?=$referral['date'] ?></td>
                                   
    
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <div class="ass-button-set">
                            <?=$component->button('write referral','','Write Referral','button--class-0','write-ref');?>
                           
                    </div>
                    
                </div>
               
                <div class="wrapper--last-consultation none">
                    <div class="variable-container">
                    <table>
                        <tr>
                            <th>Recent Report</th><th>created date</th>
                        </tr>
                        <?php foreach($recent as $re): ?>
                            
                            <tr>
                                <td><a href=<?="doctor-report?spec=".$re['type']."-report&mod=view&id=".$re['report_ID']?>><?=$re['label']?></a></td><td><?=$re['uploaded_date'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
            
        <div class="main-detail-container">
            <div class="main-nav">
                <div class="number-pad">
                    <div class="number-item--white">
                        <?=$appointmentModel->getUsedPatient(Application::$app->session->get('channeling'))?>
                        
                    </div>
                    <div class="number-item--blue">
                        <?=$appointmentModel->getTotoalPatient(Application::$app->session->get('channeling'))?>
                    </div>
                </div>
                <div class="nav_container-db">
                    <div class="nav-item">
                       
                        <input type="number" id="patient-input">
                        
                        <?=$component->button('btn','','Move','','move-btn'); ?>
                       
                    </div>
                    <div class="nav-item" id="nav-finish">
                        Finish Session
                    </div>
                    <div class="nav-item nav_last_seen" id=<?=Application::$app->session->get('channeling') ?>>
                        Go To Last Seen Patient
                    </div>
                </div>
            </div>
            <div class="patient-navigator">
                <img src="media/images/channeling assistance/left-chevron.png" class="previous" id=<?="previous-".$appointment['patient_ID']?>>
                <h3>Patient Name : <?=$appointment['name']?></h3>
                <img src="media/images/channeling assistance/right-chevron.png" class="next" id=<?="next-".$appointment['patient_ID']?>>
            </div>
            <div>
                
                <?php  if($status=='used'): ?>
                    Checked Patient :<input type="checkbox" name="checked" id="checkbox" checked>
                <?php else:?>
                    Checked Patient :<input type="checkbox" name="checked" id="checkbox">
                <?php endif;?>
            </div>
            <div>
                <center>
                <table>
                    <tr><td>Quene No :</td><td class="Qno"><?=$appointment['queue_no']?></td></tr>
                    <tr><td>Age :</td><td><?=$appointment['age']." yrs"?></td></tr>
                    <tr><td>Gender :</td><td><?=$appointment['gender']?></td></tr>
                </table>
                </center>
                
                <?=$component->button('btn','','Switch to Report Consultation Queue','button-class--darkblue switch',$appointment['patient_ID']);?>
            </div>
            <div class="result-container">
                <table class="fs-100">
                    <?php foreach($pretestvalues as $element):?>
                        <h4><?=$element['name']." :".$element['value'].$element['metric']?></h4>
                    <?php endforeach; ?>
                </table>
                <section class="graph">
                    <div class="editable-select">
                        <?=$form->editableselectversion2('tests_ed','','test_select',array_keys($alltests)) ?>
                    </div>
                    <div class="graph-part">
                    <?php foreach($alltests as $val=>$test):?>
                        
                        <div id=<?='"'.'c'.join('_',explode(' ',$val)).'"'?> class=<?='"'.'c'.join('_',explode(' ',$val)).' hide gcontainer"'?> class="chart-ast">
                            
                            <canvas id=<?='"myChart'.$val.'"'?> style="width:100%;max-width:700px chart-ast"></canvas>
                            <?=$chart->lineChart($test['labels'],[],$test['data'],[],$val,'rgb(0,0,0)',$val);?>
                        </div>
                        <?php endforeach;?>
                    </div>
                </section>
            </div>
       
        </div>
        <div class="assistance-subcontainer">
            <div class="flex-0">
                <?=$component->button('report','','View Reports','button--class-doc1 btn-2',"reports");?>
                <?=$component->button('prescription','','View Prescription','button--class-doc1 btn-2',"prescriptions");?>
                <?=$component->button('lab test','','View Lab Tests','button--class-doc1 btn-2',"lab-tests");?>
            </div>
            <div class="variable-container--2">
                <div class="wrapper--reports">
                    <div class="variable-container-item flex">
                        <div>
                            <table>
                                <tr>
                                    <th>Medical Report</th><th>created date</th>
                            
                                </tr>
                                <?php foreach($reports as $report): ?>
                                <tr class="table-row">
                                    <td><a href=<?="doctor-report?spec=".$report['type']."&mod=view&id=".$report['report_ID']?>><?=$report['type']."-".$report['report_ID']."-".$report['name']?></a></td><td><?=$report['uploaded_date'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                    <div class="ass-button-set">
                        <?=$component->button('Upload report','','Upload Report','button--class-0','upload-rep');?>
                        <?=$component->button('write report','','Write Report','button--class-0','write-rep');?>
                    </div>
                </div>
                <div class="wrapper--prescriptions none">
                    <div class="variable-container-item flex">
                        <div>
                            <table>
                                <tr>
                                    <th>Prescription</th><th>created date</th><th></th><th> </th>
                                </tr>
                                <?php foreach($prescription as $pres): ?>
                                <tr>
                                    <td><a href=<?="doctor-prescription?spec=prescription&mod=view&id=".$pres['prescription_ID']?>><?=$pres['type']."-".$pres['prescription_ID']?></a></td><td><?=$pres['uploaded_date'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        
                    </div>
                    <div class="ass-button-set">    
                        <?=$component->button('write Prescription','','Write Prescription','button--class-0','write-pres');?>
                    </div>
                </div>
                <div class="wrapper--lab-tests none">
                    <div class="variable-container-item flex">
                        <div>
                            <table>
                                <tr>
                                    <th>Lab Test Report</th><th>created date</th><th></th>
                                </tr>
                                <?php foreach($labreports as $value=>$labreport): ?>
                                
                                <tr>
                                    <td><a href=<?="handle-labreports?spec=lab-report&cmd=view&id=".$value?>><?=$labreportModel->getTitle($value)."-".$value?></a></td><td><?=$labreportModel->getCreatedDate($value) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        
                    </div>
                    <div class="ass-button-set">
                        <?=$component->button('Request Lab Test','','Request Lab Test','button--class-0','req-lab');?>
                    </div>
                </div>
                <div class="wrapper--medical-analysis none">
                    <div class="variable-container-item flex">
                        <div>
                            <table>
                                <tr>
                                    <th>Medical Report</th><th>created date</th>
                                </tr>
                                <?php foreach($reports as $report): ?>
                                
                                <tr>
                                    <td><a href="#"><?=$report['issued_doctor']."-".$report['type']."-".$report['ref_ID']?></a></td><td><?=$report['date'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <div>
                            <?=$component->button('write referral','','Write Referral','button-class--lightblue','write-ref');?>
                            <?=$component->button('Upload referral','','Upload Referral','button-class--lightblue','upload-ref');?>
                        </div>
                    </div>
                </div>
            </div>
          
        </div>
    </div>


<script>

        const next=document.querySelector(".next");
        const previous=document.querySelector(".previous");
        const checkbox=document.getElementById('checkbox');
        const switchBtn=document.querySelector('.switch');
        
        function redirect(element,location){
            return element.addEventListener('click',()=>{
                location.href=location;
            })
        }
        switchBtn.addEventListener('click',()=>{
            location.href="channeling-assistance?cmd=switch&id="+switchBtn.id;
        })
        next.addEventListener('click',()=>{
            id_component=(next.id).split("-");
            // console.log(id_component);
            if(checkbox.checked){
                location.href="channeling-assistance?cmd=next&id="+id_component[1]+"&set=used";
            }
            else{
                location.href="channeling-assistance?cmd=next&id="+id_component[1]+"&set=unused";
            }
        })

        previous.addEventListener('click',()=>{
            id_component=(next.id).split("-");
            if(checkbox.checked){
                location.href="channeling-assistance?cmd=previous&id="+id_component[1]+"&set=used";
            }
            else{
                location.href="channeling-assistance?cmd=previous&id="+id_component[1]+"&set=unused";
            }
        })
        const  movebtn=document.getElementById('move-btn');
        const painput=document.getElementById('patient-input');
        movebtn.addEventListener('click',()=>{
            console.log("dfdf");
            if(checkbox.checked){
                location.href="channeling-assistance?cmd=move&id="+painput.value+"&set=used";
                
            }
            else{
                location.href="channeling-assistance?cmd=move&id="+painput.value+"&set=unused";
                
            }
        })

        function hide(element,hideClass='none'){
            element.classList.add(hideClass);
            // console.log(element);
        }
        function visible(element,hideClass='none'){
            element.classList.remove(hideClass);
            // console.log(element);
        }
        function traverseHide(array,DOMelement){
            array.forEach(element => {
                wrapper=document.querySelector(".wrapper--"+element);
                
                if(DOMelement.id!=element){
                    hide(wrapper);
                    
                }
                else{
                    visible(wrapper);
                }
            });
        }
        const btn2=document.querySelectorAll(".btn-2");
        btn2.forEach(el=>{
            el.addEventListener('click',()=>{
                el.classList.add('doc-button--selected');
                traverseHide(['reports','prescriptions','lab-tests'],el);
            })
        })
        const btn1=document.querySelectorAll(".btn-1");
        btn1.forEach(el=>{
            el.addEventListener('click',()=>{
                traverseHide(['referrals','last-consultation'],el);
            })
        })
        const repbtn=document.getElementById("write-rep");
      
        repbtn.addEventListener('click',()=>{
           
            // console.log(id_component);
            location.href="doctor-report?spec=consultation";
        })
        const refbtn=document.getElementById("write-ref");
      
        refbtn.addEventListener('click',()=>{
           
            // console.log(id_component);
            location.href="doctor-report?spec=referral";
        })
        const presbtn=document.getElementById("write-pres");
      
        presbtn.addEventListener('click',()=>{
           
            // console.log(id_component);
            location.href="doctor-prescription";
        })
       
        const bg=document.querySelector(".background--1");
       // bg.classList.add('hide');
        const reqBtn=document.getElementById('req-lab');
        const reqPopup=document.querySelector('.labtest-popup');
        
        //reqPopup.classList.add('hide');
        if(""+reqPopup.id=='visible'){
            reqPopup.classList.remove('hide');
            bg.classList.remove('hide');
        }
        reqBtn.addEventListener('click',()=>{
            bg.classList.remove('hide');
            reqPopup.classList.remove('hide');
        })
        const rqstRmv=document.querySelectorAll('.rqst-rmv');
        rqstRmv.forEach((elem)=>{
            elem.addEventListener('click',()=>{
                location.href="/ctest/doctor-labtest?cmd=delete&id="+elem.id;
            })
            
        })
        
        const uprbtn=document.getElementById('upload-rep');
        const popup=document.querySelector('.labreport-popup');
        uprbtn.addEventListener('click',()=>{   
            popup.classList.remove('hide');
            bg.classList.remove('hide');
        })
        bg.addEventListener('click',()=>{
            bg.classList.add('hide');
            reqPopup.classList.add('hide');
            popup.classList.add('hide');
        })
        const finbtn=document.getElementById('nav-finish');
        const lasbtn=document.querySelector('.nav_last_seen');
        lasbtn.addEventListener('click',()=>{
            location.href="channeling-assistance?cmd=start&id="+lasbtn.id;
        })
        finbtn.addEventListener('click',()=>{
            location.href="http://localhost/ctest/channeling-assistance?cmd=finish";
        })

    </script>
