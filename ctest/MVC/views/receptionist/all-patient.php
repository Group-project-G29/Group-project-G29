<div class="upper-container">
    <div class="search-bar-container">
        <?php

        use app\core\component\Component;

        $component = new Component();

        echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by name', 'searchbar');
        ?>

    </div>
    <div class="search-bar-btn" style="width:5vw;color:brown">
        <?= $component->button('btn', '', 'Add New Patient', 'button--class-0 exbutton', "new"); ?>
    </div>
</div>


<div class="table-container">

    <table border="0">
        <tr class="row-height header-underline " id="header">
        <th>Name</th> <th>NIC</th> <th>Contact Number</th><th></th><th></th><th></th>
            
        </tr>
        <div class="patient-container" >
        <?php foreach ($patients as $key => $patient) : ?>
        <tr class="table-row" id=<?= '"' . $patient['name'] . "&" . $patient['patient_ID'] . '"' ?>>
            
            <td><?= $patient['name'] ?></td>
            <td><?= $patient['nic'] ?></td>  
            <td><?= $patient['contact'] ?></td>
            
            <td>
                <div class="button holder">
                    <?php echo $component->button('update', ' ', 'Add New Appointment', 'button--class-00 new-app', $patient['patient_ID']) ?>
                </div>

            </td>
            <td>
                 <?php echo $component->button('view', ' ', 'View All Appointments', 'button--class-00 width all', $patient['patient_ID']) ?>

            </td>
            <td>
                 <?php echo $component->button('view', ' ', 'More', 'button--class-5 more ', $patient['patient_ID']) ?>

            </td>
        </tr>
        <?php endforeach; ?>

        
        </div>
        
    </table>
<script>
    let btn = document.getElementById('new');
    btn.addEventListener('click', () => {
        location.href = "/ctest/receptionist-handle-patient?mod=add";
    })

    elementsArray = document.querySelectorAll(".new-app");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = "/ctest/receptionist-patient-appointment?mod=view&id=" + elem.id;
        });
    });
</script>


</div>

<script>
     const patients = document.querySelectorAll('.table-row');
    // const patients = document.querySelectorAll('.card');
    const searchBar = document.getElementById('searchbar');
    const header = document.querySelector('.header-underline');
    console.log(header);

    function checker() {

        var re = new RegExp("[a-zA-Z]*" + (searchBar.value).toLowerCase()+"[a-zA-Z]*")

        patients.forEach((el) => {
            comp = (el.id).split("&");
            
            if (re.test((el.id).toLowerCase()) || re.test(comp[1].toLowerCase())) {
                el.classList.remove("none");
                header.classList.remove("none");
            } else {
                el.classList.add("none");
                header.classList.add("none");

            }
            if(searchBar.value.length==0){
              doctors.forEach((el)=>{
                    el.classList.remove("none");
                }) 
            }
        })
    }
    searchBar.addEventListener('input', checker);



    elementsArray = document.querySelectorAll(".button--class-2");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-handle-patient?mod=update&id=' + elem.id;
            console.log(elem.id);
        });
    });
</script>

<script>
    elementsArray = document.querySelectorAll(".all");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'receptionist-patient-information?mod=view&id=' + elem.id;
            console.log(elem.id);
        });
    });

    elementsArray = document.querySelectorAll(".more");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'patient-detail?mod=view&id='+elem.id;  //pass the variable value
    });
  });
</script>