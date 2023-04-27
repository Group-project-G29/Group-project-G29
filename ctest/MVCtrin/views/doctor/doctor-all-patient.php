<div class="table-container">

    <table border="0">
        <tr class="row-height header-underline">
            <th>Patient ID</th> <th>Patient Name</th> <th>Age</th> <th>Gender</th> 
        </tr>
        <?php

        use app\core\Application;

            foreach($patients as $key=>$value){
                echo "<tr class='table-row  row-height hover' id=".$value['patient_ID'].">
                        <td>".$value['patient_ID']."</td><td>".$value['name']."</td><td>".$value['age']."</td><td>".$value['gender']."</td>
                    </tr>";
            }
        
        ?>
        
    </table>
  
</div>
<script>
        elementsArray = document.querySelectorAll(".table-row");
        elementsArray.forEach(function(elem) {
            elem.addEventListener("click", function() {
                location.href='channeling?channeling='+elem.id;
            });
        });
</script>