<?php

use app\core\component\Component;
use app\models\Employee;

$component = new Component();
?>
<div class="header-container">
    <h2>Patient Payment</h2>

    <div class="semi-header-container">
        <div class="table-container">
            <table border="">

                <tr class="">
                    <th>Payment Detail</th>
                    <th>Amount</th>
                    <th>Date & Time</th>

                </tr>

            </table>
        </div>

    </div>


    <div class="semi-footer-container">
        <h5>Total Payment :</h5>
        <h5>Selected Payment :</h5>

        <div class="button-1" >
    <?php echo $component->button('Set Appoinment', '', 'Payment', 'button--class-0  width-10', 'edit-details'); ?>
    
</div>

    </div>


</div>




<br>



<script>
    elementsArray = document.querySelectorAll(".button-1");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-channeling-set-appointment?id=' + elem.id;
        });
    });
</script>