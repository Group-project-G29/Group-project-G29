<div class="search-bar-doctor-container">
    <?php

use app\core\Application;
use app\models\OpenedChanneling;
        $openedChanneling=new OpenedChanneling();
        use app\core\component\Component;
        use app\models\Patient;

        $component=new Component();
        $patientModel=new Patient();
        
        echo $component->searchbar('','search','search-bar--class2','Search by name,specilaity','searchbar');
        echo $component->filtersortby('','',['Cardiologist'=>'cardiologist','Gastrologist'=>'gastrologist'],['Doctor Name Ascending'=>'doctor-name-asc']);
    ?>
</div>
<section class="doctor-container">
    <?php foreach($doctors as $doctor): ?>
        <?php if($patientModel->isDoctor(Application::$app->session->get('user'),$doctor['nic'])):?>
        <div class="doctor-item" id=<?="'".$doctor['name']."-".$doctor['career_speciality']."'" ?>>
        <?php else:?>
        <div class="doctor-item none" id=<?="'".$doctor['name']."-".$doctor['career_speciality']."'" ?>>
        <?php endif;?>
                    <img src=<?="./media/images/emp-profile-pictures/".$doctor['img']?>>
                    <div class="doctor-item-identity--one">
                        <div class="name-speciality">
                            <h3><?=$doctor['name']?></h3>
                            <h3><?=$doctor['career_speciality']?></h3>
                            <h4><?=$doctor['description']?></h4>
                        </div>
                        <div class="illusion-button">
                             <a href=<?="doctor-patient-appointment?spec=appointment&id=".$doctor['emp_ID']?>>Go to appointments</a>
                         </div>
                    </div>

            
        </div>
    
    <?php endforeach;   ?>


</section>
<script>
        const patients=document.querySelectorAll('.doctor-item');
        const searchBar=document.getElementById('searchbar');
       
    
        function checker(){
        
        var re=new RegExp("^"+searchBar.value)
        patients.forEach((el)=>{
            comp=""+el.id;
            console.log(el.id);
            comp=comp.split("-");
          ;
            if(searchBar.value.length==0){
                el.classList.add("none")
            }
            else if(re.test(comp[0]) || re.test(comp[1])){
                el.classList.remove("none");
            }
            else{
                el.classList.add("none");
               
            }
            })
            if(searchBar.value.length==0){
                patients.forEach((el)=>{
                    el.classList.add("none");
                }) 
            }
        }
        searchBar.addEventListener('input',checker);
</script>