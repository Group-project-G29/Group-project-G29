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
                <center><h2>Set Doctor</h2></center>
                <?php echo $form->spanselect($channelingmodel,'doctor','Doctor','field',$doctors)?>
                <?php echo $form->spanselect($channelingmodel,'speciality','Speciality','field',['Dental'=>'Dental','Cardiology'=>'Cardiology','Radiology'=>'Radiology','Gastrology'=>'Gastrology'])?>
            </table>
            
            <table>    
                <center><h2 style="margin-top: 5vh;">Set Date Time</h2></center>
                
                <?php echo $form->spanselect($channelingmodel,'day','Day','field',['Monday'=>'Monday','Tuesday'=>'Tuesday','Wednesday'=>'Wednesday','Thursday'=>'Thursay','Friday'=>'Friday','Saturday'=>'Saturday'])?>
                <?php echo $form->spanfield($channelingmodel,'time','Starting Time*','field','time') ?>
                
                <?php echo $form->spanfield($channelingmodel,'schedule_for','Schedule For*','field','text') ?>
                <?php echo $form->spanselect($channelingmodel,'schedule_type','Schedule Type','field',['Week'=>'Week','Month'=>'Month','Year'=>'Year'])?>
                
                <?php echo $form->spanfield($channelingmodel,'start_date','Starting Date*','field','date') ?>

                <?php echo $form->spanfield($channelingmodel,'frequency','Frequency*','field','text') ?>
                <?php echo $form->spanselect($channelingmodel,'frequency_type','Frequency Type','field',['Day'=>'Day','Week'=>'Week','Month'=>'Month','Year'=>'Year'])?>
            </table>    
            
            <table>
                <center><h2 style="margin-top: 5vh;">Set Patient Count</h2></center>
                <tr>
                    <td>
                        <label for="limitCheckbox">Limit Patients</label>
                    </td>
                    <td>
                        <input type="checkbox" class="limitCheckbox" name="limitCheckbox" id="limitCheckbox" onclick="checkMe()"/>
                    </td>
                </tr>
                
                <span id="popLine"></span>
                <!-- <div><?php //echo $form->spanfield($channelingmodel,'total_patients','Limit Patient Count','field','text') ?></div> -->
            </table>

        </div>

        <div class="input-container">
            <table> 
                <center><h2>Set payment detail</h2></center>   
                <?php echo $form->spanfield($channelingmodel,'fee','Basic Fee (Rs.)*','field','text') ?>
                <?php echo $form->spanfield($channelingmodel,'percentage','Doctor\'s Income Percentage (%)*','field','text') ?>
            </table>

            <table>
                <center><h2 style="margin-top: 5vh;">Set Rooms and Nurses</h2></center> 
                <?php echo $form->spanselect($channelingmodel,'room','Room','field',$rooms)?>
            </table>
            
        
            <?php Form::end() ?>

            <div class="nurse-assign-body">
                <div>
                    <h3>Assign Nurses</h3>
                    <div class="nurse-container"></div>
                    <center><?php  echo $component->searchbar($employeemodel,'nurse','search-bar--class2','Search by nurse name',"search-nurse");?></center>
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
                
            </div>

            <div class="nurse-assign-body-button">
                <?php echo $component->button('submit','submit','Create Channeling Session','button--class-1') ?>
            </div>
        </div> 

        
    
    </div>

</section>



<script>
    //test
    // const wrapper=document.querySelector('.wrapper');
    // wrapper.classList.add('hide');
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


    // function checkMe(){
    //     const checkActive = document.querySelector(".limitCheckbox");
    //     const popLine = document.querySelector(".hideLine");
    //     if(checkActive.checked){
    //         console.log(popLine);
    //         popLine.classList.add("block");
    //         // visible(popLine)
            
    //     }
    //     else{
    //         hide(popLine)
    //     }
    // }

    const checkActive = document.getElementById("limitCheckbox");
    checkActive.addEventListener("click", ()=>{
        if(checkActive.checked){
            console.log(popLine);
            document.getElementById("popLine").innerHTML = `<?php echo $form->spanfield($channelingmodel,'total_patients','','field','text') ?>`;
        }
        else{
            document.getElementById("popLine").innerHTML = ``;
        }
    });


</script>
