<?php
    use app\core\component\Component;
    $component=new Component();
    $popup=$component->popup("Are you sure you want to delete the channeling session","popup-value","popup--class-1","yes");
    echo $popup;
?>

<div class="filter-holder">
    <?php 
        echo $component->filtersortby('','',[],['Speciality'=>'speciality','Doctor'=>'Doctor']);
    ?>
</div>
<div class="table-container">
<?php if($channelings):?>
<table border="0">
    <tr>
        <th>Clinic</th><th>Doctor</th><th>Date</th><th>Time</th>
    </tr>
    
        <?php foreach($channelings as $key=>$channeling): ?>
        <tr class="table-row">
            
            <td><?=$channeling['speciality']?></td>
            <td><?=$channeling['name']?></td>  
            <td><?=$channeling['channeling_date']?></td>
            <td><?=$channeling['time']?></td>  
            <td>
                <div>
                    <?php echo $component->button('delete',' ','Cancel Appointment','button--class-3',$channeling['appointment_ID']) ?>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <div class="empty-container">
            Empty
        </div>
    <?php endif; ?>
</div>

<script>
    elementsArray = document.querySelectorAll(".button--class-3");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            url='handle-appointment?cmd=delete&id='+elem.id;
            div.style.display="flex";
            bg.classList.add("background");
        });
    });
    yes.addEventListener("click",()=>{
        location.href=url;
        })
</script>
<script>
    const labels = Utils.months({count: 7});
const data = {
  labels: labels,
  datasets: [{
    label: 'My First Dataset',
    data: [65, 59, 80, 81, 56, 55, 40],
    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(255, 205, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(201, 203, 207, 0.2)'
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
      'rgb(54, 162, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)'
    ],
    borderWidth: 1
  }]
};
</script>