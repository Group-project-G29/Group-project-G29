<?php

use app\core\component\Component;

$component = new Component();
// var_dump($user);
// exit;
?>
<!-- <div class="detail-container" style="margin-left: 35%;"> -->


<section class="profile">
    <!-- <div class="img" style="margin-left:13%;">
    <img style="width: 50%; height:auto;margin-left:5vw" src="./media/images/emp-profile-pictures/<?= $user['img'] ?>">

    </div> -->
    <div class="card-wrapper">
        <div class="card-1">
            <img src="./media/images/patient/Hospital-2.jpg" class="card-img">
            <img src="./media/images/emp-profile-pictures/<?= $user['img'] ?>" class="pro-img">
            <div class="content">
                <p>
            <h1 ><b><?= $user['name'] ?></b></h1>
            <h4 ><b>NIC : </b><?= $user['nic'] ?></h4>
            <h4 ><b>Email : </b><?= $user['email'] ?></h4>
            <h4 ><b>Address : </b><?= $user['address'] ?></h4>
            <h4 ><b>Contact : </b><?= $user['contact'] ?></h4>
                </p> 
            
            </div>
           
            <div class="button" id=<?= $user['emp_ID'] ?>>
                <?php echo $component->button('edit-details', '', 'Edit Details', 'button--class-4  width-10', 'edit-details'); ?>
            </div>
        </div>


    </div>

</section>


</div>

<script>
    elementsArray = document.querySelectorAll(".button");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'delivery-update-personal-details?mod=update&id=' + elem.id; //pass the variable value
        });
    });
</script>