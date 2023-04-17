<?php

use app\core\Application;
use app\core\component\Component;
use app\core\form\Form;
use app\models\Employee;

$form=new Form();
$form->begin('update-channeling?cmd=update&id='.Application::$app->session->get('selected_channelingh'),'post');
$component=new Component();
$employeemodel=new Employee();
?>
<section>
    <section class="upper-update">
        <div>
            <div>
                    <?=$form->spanfield($model,'speciality','Speciality','field','text',''); ?>
                    <?=$form->spanfield($model,'fee','Fee','field','number',''); ?>
                    <?=$form->select($model,'room','Room','field',$rooms,'')?>
                    <?=$form->spanfield($model,'total_patients','Total Patients','field','text',''); ?>
                    <?=$form->spanfield($model,'percentage',"Doctor's Income Percentage",'field','text',''); ?>
            </div>
            <div>
                <div class="nurse-assign-body">
                        <div>
                            <h3>Assigned Nurses</h3>
                            <div class="nurse-container"></div>
                            <center><?php  echo $component->searchbar($employeemodel,'nurse','search-bar--class2','Search by nurse name',"search-nurse");?></center>
                        </div>
                        <div class="nurse-list">
                            <?php foreach($nurses as $nurse):?>
                                <div class="nurse-wrapper class none" id=<?="'".$nurse['name']."'"?>>
                                    <div class="nurse-item" >
                                        <img src=<?="./media/images/emp-profile-pictures/".$nurse['img']?>>
                                        <?=$nurse['name']?>
                                        <?php if($employeemodel->isNurse($nurse['emp_ID'],$id)): ?>
                                            <input type="checkbox" name="emp_ID[]" class="checkbox"  value=<?=$nurse['emp_ID']?> checked>
                                        <?php else: ?>
                                            <input type="checkbox" name="emp_ID[]" class="checkbox" value=<?=$nurse['emp_ID']?>>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>    
                    </div>        
            </div>
        </div>
        <div>
            <?=$component->button('submit','submit','Submit','',''); ?>
        </div>
        <?php $form->end() ?>
    </section>
    <section class="lower-update">
        <?php foreach($openedchannelings as $op): ?>
            <div>
                <?php var_dump($op); ?>
                <?=$component->button('btn','','Cancel Channeling Session','button-class-01 cancel-btn',$op['opened_channeling_ID']) ?>
                <?php if($op['status']=='closed'): ?>
                        <?=$component->button('btn','','Open Channeling Session','button-class-01 open-btn',$op['opened_channeling_ID']) ?>
                    <?php else:?>
                        <?=$component->button('btn','','Close Channeling Session','button-class-01 close-btn',$op['opened_channeling_ID']) ?>
                <?php endif;?>
            </div>
        <?php endforeach;?>
    </section>
</section>
<script>
    const closebtn=document.querySelectorAll('.close-btn');
    const cancelbtn=document.querySelectorAll('.cancel-btn');
    const openbtn=document.querySelectorAll('.open-btn');
    const chechbox=document.querySelectorAll('.checkbox');
    const nurseContainer=document.querySelector(".nurse-container");
    const nurseList=document.querySelector(".nurse-list");
    const searchBar=document.getElementById('search-nurse');
    const nurses=document.querySelectorAll(".class");
    openbtn.forEach((elem)=>{
        elem.addEventListener('click',()=>{
            location.href="update-channeling?spec=opened_channeling&cmd=open&id="+elem.id;
        });
    });
    closebtn.forEach((elem)=>{
        elem.addEventListener('click',()=>{
            location.href="update-channeling?spec=opened_channeling&cmd=close&id="+elem.id;
        });
    });
    cancelbtn.forEach((elem)=>{
        elem.addEventListener('click',()=>{
            location.href="update-channeling?spec=opened_channeling&cmd=cancel&id="+elem.id;
        });
    });
    chechbox.forEach(function(elem) {
        if(elem.checked)nurseContainer.appendChild(elem.parentNode);
        else {
            
            elem.parentNode.classList.add('none');    
        }  
    });
    chechbox.forEach(function(elem) {
        elem.addEventListener("change", function() {
        if(elem.checked)nurseContainer.appendChild(elem.parentNode);
        else {
            
            nurseList.appendChild(elem.parentNode);
            elem.parentNode.classList.add('none');
            
        }
            
        });
    });
   
    function hide(element,hideClass='hide',visibleClass='field'){
        element.classList.remove(visibleClass);
        element.classList.add(hideClass);
    }
    function visible(element,hideClass='hide',visibleClass='field'){
        element.classList.remove(hideClass);
        element.classList.add(visibleClass);
    }

    function checker(){
        
        var re=new RegExp("^"+searchBar.value)
        nurses.forEach((el)=>{
            if(searchBar.value.length==0){
                el.classList.add("none")
            }
            else if(re.test(el.id) ){
                el.classList.remove("none");
            }
            else{
                el.classList.add("none");
               
            }
        })
    }
    searchBar.addEventListener('input',checker);

</script>