<?php

use app\core\component\Component;

$component = new Component();

?>


<!-- <section class="profile">
    
    <div class="card-wrapper">
        <div class="card-1">
            <img src="./media/images/patient/Hospital-2.jpg" class="card-img">
            <img src="./media/images/emp-profile-pictures/<?= $userinfo['img'] ?>" class="pro-img">
            <div class="content">
                <p>
            <h1 ><b><?= $userinfo['name'] ?></b></h1>
            <h4 ><b>NIC : </b><?= $userinfo['nic'] ?></h4>
            <h4 ><b>Email : </b><?= $userinfo['email'] ?></h4>
            <h4 ><b>Address : </b><?= $userinfo['address'] ?></h4>
            <h4 ><b>Contact : </b><?= $userinfo['contact'] ?></h4>
                </p> 
            
            </div>
           
            <div class="button" id=<?= $userinfo['email'] ?>>
                <?php echo $component->button('edit-details', '', 'Edit Details', 'button--class-4  width-10', 'edit-details'); ?>
            </div>
        </div>


    </div>

</section> -->


<div class="container">

    <div class="detail-container-left">
        <img src="./media/images/emp-profile-pictures/<?= $userinfo['img'] ?>" >  
        <div class="button" id=<?= $userinfo['email'] ?>>
                <?php echo $component->button('edit-details', '', 'Edit Details', 'button--class-4  width-10', 'edit-details'); ?>
            </div>    </div>
    
    <div class="detail-container-right">
        <div class="data-row">
            <div class="data-row-left">Employee ID</div>
            <div class="data-row-rite">: <?= $userinfo['emp_ID'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Name</div>
            <div class="data-row-rite">: <?= $userinfo['name'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">NIC</div>
            <div class="data-row-rite">: <?= $userinfo['nic'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Gender</div>
            <div class="data-row-rite">: <?= $userinfo['gender'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Age</div>
            <div class="data-row-rite">: <?= $userinfo['age'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Contact Number</div>
            <div class="data-row-rite">: <?= $userinfo['contact'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Email</div>
            <div class="data-row-rite">: <?= $userinfo['email'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Address</div>
            <div class="data-row-rite">: <?= $userinfo['address'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Role</div>
            <div class="data-row-rite">: <?= $userinfo['role'] ?></div>
        </div>
    </div>
    
</div>

<script>
    // const btn=document.getElementById("edit-details");
    const btn=document.querySelector(".button--class-0");
    btn.addEventListener('click',function(){
        location.href="receptionist-personal-detail-update?mod=update&id="+ btn.id; //get
    })
</script>



<script>
    elementsArray = document.querySelectorAll(".button");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-personal-detail-update?mod=update&id=' + elem.id; //pass the variable value
        });
    });
</script>
