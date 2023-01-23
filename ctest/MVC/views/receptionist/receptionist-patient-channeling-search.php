
<div class="patient-detail">

    <h3>Patient Name : <?=$patient[0]['name']?></h3>
    <h3>NIC :<?=$patient[0]['nic'] ?></h3>
    <h3>Age :<?$patient[0]['age']?></h3>
    
</div>
<div class="search-bar-container">
    <?php
        use app\models\OpenedChanneling;
        use app\core\component\Component;
        
        $openedChanneling=new OpenedChanneling();
        $component=new Component();
        
        echo $component->searchbar('','search','search-bar--class2','Search by name,specilaity','searchbar');
    ?>
</div>
  
<div class="table-container">

    <table border="0">
        <tr class="row-height header-underline none" id="header">
        <th>Doctor</th> <th>Specialtity</th> <th>Time</th><th>Day</th><th>Fee</th><th></th>
            
        </tr>
        <div class="patient-container">

        <?php   foreach($channelings as $key=>$channeling): ?>
         
        <tr class="table-row none" id=<?=$channeling['name']."&".$channeling['speciality']?>>
            
            <td><?=$channeling['name']?></td>
            <td><?=$channeling['speciality']?></td>  
            <td><?=$channeling['time']?></td>
            <td><?=$channeling['day']?></td>
            <td><?=$channeling['fee']?></td>
            <td>
                <div>
                    <?php if($openedChanneling->isPatientIn($patient[0]['patient_ID'],$channeling['opened_channeling_ID'])):?>
                    <?php echo "Already have an appointment" ?>
                    <?php else: ?>
                    <?php echo $component->button('update',' ','Add New Appointment','button--class-0 new-app',$channeling['opened_channeling_ID']) ?>
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
            location.href="/ctest/receptionist-patient-appointment?cmd=add&id="+elem.id;
        });
    });
    

    </script>
    
  
</div>

<script>
        const patients=document.querySelectorAll('.table-row');
        const searchBar=document.getElementById('searchbar');
        const header=document.querySelector('.header-underline');
       
    
        function checker(){
        
        var re=new RegExp("^"+searchBar.value)
        patients.forEach((el)=>{
            comp=(el.id).split("&");
          ;
            if(searchBar.value.length==0){
                el.classList.add("none")
                header.classList.add("none");
            }
            else if(re.test(comp[0]) || re.test(comp[1])){
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