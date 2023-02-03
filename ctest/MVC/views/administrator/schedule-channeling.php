<?php
use app\core\DbModel;
use \app\core\form\Form;
use app\core\component\Component;
$component=new Component();
$form=Form::begin('','post');

?> 
<section class="scheduling-container">
    
    <div class="form-body">
        <div class="input-container">
            <table>
                <?php echo $form->spanselect($employeemodel,'doctor','field',$doctors)?>
                <?php echo $form->spanselect($channelingmodel,'speciality','field',['Dental'=>'Dental','Cardiology'=>'Cardiology','Radiology'=>'Radiology','Gastrology'=>'Gastrology'])?>
                <?php echo $form->spanfield($channelingmodel,'start_date','Starting Date*','field','date') ?>
                <?php echo $form->spanfield($channelingmodel,'time','Starting Time*','field','time') ?>
                <?php echo $form->spanselect($channelingmodel,'day','field',['Monday'=>'Monday','Tuesday'=>'Tuesday','Wednesday'=>'Wednesday','Thursday'=>'Thursay','Friday'=>'Friday','Saturday'=>'Saturday'])?>
                <?php echo $form->spanfield($channelingmodel,'count','Frequency count*','field','text') ?>
                <?php echo $form->spanselect($channelingmodel,'type','field',['Week'=>'Week','Month'=>'Month','Year'=>'Year'])?>
                <?php //echo $form->spanfield($channelingmodel,'total_patients','Total Patient Count*','field','text') ?>
                <?php //echo $form->spanfield($channelingmodel,'extra_patients','Number of Extra Patients*','field','text') ?>
                
                <tr>
                    <td>
                        <label for="limitCheckbox">Limit Patients</label>
                    </td>
                    <td>
                        <input type="checkbox" class="limitCheckbox" name="limitCheckbox" id="limitCheckbox" onclick="checkMe()"/>
                    </td>
                </tr>
                <!-- <tr class="hideLine" hidden>
                    <td>fhgfhljkx</td>
                    <td>jghjkl</td>
                </tr>    -->
                <div class="hideLine" ><?php echo $form->spanfield($channelingmodel,'total_patients','Limit Patient Count','field','text') ?></div>   
                
                <!-- <?php // echo $form->spanfield($channelingmodel,'max_free_appointments','Maximum Free Appointments*','field','text') ?> -->
                <?php echo $form->spanfield($channelingmodel,'fee','Baic Fee*','field','text') ?>
                <?php echo $form->spanfield($channelingmodel,'percentage','Doctor\'s Income Percentage*','field','text') ?>
                <?php echo $form->spanselect($channelingmodel,'room','field',$rooms)?>
            </table>
        </div>
    </div>
    <?php Form::end() ?>
    <div class="nurse-assign-body">
        <div>
            <h3 class="fs-200 ">Assign Nurses</h3>
            <div class="nurse-container"></div>
            <?php  echo $component->searchbar($employeemodel,'nurse','search-bar--class2','Search by nurse name',"search-nurse");?>
        </div>
        <div class="nurse-list">
            <?php foreach($nurses as $nurse):?>
                <div class="nurse-wrapper class none" id=<?=$nurse['name']?>>
                    <div class="nurse-item" >
                        <img src=<?="./media/images/emp-profile-pictures/".$nurse['img']?>>
                        <?=$nurse['name']?>
                        <input type="checkbox" name="emp_ID[]" class="checkbox" value=<?=$nurse['emp_ID']?>>
                    </div>
                </div>
            <?php endforeach;?>
           
        </div>
        <div class="nurse-assign-body-button">
            <?php echo $component->button('submit','submit','Create Channeling Session','button--class-1') ?>
        </div>
    </div>
</section>
<script>
    
     const chechbox=document.querySelectorAll('.checkbox');
     const nurseContainer=document.querySelector(".nurse-container");
     const nurseList=document.querySelector(".nurse-list");
     const searchBar=document.getElementById('search-nurse');
    const nurses=document.querySelectorAll(".class");
    console.log(chechbox);
    chechbox.forEach(function(elem) {
            elem.addEventListener("change", function() {
            if(elem.checked)nurseContainer.appendChild(elem.parentNode);
            else {
               
                //nurseList.appendChild(elem.parentNode);
                
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


    function checkMe(){
        const checkActive = document.querySelector(".limitCheckbox");
        const popLine = document.querySelector(".hideLine");
        if(checkActive.checked){
            visible(popLine)
            
        }
        else{
            hide(popLine)
        }
    }

    // const checkActive = document.getElementById("limitCheckbox");
    // const popLine = document.querySelector(".hideLine");
    // checkActive.addEventListener("click", ()=>{
    //     if(checkActive.checked){
    //         popLine.hidden=false;
    //     }
    //     else{
    //         popLine.hidden=true;
    //     }
    // });
   
</script>
   