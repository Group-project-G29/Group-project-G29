<?php

use app\core\component\Component;
use app\models\Employee;
$component = new Component();
?>
<div class="header-container">
    <h2>Set Patient Appoinment</h2>

    <div class="semi-header-container">
        <h5>Name = <?= $channelingset['name'] ?></h5>
        <?php $age="18"?>

        <h5><?php if ($channelingset['age']< $age){
        echo "Categoty = Pediatric";}
        else{echo "Categoty = Adult";}?></h5>


        <hr style="width: 95%;">
        <div class="search-bar-container">
            <?php echo $component->searchbar('', 'search', 'search-bar--class', 'Search by name, specilaity', 'searchbar'); ?>
        </div>

    </div>
</div>




<br>



<!-- <script>
    elementsArray = document.querySelectorAll(".button-1");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-channeling-set-appointment?id=' + elem.id;
        });
    });
</script> -->