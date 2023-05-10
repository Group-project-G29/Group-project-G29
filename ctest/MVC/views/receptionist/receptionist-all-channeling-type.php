<?php

use app\core\component\Component;

$component = new Component();

// var_dump($channelings);
// exit;
?>

<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by name', 'searchbar'); ?>
    </div>
   


</div>
<div class="main-card ">
<div class="">
        <h1 style="text-align:center"><?= $channelingSp['career_speciality'] ?> Specialists</h1>
        
    </div>
    <?php foreach ($channelings as $key => $channeling) : ?>
        
        <div class="card-0" style="width:30vh;height:35vh" id=<?= "'".$channeling['emp_ID']."-".$channeling['name']."'" ?>>
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

<script>

 const doctors = document.querySelectorAll('.card-0');
    const searchBar = document.getElementById('searchbar');
    

    function checker() {
      var re = new RegExp("^" + (searchBar.value).toLowerCase())
      
      doctors.forEach((el) => {
        comp = (el.id).split("-");
        console.log(comp[1]);
        console.log(re.test(comp[1].toLowerCase()));

            if ( re.test(comp[1].toLowerCase())) {
                el.classList.remove("none");
            } else {
                el.classList.add("none");

            }
            if(searchBar.value.length==0){
              doctors.forEach((el)=>{
                    el.classList.remove("none");
                }) 
            }
        })
    }
    searchBar.addEventListener('input', checker);
    
    elementsArray = document.querySelectorAll(".card-0");
    elementsArray.forEach(function(elem) {
      comp = (elem.id).split("-");
        elem.addEventListener("click", function() {
            location.href = 'receptionist-channeling-more?id=' + comp[0]; //pass the variable value
        });
    });
</script>