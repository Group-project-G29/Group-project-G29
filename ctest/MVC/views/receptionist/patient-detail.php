<?php

use app\core\component\Component;

$component = new Component();
?>

<div class="container" style="padding-left:10vw;width:100%;min-height:2vw;overflow:hidden;overflow-y:scroll">
    <!-- <div class="scroll" style="min-height:50vw;overflow:hidden;overflow-y:scroll"> -->
        <div class="detail-container-right" style="width:100%;background-color:white;padding:2vw">
            <div class="">
                <h1 style="text-align:center;line-height:4.2vw"><?= $patients['name'] ?></h1>

            </div>
            <hr>
            <div class="data-row">
                <div class="data-row-left">Patient ID</div>
                <div class="data-row-rite">: <?= $patients['patient_ID'] ?></div>
            </div>

            <div class="data-row">
                <div class="data-row-left">NIC</div>
                <div class="data-row-rite">: <?= $patients['nic'] ?></div>
            </div>
            <div class="data-row">
                <div class="data-row-left">Gender</div>
                <div class="data-row-rite">: <?= $patients['gender'] ?></div>
            </div>
            <div class="data-row">
                <div class="data-row-left">Age</div>
                <div class="data-row-rite">: <?= $patients['age'] ?></div>
            </div>
            <div class="data-row">
                <div class="data-row-left">Contact Number</div>
                <div class="data-row-rite">: <?= $patients['contact'] ?></div>
            </div>
            <div class="data-row">
                <div class="data-row-left">Email</div>
                <div class="data-row-rite">: <?= $patients['email'] ?></div>
            </div>
            <div class="data-row">
                <div class="data-row-left">Address</div>
                <div class="data-row-rite">: <?= $patients['address'] ?></div>
                <br><br>
            </div>

            <?php echo $component->button('add', ' ', 'Update', 'button--class-2 height-2', $patients['patient_ID']) ?>



            <?php foreach ($referrals as $referral) : ?>

                <?php $referral['patient'] ?>
            <?php endforeach ?>

        </div>


        <div class="detail-container-right" style="padding:1.2vw">
            <div class="active-switch-container">
                <h2 style="margin-top:1vw;margin-bottom:0vw">Appointment Payments</h2><b>
                    <hr>
                </b>
                <?php foreach ($appointmentPayment as $appPaymnet) : ?>

                    <label class="active-switch">

                        <?php if ($appPaymnet["payment_status"] === 'done') : ?>
                            <input type="checkbox" id=<?= $appPaymnet["payment_ID"] ?> onclick="is_checked_all_payment(<?= $appPaymnet['payment_ID'] ?>)" checked>
                            <label><b><?= 'Rs.' . $appPaymnet["amount"] ?></b></label><br>
                            <label><?= $appPaymnet["date"] . " " . $appPaymnet["day"] ?></label><br>
                            <label><?= $appPaymnet["speciality"] . " " . "( Dr." . $appPaymnet["ename"] . ")" ?></label><br>
                        <?php else : ?>
                            <input type="checkbox" id=<?= $appPaymnet["payment_ID"] ?> onclick="is_checked_all_payment(<?= $appPaymnet['payment_ID'] ?>)">
                            <label><b><?= 'Rs.' . $appPaymnet["amount"] ?></b></label><br>
                            <label><?= $appPaymnet["date"] . " " . $appPaymnet["day"] ?></label><br>
                            <label><?= $appPaymnet["speciality"] . " " . "( Dr." . $appPaymnet["ename"] . ")" ?></label><br>
                        <?php endif; ?>
                        <span class="slider"></span>
                    </label>
                <?php endforeach; ?>


            </div>
        </div>
        <div class="detail-container-right" style="padding:1vw;width:50%;background-color:#AEE2FF">
            <div class="active-switch-container">

                <h2 style="margin-top:1vw;margin-bottom:0vw">Lab Report Payments</h2><b>
                    <hr>
                </b>
                <?php foreach ($labPayment as $lbPaymnet) : ?>

                    <label class="active-switch">
                        <?php if ($lbPaymnet["payment_status"] === 'done') : ?>
                            <input type="checkbox" id=<?= $lbPaymnet["payment_ID"] ?> onclick="is_checked_all_payment(<?= $lbPaymnet['payment_ID'] ?>)" checked>
                            <label><b><?= 'Rs.' . $lbPaymnet["amount"] ?></b></label><br>
                            <label><?= $lbPaymnet["tname"] ?></label><br>
                            <label><?= $lbPaymnet["date"] ?></label><br>
                            <label><?= $lbPaymnet["speciality"] . " " . "( Dr." . $lbPaymnet["ename"] . ")" ?></label><br>

                        <?php else : ?>
                            <input type="checkbox" id=<?= $lbPaymnet["payment_ID"] ?> onclick="is_checked_all_payment(<?= $lbPaymnet['payment_ID'] ?>)">
                            <label><b><?= 'Rs.' . $lbPaymnet["amount"] ?></b></label><br>
                            <label><?= $lbPaymnet["date"] ?></label><br>
                            <label><?= $lbPaymnet["speciality"] . " " . "( Dr." . $lbPaymnet["ename"] . ")" ?></label><br>
                        <?php endif; ?>
                        <span class="slider"></span>
                    </label>
                <?php endforeach; ?>


            </div>

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