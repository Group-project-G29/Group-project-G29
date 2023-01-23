<div class="channeling-patient">
    <?php

use app\core\component\Component;
use app\models\Referral;

  
 $appointment=$appointment[0]; ?>
    <?php $component=new Component(); ?>
    <div class="asssistance-upper-container">
        <div class="number-pad">
            <div class="number-item--white">
                <?=$appointment['queue_no']?>
            
            </div>
            <div class="number-item--blue">
                <?=$appointment['total_patients']?>
            </div>
        </div>
        <div class="patient-navigator">
            <img src="media/images/channeling assistance/left-chevron.png" class="previous" id=<?="previous-".$appointment['patient_ID']?>>
            <h3>Patient Name : <?=$appointment['name']?></h3>
            <img src="media/images/channeling assistance/right-chevron.png" class="next" id=<?="next-".$appointment['patient_ID']?>>
        </div>
        <div>
            Checked Patient :<input type="checkbox" name="checked" id="checkbox">
        </div>
        <div>
            <table>
                <td>Age :</td><td><?=$appointment['age']." yrs"?></td>
                <td>Gender :</td><td><?=$appointment['gender']?></td>
            </table>
        </div>
       
    </div>
    <div class="assistance-container">
        <div class="assistance-subcontainer">
            <div>
                <table class="fs-100">
                    <tr><td>Blood Sugar :</td><td>99 mg/dL</td></tr>
                    <tr><td>Blood Pressure(systolic) :</td><td>120 mmHg</td></tr>
                    <tr><td>Weight :</td><td>70 kg</td></tr>
                    </tr><td>Height :</td><td>170.6 cm</td></tr>
                </table>
            </div>
            <div class="flex-0">
                <?= $component->button('referal','','Referral','button--class-doc1 btn-1','referrals');?>
                <?=  $component->button('consultaion','','Last consultation report ','button--class-doc1 btn-1',"last-consultation");?>
            </div>
            <div class="variable-container--1">
                <div class="wrapper--referrals">
                    <div class="variable-container">
                        <table>
                            <tr>
                                <th>Referral</th><th>created date</th>
                            </tr>
                            <?php foreach($referrals as $referral): ?>
                            <?php var_dump($referral['ref_ID']); ?>
                            <tr>
                                <td><a href=<?="/ctest/doctor-report?spec=refferal&mod=view&id=".$referral['ref_ID']?>><?=$referral['refer_doctor']."-".$referral['type']."-".$referral['ref_ID']?></a></td><td><?=$referral['date'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
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
                                <td><a href="#"><?=$referral['refer_doctor']."-".$referral['type']."-".$referral['ref_ID']?></a></td><td><?=$referral['date'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="assistance-subcontainer">
            <div class="flex-0">
                <?=$component->button('report','','View Reports','button--class-doc1 btn-2',"reports");?>
                <?=$component->button('prescription','','View Prescription','button--class-doc1 btn-2',"prescriptions");?>
                <?=$component->button('lab test','','View Lab Tests','button--class-doc1 btn-2',"lab-tests");?>
                <?=$component->button('medical_analysis','','Medical Analysis','button--class-doc1 btn-2',"medical-analyisis");?>
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
                                
                                <tr>
                                    <td><a href="#"><?=$report['refer_doctor']."-".$report['type']."-".$report['ref_ID']?></a></td><td><?=$report['date'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <div>
                            <?=$component->button('write report','','Write Report','button--class-0','write-rep');?>
                            <?=$component->button('Upload report','','Upload Report','button--class-0','upload-rep');?>
                        </div>
                    </div>
                </div>
                <div class="wrapper--prescriptions none">
                    <div class="variable-container-item flex">
                        <div>
                            <table>
                                <tr>
                                    <th>Prescription</th><th>created date</th>
                                </tr>
                                <?php foreach($reports as $report): ?>
                                
                                <tr>
                                    <td><a href="#"><?=$report['refer_doctor']."-".$report['type']."-".$report['ref_ID']?></a></td><td><?=$report['date'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <div>
                            <?=$component->button('write report','','Write Report','button--class-0','write-rep');?>
                            <?=$component->button('Upload report','','Upload Report','button--class-0','upload-rep');?>
                        </div>
                    </div>
                </div>
                <div class="wrapper--lab-tests none">
                    <div class="variable-container-item flex">
                        <div>
                            <table>
                                <tr>
                                    <th>Lab Test Report</th><th>created date</th>
                                </tr>
                                <?php foreach($reports as $report): ?>
                                
                                <tr>
                                    <td><a href="#"><?=$report['refer_doctor']."-".$report['type']."-".$report['ref_ID']?></a></td><td><?=$report['date'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <div>
                            <?=$component->button('Request Lab Test','','Write Report','button--class-0','req-lab');?>
                        </div>
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
                                    <td><a href="#"><?=$report['refer_doctor']."-".$report['type']."-".$report['ref_ID']?></a></td><td><?=$report['date'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                        <div>
                            <?=$component->button('write referral','','Write Referral','button--class-0','write-ref');?>
                            <?=$component->button('Upload referral','','Upload Referral','button--class-0','upload-ref');?>
                        </div>
                    </div>
                </div>
            </div>
          
        </div>
    </div>


<script>

        const next=document.querySelector(".next");
        const previous=document.querySelector(".previous");
        function redirect(element,location){
            return element.addEventListener('click',()=>{
                location.href=location;
            })
        }
    
        next.addEventListener('click',()=>{
            id_component=(next.id).split("-");
            // console.log(id_component);
            location.href="channeling-assistance?cmd=next&id="+id_component[1];
        })
        previous.addEventListener('click',()=>{
            id_component=(next.id).split("-");
            location.href="channeling-assistance?cmd=previous&id="+id_component[1];
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
                traverseHide(['reports','prescriptions','lab-tests','medical-analysis'],el);
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
            location.href="doctor-report?spec=consultation-report";
        })
        



    </script>