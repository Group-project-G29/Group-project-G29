<?php

use app\core\Application;
use app\core\component\Component;
use app\core\form\Form;
use app\models\Employee;

$form=new Form();
$form->begin('update-channeling?cmd=update&id='.Application::$app->session->get('selected_channeling'),'post');
$component=new Component();
$employeemodel=new Employee();
?>
<section>
    <section class="upper-update">
        <div class="update-deatails">
            <div class="update-deatails-items">
                    <?=$form->spanfield($model,'speciality','Speciality','field','text',''); ?>
                    <?=$form->spanfield($model,'fee','Fee','field','number',''); ?>
                    <?php if(isset($roomOverlaps)):?>
                        <img src="media/images/common/delete.png" class="delete-btn" id="room">
                        <div>
                            <?php var_dump($roomOverlaps); ?>
                        </div>
                    <?php endif;?>
                    <?=$form->select($model,'room','Room','field',$rooms,'')?>
                    <?=$form->spanfield($model,'percentage',"Doctor's Income Percentage",'field','text',''); ?>
                    <tr>
                        <td>
                            <label for="limitCheckbox">Limit Patients</label>
                        </td>
                        <td>
                            <input type="checkbox" class="limitCheckbox" name="limitCheckbox" id="limitCheckbox">
                        </td>
                    </tr> 
                    <span id="popLine"></span> 
            </div>
            <div>
                <div class="nurse-assign-body">
                    <div>
                        <h3>Assigned Nurses</h3>
                        <?php if(isset($nurseOverlaps)):?>
                            <div class="overlaps">
                            <img src="media/images/common/delete.png" class="delete-btn" id="nurse">
                            <div>
                                <?php var_dump($nurseOverlaps); ?>
                            </div>
                            </div>
                        <?php endif;?>
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
                <div class="btn-submit">
                    <?=$component->button('submit','submit','Submit','',''); ?>
                </div>
            </div>
        </div>
        <?php $form->end() ?>
    </section>
    <section class="lower-update">
        <?php foreach($openedchannelings as $op): ?>
            <div class="open-channeling">
                <div class="open-channeling-date flex">
                    <?=ucfirst($op['status'])." channeling session"?> 
                    <span><?= $op['channeling_date']; ?></span>
                </div>    
                <?php if($op['status']=='cancelled'): ?>
                    <?=$component->button('btn','','Open Channeling Session','button-class-01 open-btn open-channeling-btn',$op['opened_channeling_ID']) ?>
                <?php elseif($op['status']=='closed' ): ?>
                        <?=$component->button('btn','','Open Channeling Session','button-class-01 open-btn open-channeling-btn',$op['opened_channeling_ID']) ?>
                        <?=$component->button('btn','','Cancel Channeling Session','button-class-01 cancel-btn open-channeling-btn',$op['opened_channeling_ID']) ?>
                    <?php else:?>
                        <?=$component->button('btn','','Close Channeling Session','button-class-01 close-btn open-channeling-btn',$op['opened_channeling_ID']) ?>
                        <?=$component->button('btn','','Cancel Channeling Session','button-class-01 cancel-btn open-channeling-btn',$op['opened_channeling_ID']) ?>
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
        elem.addEventListener('click',(event)=>{
            event.preventDefault();
            location.href="update-channeling?spec=opened_channeling&cmd=open&id="+elem.id;
        });
    });
    closebtn.forEach((elem)=>{
        elem.addEventListener('click',(event)=>{
            event.preventDefault();
            location.href="update-channeling?spec=opened_channeling&cmd=close&id="+elem.id;
        });
    });
    cancelbtn.forEach((elem)=>{
        elem.addEventListener('click',(event)=>{
            event.preventDefault();
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
      const checkActive = document.getElementById("limitCheckbox");
    checkActive.addEventListener("click", ()=>{
        if(checkActive.checked){
            document.getElementById("popLine").innerHTML = `<?php echo $form->spanfield($model,'total_patients','','field','text') ?>`;
        }
        else{
            document.getElementById("popLine").innerHTML = ``;
        }
    });

</script>