<div class="search-bar-doctor-container">
    <?php
        use app\models\OpenedChanneling;
        $openedChanneling=new OpenedChanneling();
        use app\core\component\Component;


        $component=new Component();
        
        echo $component->searchbar('','search','search-bar--class2','Search by name,specilaity','searchbar');
    ?>
</div>
<section class="doctor-container">
    <?php foreach($doctors as $doctor): ?>
        <div class="doctor-item">
                <div class="doctor-item-identity" >
                    <div class="doctor-item-identity--one">
                        <img src=<?="./media/images/emp-profile-pictures/".$doctor['img']?>>
                        <div class="name-speciality">
                            <h3><?=$doctor['name']?></h3>
                            <h3><?=$doctor['career_speciality']?></h3>
                        </div>
                        <a href=<?="doctor-patient-appointment?spec=appointment&id=".$doctor['emp_ID']?>>Go to appointments</a>
                    </div>
                    <h4><?=$doctor['description']?></h4>

                </div>
            
        </div>
    
    <?php endforeach;   ?>


</section>