<?php

use app\core\component\Component;

$component = new Component();
// var_dump($appointmentPayment);
// var_dump($referrals);
// exit;
// payments[array] = [ payment_ID=>[select * where payment_ID = payment_ID] ] ]
?>

<div class="container"style="padding-left:12vw">


    <!-- <img src="./media/images/emp-profile-pictures/" >   -->

    <div class="detail-container-right" style="width:60%;background-color:white">
        <div class="">
            <h1 style="text-align:center"><?= $patient['name'] ?></h1>

        </div><hr>
        <div class="data-row">
            <div class="data-row-left">Patient ID</div>
            <div class="data-row-rite">: <?= $patient['patient_ID'] ?></div>
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
            <br><br>
        </div>
        
        <?php echo $component->button('add', ' ', 'Update', 'button--class-2 height-2', $patient['patient_ID']) ?>
    
    
        
        <?php foreach ($referrals as $referral) : ?>
            
        <?php $referral['patient']?>
        <?php endforeach?>
        
    </div>

    <div class="detail-container-right" style="padding:2vw">
        <div class="active-switch-container"style="">
            <h2 style="margin-top:1vw;margin-bottom:0vw">Appointment Payments</h2>
            <?php foreach ($appointmentPayment as $appPaymnet) : ?>

                <label class="active-switch">

                    <?php if ($appPaymnet["payment_status"] === 'done') : ?>
                        <input type="checkbox" id=<?= $appPaymnet["payment_ID"] ?> onclick="is_checked_all_payment(<?= $appPaymnet['payment_ID'] ?>)" checked>
                        <label><b>Payment ID :</b><?= $appPaymnet["payment_ID"]?></label><br>
                        <label><?='Rs.'.$appPaymnet["amount"]?></label><br>
                        <label><?=$appPaymnet["date"]." ".$appPaymnet["time"]?></label><br>
                        <label><?=$appPaymnet["speciality"]." "."( Dr.".$appPaymnet["ename"].")" ?></label><br>
                    <?php else : ?>
                        <input type="checkbox" id=<?= $appPaymnet["payment_ID"] ?> onclick="is_checked_all_payment(<?= $appPaymnet['payment_ID'] ?>)">
                        <label><b>Payment ID :</b><?= $appPaymnet["payment_ID"]?></label><br>
                        <label><?='Rs.'.$appPaymnet["amount"]?></label><br>
                        <label><?=$appPaymnet["date"]." ".$appPaymnet["time"]?></label><br>
                        <label><?=$appPaymnet["speciality"]." "."( Dr.".$appPaymnet["ename"].")" ?></label><br>                   
                         <?php endif; ?>
                    <span class="slider"></span>
                </label>
            <?php endforeach; ?>
            

            <hr>

            <h2 style="margin-top:1vw;margin-bottom:0vw">Lab Report Payments</h2>
            <?php foreach ($labPayment as $lbPaymnet) : ?> 

                    <label class="active-switch" >
                        <?php if ($lbPaymnet["payment_status"] === 'done') : ?>
                            <input type="checkbox" id=<?= $lbPaymnet["payment_ID"] ?> onclick="is_checked_all_payment(<?= $lbPaymnet['payment_ID'] ?>)" checked>
                            <label><b>Payment ID :</b><?= $lbPaymnet["payment_ID"]?></label><br>
                            <label><?='Rs.'.$lbPaymnet["amount"]?></label><br>
                            <label><?=$lbPaymnet["date"]."   Time:".$lbPaymnet["time"]?></label><br>
                            <label><?=$lbPaymnet["speciality"]." "."( Dr.".$lbPaymnet["ename"].")" ?></label><br>

                        <?php else : ?>
                            <input type="checkbox" id=<?= $lbPaymnet["payment_ID"] ?> onclick="is_checked_all_payment(<?= $lbPaymnet['payment_ID'] ?>)">
                            <label><b>Payment ID :</b><?= $lbPaymnet["payment_ID"]?></label><br>
                            <label><?='Rs.'.$lbPaymnet["amount"]?></label><br>
                            <label><?=$lbPaymnet["date"]." ".$lbPaymnet["time"]?></label><br>
                            <label><?=$lbPaymnet["speciality"]." "."( Dr.".$lbPaymnet["ename"].")" ?></label><br>
                        <?php endif; ?>
                    <span class="slider"></span>
                    </label>
            <?php endforeach; ?>
            

        </div>

    </div>

</div>


<script>
    elementsArray = document.querySelectorAll(".button--class-2");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-handle-patient?mod=update&id=' + elem.id;
            console.log(elem.id);
        });
    });

    function is_checked_all_payment($payment_ID) {
        console.log("dis");
        if (document.getElementById($payment_ID).checked) {
            // location.href="/ctest/login";
            const online_path = "/ctest/receptionist-all-payment-done?pay_ID=" + $payment_ID;
            fetch(online_path, {
                    method: "GET"
                }).then((res) => res.text())
                .then((data) => {
                    console.log(data);
                })
            console.log('checked');
            // alert(online_path);
        } else {
            const online_path = "/ctest/receptionist-all-payment-notdone?pay_ID=" + $payment_ID;
            fetch(online_path, {
                    method: "GET"
                }).then((res) => res.text())
                .then((data) => {
                    console.log(data);
                })
            console.log('Checkbox is not checked!');
            // alert(online_path);

        }
    }

    let btn = document.getElementById('new');
    btn.addEventListener('click', () => {
        location.href = "/ctest/receptionist-handle-patient?mod=add";
    })

    const patients = document.querySelectorAll('.table-row');
    // const patients = document.querySelectorAll('.card');
    const searchBar = document.getElementById('searchbar');
    const header = document.querySelector('.header-underline');
    console.log(header);

    function checker() {

        var re = new RegExp("[a-zA-Z]*" + (searchBar.value).toLowerCase() + "[a-zA-Z]*")

        patients.forEach((el) => {
            comp = (el.id).split("&");
            if (searchBar.value.length == 0) {
                el.classList.add("none")
                header.classList.add("none");
            } else if (re.test((el.id).toLowerCase()) || re.test(comp[1].toLowerCase())) {
                el.classList.remove("none");
                header.classList.remove("none");
            } else {
                el.classList.add("none");
                header.classList.add("none");

            }
        })
    }
    searchBar.addEventListener('input', checker);



    
</script>