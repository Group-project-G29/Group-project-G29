    <?php
    use app\core\Application;
        Application::$app->session->set('patient',$patient[0]['patient_ID']);
        use app\models\OpenedChanneling;
        use app\models\Appointment;
        $openedChanneling=new OpenedChanneling();
        use app\core\component\Component;


        $component=new Component();
        $appoinment = new Appointment();
        
        ?>

<div class="patient-detail"style=" padding:2vw;height:5vw" >
    <h3>Patient Name : <?=$patient[0]['name']?></h3>
    <h3>NIC :<?=$patient[0]['nic'] ?></h3>
    <h3>Age :<?=$patient[0]['age'] ?></h3>
    
    
</div>
<div class="search-bar-container" style="margin-left:25vw">
    <?php echo $component->searchbar('','search','search-bar--class2','Search by name,specilaity','searchbar');?>
</div>
  
<div class="table-container">

    <table border="0">
        <tr class="row-height header-underline none" id="header">
        <th>Doctor</th> <th>Specialtity</th> <th>Time</th><th>Day</th><th>Fee</th><th></th>
            
        </tr>
        <div class="patient-container">

        <?php   foreach($channelings as $key=>$channeling): ?>
         
        <tr class="table-row none" id=<?="'".$channeling['name']."&".$channeling['speciality']."'"?>>
            
            <td><?=$channeling['name']?></td>
            <td><?=$channeling['speciality']?></td>  
            <td><?=$channeling['time']?></td>
            <td><?=$channeling['day']?></td>
            <td><?=$channeling['fee']?></td>
            <td>
                <div>
                    <?php if($openedChanneling->isPatientIn($patient[0]['patient_ID'],$channeling['opened_channeling_ID'])):?>
                    <div style="color:green"><?php echo "Already have an appointment" ?></div>
                    <?php else: ?>
                        <?php if($appoinment->labReportEligibility($patient[0]['patient_ID'],$channeling['nic'],$channeling['opened_channeling_ID'])):?>
                            <?php echo $component->button('update',' ','Add Lab Report Appointment','button--class-0 new-lab',$channeling['opened_channeling_ID']) ?>
                        <?php else:?>
                            <?php echo $component->button('update',' ','Add Ordinary Appointment','button--class-0 new-app',$channeling['opened_channeling_ID']) ?>
                            <?php endif; ?>
                    <?php endif; ?>
                </div>
            </td>
         
        </tr>
        <?php endforeach; ?>

        
        </div>
        
    </table>
    <script>
      

    
        elementsArray = document.querySelectorAll(".new-app");
        elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href="/ctest/receptionist-patient-appointment?cmd=add&id="+elem.id+'&type=consultation';
        });
    });

    elementsArray = document.querySelectorAll(".new-lab");
        elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href="/ctest/receptionist-patient-appointment?cmd=add&id="+elem.id+'&type=labtest';
        });
    });

    

    </script>
    
  
</div>

<script>
        const patients=document.querySelectorAll('.table-row');
        const searchBar=document.getElementById('searchbar');
        const header=document.querySelector('.header-underline');
       
    
        function checker(){
        
        var re=new RegExp("^"+(searchBar.value).toLowerCase())
        patients.forEach((el)=>{
            comp=(el.id).split("&");
          ;
            if(searchBar.value.length==0){
                el.classList.add("none")
                header.classList.add("none");
            }
            else if(re.test(comp[0].toLowerCase()) || re.test(comp[1].toLowerCase())){
                el.classList.remove("none");
                header.classList.remove("none");
            }
            else{
                el.classList.add("none");
                header.classList.add("none");
               
            }
            })
        }
        searchBar.addEventListener('input',checker);



        elementsArray = document.querySelectorAll(".button--class-2");
        elementsArray.forEach(function(elem) {
            elem.addEventListener("click", function() {
                location.href='receptionist-handle-patient?mod=update&id='+elem.id;
            });
        });
</script>