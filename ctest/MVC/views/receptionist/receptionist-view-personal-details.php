<?php

use app\core\component\Component;

$component = new Component();
// var_dump($userinfo);
// exit;
?>
<!-- <div class="detail-container" style="margin-left: 35%;"> -->



<div class="card">
    <div class="img" style="margin-left:13%;">
    <img style="width: 50%; height:auto;margin-left:5vw" src="./media/images/emp-profile-pictures/<?= $userinfo['img'] ?>">

    </div>
    <div class="container">
    <table>
        <!-- <tr class="table-row">
                <td>Employee ID</td>
                <td><?= $userinfo['emp_ID'] ?></td>
            </tr> -->
            <tr class="table-row">
                <td>Name</td>
                <td>:</td>
                <td><?= $userinfo['name'] ?></td>
            </tr>
            <tr class="table-row">
                <td>NIC</td>
                <td>:</td>

                <td><?= $userinfo['nic'] ?></td>
            </tr>
            <!-- <tr class="table-row">
                <td>Gender</td>
                <td><?= $userinfo['gender'] ?></td>
            </tr> -->
            <!-- <tr class="table-row">
                <td>Age</td>
                <td><?= $userinfo['age'] ?></td>
            </tr> -->
            <tr class="table-row">
                <td>Contact</td>
                <td>:</td>

                <td><?= $userinfo['contact'] ?></td>
            </tr>
            <tr class="table-row">
                <td>Email</td>
                <td>:</td>

                <td><?= $userinfo['email'] ?></td>
            </tr>
            <tr class="table-row">
                <td>Address</td>
                <td>:</td>

                <td><?= $userinfo['address'] ?></td>
            </tr>
            <!-- <tr class="table-row">
                <td>Role</td>
                <td><?= $userinfo['role'] ?></td>
            </tr> -->
        </table>
    </div>
    <div class="button" style="margin-left: 35%;" id=<?= $userinfo['email'] ?>>
        <?php echo $component->button('edit-details', '', 'Edit Details', 'button--class-0  width-10', 'edit-details'); ?>
    </div>
</div>




</div>

<script>
    elementsArray = document.querySelectorAll(".button");
    console.log(elementsArray);
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-personal-detail-update?mod=update&id=' + elem.id; //pass the variable value
        });
    });
</script>