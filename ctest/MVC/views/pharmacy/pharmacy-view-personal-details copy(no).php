<?php
    use app\core\component\Component;
    $component=new Component();
?>
<div class="detail-container">

        <img style="width: 200px; height:auto;" src="./media/images/emp-profile-pictures/<?= $user['img'] ?>" >  
    
        <table>
            <tr class="table-row">
                <td>Employee ID</td>
                <td><?= $user['emp_ID'] ?></td>
            </tr>
            <tr class="table-row">
                <td>Name</td>
                <td><?= $user['name'] ?></td>
            </tr>
            <tr class="table-row">
                <td>NIC</td>
                <td><?= $user['nic'] ?></td>
            </tr>
            <tr class="table-row">
                <td>Gender</td>
                <td><?= $user['gender'] ?></td>
            </tr>
            <tr class="table-row">
                <td>Age</td>
                <td><?= $user['age'] ?></td>
            </tr>
            <tr class="table-row">
                <td>Contact Number</td>
                <td><?= $user['contact'] ?></td>
            </tr>
            <tr class="table-row">
                <td>Email</td>
                <td><?= $user['email'] ?></td>
            </tr>
            <tr class="table-row">
                <td>Address</td>
                <td><?= $user['address'] ?></td>
            </tr>
            <tr class="table-row">
                <td>Role</td>
                <td><?= $user['role'] ?></td>
            </tr>
        </table>

    <div>
        <?php echo $component->button('edit-details','','Edit Details','button--class-0  width-10', $user['emp_ID'])?>
    </div>
    
</div>

<script>
    // const btn=document.getElementById("edit-details");
    const btn=document.querySelector(".button--class-0");
    btn.addEventListener('click',function(){
        location.href="pharmacy-update-personal-details?mod=update&id="+ btn.id; //get
    })
</script>


