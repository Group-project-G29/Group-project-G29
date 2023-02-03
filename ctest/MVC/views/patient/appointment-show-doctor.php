<section class="doctor-container">
    <?php foreach($doctors as $doctor): ?>
        <div class="doctor-item">
                <div class="doctor-item-identity" >
                    <div class="doctor-item-identity--one">
                        <img src=<?="./media/images/emp-profile-pictures/".$doctor['img']?>>
                        <div class="name-speciality">
                            <h3><?=$doctor['name']?></h3>
                            <h3><?=$doctor['speciality']?></h3>
                        </div>
                        <a href=<?="doctor-patient-appointment?spec=appointment&id=".$doctor['emp_ID']?>>Go to appointments</a>
                    </div>
                    <h3><?=$doctor['description']?></h3>

                </div>
            
        </div>
    
    <?php endforeach;   ?>


</section>