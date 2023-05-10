<?php

use app\core\component\Component;
use app\core\form\Form;
use app\core\Request;
use app\models\LabReport;
use app\models\Prescription;
$request=new Request();
$params=$request->getParameters();
$search=0;
if(isset($params[1]['search'])){
    $search=$params[1]['search'];
}
$component=New Component();
$labreportModel=new LabReport();
$prescriptionModel=new Prescription();
$form=new Form();

?>
<section class="document-section">
<section class="docu-searchbar-container">
    <?=$component->searchbar($component,'','search-bar--class1','Search by report type','search-bar'); ?>
    <?=$form->spanselect($labreportModel,'type','Filter ','filter',['All'=>'all','Lab Reports'=>'labreports','Consultation Reports'=>'consultation','Medical History'=>'medical-history','Prescriptions'=>'prescription'],'filter')?>
</section>
<section class="document-main-container">
    <?php  foreach($labreports as $labreport): ?>
        <div class="document-container" id=<?="'".$labreportModel->getTitle($labreport['report_ID'])."'" ?>>
            <div class="document-image">
                <img src="./media/images/common/labreport.png">
            </div>
            <a href=<?="'"."handle-labreports?spec=lab-report&cmd=view&id=".$labreport['report_ID']."'" ?>><?=($labreportModel->getTitle($labreport['report_ID'])?$labreportModel->getTitle($labreport['report_ID']):'Lab Report')."-".$labreportModel->getUploadedDate($labreport['report_ID'])?></a>
        </div>
    <?php endforeach;?>
    <?php  foreach($reports as $report): ?>
        <div class="document-container" id=<?="'".$report['type']."'" ?>>
            <div class="document-image">
                <img src="./media/images/common/medicalreport.png">
            </div>
            <a href=<?="'"."handle-documentation?spec=".$report['type']."&mod=view&id=".$report['report_ID']."'" ?>><?=$report['type']." Report-".$report['uploaded_date']?></a>
        </div>
    <?php endforeach;?>
    <?php  foreach($prescriptions as $prescription): ?>
        <div class="document-container" id="prescription">
            <div class="document-image">
                <img src="./media/images/common/prescription.png">
            </div>
            <div class="referral-name">
                <a href=<?="'"."handle-documentation?spec=prescription"."&mod=view&id=".$prescription['prescription_ID']."'" ?>><?="Prescription-".$prescription['uploaded_date']?></a>
                <?php if(!$prescriptionModel->isInCart($prescription['prescription_ID'])):?>
                    <div>
                        <?=$component->button('btn','','Add to Cart','button--class-0 btn-presc',$prescription['prescription_ID']); ?>
                    </div>
                    <?php endif;?>
                </div>
        </div>
    <?php endforeach;?>
   

</section>
</section>
<script>
    const searchBar=document.getElementById('search-bar');
    const documents=document.querySelectorAll('.document-container');
      function checker(){
          var re=new RegExp("^"+searchBar.value)
          documents.forEach((el)=>{
              comp=""+el.id;
              
              if(searchBar.value.length==0){
                  // el.classList.add("none")
                }
                else if(re.test(comp)){
                    el.style.display='flex';
                }
                else{
                el.style.display='none';
               
            }
            })
        
            if(searchBar.value.length==0){
                documents.forEach((el)=>{
                    el.style.display='flex';
                }) 
            }
            
      }
      const filter1=document.getElementById('filter');
      function filter(){
        
      
          documents.forEach((el)=>{
              comp=""+el.id;
              
                console.log(el.id);
                if(comp==filter1.value){
                    el.style.display='flex';
                }
                else{
                    el.style.display='none';
               
                 }
            })
        
            if(filter1.value=='all'){
                documents.forEach((el)=>{
                    el.style.display='flex';
                }) 
            }
            if(filter1.value=='labreports'){
                documents.forEach((el)=>{
                    if(el.id!='prescription' || el.id!='consultation' || el.id!='soap-report' || el.id!='medical-history'){
                        el.style.display='flex';
                    }
                }) 
            }
      }
        filter1.addEventListener('change',filter);
            <?php if($search):?>
                var re=new RegExp("^"+<?="'".$search."'"?>)
                searchBar.value=<?="'".$search."'\n"?>
                documents.forEach((el)=>{
              comp=""+el.id;
              
              if(searchBar.value.length==0){
                  // el.classList.add("none")
                }
                else if(re.test(comp)){
                    el.style.display='flex';
                }
                else{
                el.style.display='none';
               
            }
            })

            <?php endif;?>
            
        
        searchBar.addEventListener('input',checker);
        const btns=document.querySelectorAll(".btn-presc");
        btns.forEach((elem)=>{
            elem.addEventListener('click',()=>{
                location.href="handle-documentation?spec=sec-prescription&cmd=add&id="+elem.id;
            });
        })
</script>
