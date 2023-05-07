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
<div class="patient-detail"style=" padding:1vw;height:10vw" > 
    <div class="" style="margin-left: 5vw;">
        <h1 class="fs-200 fc-color--dark">Patient Detail</h1>

        <?php $age = "18" ?>
        <h5><?php if ($patient[0]['age'] < $age) {
                echo " Pediatric *";
            } else {
                echo "Adult *";
            } ?></h5>

        <h4><b> Name  : </b><?= $patient[0]['name'] ?></h4>
        <h4><b>NIC : </b><?= $patient[0]['nic'] ?></h4>
        <h4><b>Age : </b><?= $patient[0]['age'] ?></h4>
    </div>


</div>
<!-- </div> -->
<div class="search-bar-container" style="margin-left:25vw">
    <?php echo $component->searchbar('','search','search-bar--class2','Search by name,specilaity','searchbar');?>
</div>
  
<div class="table-container">

    <table border="0">
        <tr class="row-height header-underline none " id="header">
        <th>Doctor</th> <th>Specialtity</th> <th>Time</th><th>Start Day</th><th>Fee</th><th></th>
            
        </tr>
        <div class="patient-container">
            

        <?php   foreach($channelings as $key=>$channeling): ?>

        <?php   foreach($channeling as $key=>$Ochanneling): ?>
         
        <tr class="table-row none " id=<?="'".$Ochanneling['name']."&".$Ochanneling['speciality']."'"?>>
        <?php $time="12.00"?>

            <td><?=$Ochanneling['name']?></td>
            <td><?=$Ochanneling['speciality']?></td>  
            <td><?php if ($Ochanneling['time']<$time){
          echo $Ochanneling['time']." A.M";}
          else{echo $Ochanneling['time']." P.M";}?> </td>
            <td><?=$Ochanneling['start_date'].' ('.$Ochanneling['day'].')'?></td>
            <td><?=$Ochanneling['fee']?></td>
            <td>
                <div>
                    <?php if($openedChanneling->isPatientIn($patient[0]['patient_ID'],$Ochanneling['opened_channeling_ID'])):?>
                    <div style="color:green"><?php echo "Already have an appointment" ?></div>
                    <?php else: ?>
                        <?php if($appoinment->labReportEligibility($patient[0]['patient_ID'],$Ochanneling['nic'],$Ochanneling['opened_channeling_ID'])):?>
                            <?php echo $component->button('update',' ','Add Lab Report Appointment','button--class-0 new-lab',$Ochanneling['opened_channeling_ID']) ?>
                        <?php else:?>
                            <?php echo $component->button('update',' ','Add Ordinary Appointment','button--class-0 new-app',$Ochanneling['opened_channeling_ID']) ?>
                            <?php endif; ?>
                    <?php endif; ?>
                </div>
            </td>
         
        </tr>
        <?php endforeach; ?>
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