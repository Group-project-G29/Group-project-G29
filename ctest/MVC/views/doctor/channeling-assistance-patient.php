<div class="channeling-patient">
    <?php

use app\core\component\Component;
use app\models\Referral;
use app\core\Application;
use app\core\form\Form;
use app\models\Appointment;
use app\models\LabTest;
use app\models\LabTestRequest;

$appointment=$appointment[0]; 
$referralModel=new Referral();
$appointmentModel=new Appointment();
$labTestModel= new LabTest();
$labrequestModel=new LabTestRequest();
$form=new Form();
$class='';
$popup=Application::$app->session->get('popup')??null;
if( !(isset($popup)) || $popup=='unset' ) $class='hide';
Application::$app->session->set('popup','unset');


?>
<div class=<?="'background--1 ".$class."'"?>>

</div>
    <?php $component=new Component(); ?>
    <div class=<?='"labtest-popup'.' '."$class".'"'?> id=<?="'".$class."'"?>>
        <h3>Add labtest here</h3>
        <?php $form->begin('/ctest/doctor-labtest','post'); ?>
        <?=  $form->editableselect('name','Test Name*','',$labTestModel->getAllTests()); ?>
        <?= $form->textarea(new LabTestRequest,'note','note','Note',3,5,'');?>
        <?= $component->button('btn','submit','Request','',''); ?>
        <?php $form->end(); ?>
        <div class="scrollable-labtest-container">
            <?php $labrequests=$labrequestModel->getLabTestRequests();?>
            <?php foreach($labrequests as $labrequest): ?>
                <h3><?=$labrequest['name']."  date :".$labrequest['requested_date_time']?></h3>
                <?=$component->button('btn','','Cancel','rqst-rmv',$labrequest['request_ID']); ?>
            <?php endforeach; ?>
        </div>

    </div>
    <div class="assistance-container">
        <div class="assistance-subcontainer ">
            
            <div class="flex-0">
                <?= $component->button('referal','','Referral','button--class-doc1 btn-1','referrals');?>
                <?=  $component->button('consultaion','','Last consultation report ','button--class-doc1 btn-1',"last-consultation");?>
            </div>
            <div class="variable-container--1">
                <div class="wrapper--referrals">
                    <div class="variable-container">
                        <table>
                            <tr>
                                <th>Referral</th><th>Added Date</th><th></th><th></th>
                            </tr>
                            <?php foreach($referrals as $referral): ?>
                                <tr>
                                    <td><a href=<?="/ctest/doctor-report?spec=referral&mod=view&id=".$referral['ref_ID']?>><?=$referral['issued_doctor']."-".$referral['type']."-".$referral['ref_ID']?></a></td><td><?=$referral['date'] ?></td>
                                    <?php if($referralModel->isIssued($referral['ref_ID'],Application::$app->session->get('userObject')->nic)): ?>
                                        <td><?=$component->button('update','','Update','button--class-2-small'); ?></td>
                                        <td><?=$component->button('delete','','Delete','button--class-3-small'); ?></td>
                                    <?php endif; ?>
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
                            <th>LastConsultation Report</th><th>created date</th>
                        </tr>
                        <?php foreach($referrals as $referral): ?>
                            
                            <tr>
                                <td><a href="#"><?=$referral['issued_doctor']."-".$referral['type']."-".$referral['ref_ID']?></a></td><td><?=$referral['date'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
            
        <div class="asssistance-sub-container main-detail-container">
            <div class="number-pad">
                <div class="number-item--white">
                    <?=$appointmentModel->getUsedPatient(Application::$app->session->get('channeling'))?>
                    
                </div>
                <div class="number-item--blue">
                    <?=$appointmentModel->getTotoalPatient(Application::$app->session->get('channeling'))?>
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
                    <tr><td>Age :</td><td><?=$appointment['age']." yrs"?></td></tr>
                    <tr><td>Gender :</td><td><?=$appointment['gender']?></td></tr>
                </table>
                </center>
                
                <?=$component->button('btn','','Switch to Report Consultation Queue','button-class--darkblue switch',$appointment['patient_ID']);?>
            </div>
            <div class="result-container">
                <table class="fs-100">
                    <tr><td>Blood Sugar :</td><td>99 mg/dL</td></tr>
                    <tr><td>Blood Pressure(systolic) :</td><td>120 mmHg</td></tr>
                    <tr><td>Weight :</td><td>70 kg</td></tr>
                    </tr><td>Height :</td><td>170.6 cm</td></tr>
                </table>
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
                                    <td><a href=<?="/ctest/doctor-report?spec=".$report['type']."&mod=view&id=".$report['report_ID']?>><?=$report['type']."-".$report['report_ID']."-".$report['name']?></a></td><td><?=$report['uploaded_date'] ?></td>
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
                                <?php foreach($reports as $report): ?>
                                
                                <tr>
                                    <!-- <td><a href="#"><?=$report['issued_doctor']."-".$report['type']."-".$report['ref_ID']?></a></td><td><?=$report['date'] ?></td> -->
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
                                <?php foreach($reports as $report): ?>
                                
                                <tr>
                                    <!-- <td><a href="#"><?=$report['issued_doctor']."-".$report['type']."-".$report['ref_ID']?></a></td><td><?=$report['date'] ?></td> -->
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



    </script>