<?php
// var_dump($patient)
?>

<?php
    use app\core\component\Component;
    $component=new Component();
    // var_dump($patient);exit;
?>

<link rel="stylesheet" href="./media/css/nurse-style.css">
<section class="patient-detail-s">
<div class="container">

    <div class="detail-container-left">
        <img src="./media/images/user.png " >  
        <div><?php echo $component->button('edit-details','','Edit Details','button--class-0  width-10', $patient['patient_ID'])?></div>
    </div>
    
    <div class="detail-container-right">
        <div class="data-row">
            <div class="data-row-left">Employee ID</div>
            <div class="data-row-rite">: <?= $patient['patient_ID'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Name</div>
            <div class="data-row-rite">: <?= $patient['name'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">NIC</div>
            <div class="data-row-rite">: <?= $patient['nic'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Gender</div>
            <div class="data-row-rite">: <?= $patient['gender'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Age</div>
            <div class="data-row-rite">: <?= $patient['age'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Contact Number</div>
            <div class="data-row-rite">: <?= $patient['contact'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Email</div>
            <div class="data-row-rite">: <?= $patient['email'] ?></div>
        </div>
        <div class="data-row">
            <div class="data-row-left">Address</div>
            <div class="data-row-rite">: <?= $patient['address'] ?></div>
        </div>
       
    </div>
    
</div>
<section>
<script>
    // const btn=document.getElementById("edit-details");
    const btn=document.querySelector(".button--class-0");
    btn.addEventListener('click',function(){
        location.href="patient-my-detail?mod=update"
    })
</script>


