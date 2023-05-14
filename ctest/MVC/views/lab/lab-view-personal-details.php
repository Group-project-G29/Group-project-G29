<?php

use app\core\component\Component;

$component = new Component();
// var_dump($userinfo);
// exit;
?>

<div class="container">

    <div class="detail-container-left">
        <img src="./media/images/emp-profile-pictures/<?= $user['img'] ?>" >  
        <div class="button" id=<?= $user['emp_ID'] ?>>
                <?php echo $component->button('edit-details', '', 'Edit Details', 'button--class-4  width-10', 'edit-details'); ?>
            </div>    </div>
    
    <div class="detail-container-right">
        <div class="data-row">
            <div class="data-row-left">Employee ID</div>
            <div class="data-row-rite">: <?= $user['emp_ID'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Name</div>
            <div class="data-row-rite">: <?= $user['name'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">NIC</div>
            <div class="data-row-rite">: <?= $user['nic'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Gender</div>
            <div class="data-row-rite">: <?= $user['gender'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Age</div>
            <div class="data-row-rite">: <?= $user['age'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Contact Number</div>
            <div class="data-row-rite">: <?= $user['contact'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Email</div>
            <div class="data-row-rite">: <?= $user['email'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Address</div>
            <div class="data-row-rite">: <?= $user['address'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Role</div>
            <div class="data-row-rite">: <?= $user['role'] ?></div>
        </div>
    </div>
    
</div>

<script>
    // const btn=document.getElementById("edit-details");
    const btn=document.querySelector(".button--class-0");
    btn.addEventListener('click',function(){
        location.href="lab-personal-detail-update?mod=update&id="+ btn.id; //get
    })
</script>





<script>
  elementsArray = document.querySelectorAll(".button");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-personal-detail-update?mod=update&id='+elem.id; //pass the variable value
    });
  });
  
</script>
