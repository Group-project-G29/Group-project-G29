<div class="upper-container">
    <div class="search-bar-container">
    <?php

use app\core\component\Component;

        $component=new Component();
        
        echo $component->searchbar('','search','search-bar--class2','Search by name','searchbar');
    ?>
    
    </div>
    <div class="search-bar-btn">
    <?= $component->button('btn','','Add New Patient','button--class-0',"new"); ?>
    </div>
</div>
<div class="table-container">

    <table border="0">
        <tr class="row-height header-underline none" id="header">
        <th>Name</th> <th>NIC</th> <th>Contact Number</th><th></th><th></th><th></th>
            
        </tr>
        <div class="patient-container">
        <?php foreach($patients as $key=>$patient): ?>
        <tr class="table-row none" id=<?='"'.$patient['name']."&".$patient['patient_ID'].'"'?>>
            
            <td><?=$patient['name']?></td>
            <td><?=$patient['nic']?></td>  
            <td><?=$patient['contact']?></td>
            <td>
                <div>
                    <?php echo $component->button('update',' ','Update','button--class-2',$patient['patient_ID']) ?>
                </div>
            </td>
            <td>
                <div class="button holder">
                    <?php echo $component->button('update',' ','Add New Appointment','button--class-0 new-app',$patient['patient_ID']) ?>
                </div>

            </td>
            <td>
                <a href=<?="/ctest/receptionist-patient-information?mod=view&id=".$patient['patient_ID']?>>View All Appointments</a>
            </td>
        </tr>
        <?php endforeach; ?>

        
        </div>
        
    </table>
    <script>
        let btn=document.getElementById('new');
        btn.addEventListener('click',()=>{
            location.href="/ctest/receptionist-handle-patient?mod=add";
        })

        elementsArray = document.querySelectorAll(".new-app");
        elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href="/ctest/receptionist-patient-appointment?mod=view&id="+elem.id;
        });
    });
        
    

    </script>
    
  
</div>

<script>
        const patients=document.querySelectorAll('.table-row');
        const searchBar=document.getElementById('searchbar');
        const header=document.querySelector('.header-underline');
        console.log(header);
    
        function checker(){
        
        var re=new RegExp("^"+searchBar.value)
        
        patients.forEach((el)=>{
            comp=(el.id).split("&");
            if(searchBar.value.length==0){
                el.classList.add("none")
                header.classList.add("none");
            }
            else if(re.test(el.id) ||re.test(comp[1])){
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
                console.log(elem.id);
            });
        });
</script>