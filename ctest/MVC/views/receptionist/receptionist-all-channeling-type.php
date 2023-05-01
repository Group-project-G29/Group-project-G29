<?php

use app\core\component\Component;

$component = new Component();

// var_dump($channelings);
// exit;
?>

<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by name,speciality', 'searchbar'); ?>
    </div>
   


</div>
<div class="main-card ">
<div class="">
        <h1 style="text-align:center"><?= $channelingSp['career_speciality'] ?> Specialists</h1>
        
    </div>
    <?php foreach ($channelings as $key => $channeling) : ?>
        
        <div class="card-0" style="width:30vh;height:35vh" id=<?= $channeling['emp_ID'] ?>>
            <div class="image" >
                <img src="./media/images/emp-profile-pictures/<?= $channeling['img'] ?> ">
            </div>
            <div class="card-header-0" style="padding-bottom: 5vh;">
                <h3 style="">Dr.<?= $channeling['name'] ?></h3>
                <h5><?= $channeling['description'] ?></h5>
                <h5>Age: <?= $channeling['age'] ?></h5>
                
            </div>

        </div>

    <?php endforeach; ?>

</div>

<!-- <div class="table-container">


  <table border="0">

    <tr class="row-height header-underline">
      <th>Doctor</th>
      <th>Type</th>

    </tr>


    <?php foreach ($channelings as $key => $channeling) : ?>
      
      
      <tr class='table-row  row-height hover' id=<?= $channeling['emp_ID'] ?>>
        <td><?= $channeling['name'] ?></td>
        <td><?= $channeling['speciality'] ?> </td>
      </tr>
    <?php endforeach; ?>




  </table>
</div> -->
<script>
    elementsArray = document.querySelectorAll(".card-0");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-channeling-more?id=' + elem.id; //pass the variable value
        });
    });
</script>