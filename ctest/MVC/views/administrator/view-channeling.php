<?php
    use app\core\component\Component;
    $component=new Component();
    $popup=$component->popup("Are you sure you want to delete the channeling session","popup-value","popup--class-1","yes");
    echo $popup;
?>
<div class="upper-container">
    <div class="search-bar-container ">
        <?= $component->searchbar('','channeling','search-bar--class1','Search by speciality,Doctor name',"searchbar") ?>
    </div>
    <div class="set-margin-30">
        <?=$component->button('add','','Add New Channeling','button--class-0','add-new')?>
    </div>
    </div>   
    <div class="table-container">
 
<?php if($channelings):?>
<table border="0">
    <tr>
        <th>Channeling</th><th>Doctor</th><th>Day</th><th>Time</th>
    </tr>
        <?php foreach($channelings as $key=>$channeling): ?>
<<<<<<< HEAD
        <tr class="table-row" id=<?="'".$channeling['name']."-".$channeling['speciality']."-".$channeling['day']."-".$channeling['channeling_ID']."'" ?>>
=======
        <tr class="table-row" id=<?="'".$channeling['doctor']."-".$channeling['speciality']."-".$channeling['day']."'" ?>>
            
>>>>>>> 20000758
            <td><?=$channeling['speciality']?></td>
            <td><?=$channeling['name']?></td>  
            <td><?=$channeling['day']?></td>
            <td><?=$channeling['time']?></td>  
            
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
    const btnadd=document.getElementById('add-new');
    btnadd.addEventListener('click',()=>{
        location.href="schedule-channeling?mod=add";
    })
    
    elementsArray = document.querySelectorAll(".button--class-3");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            url='handle-appointment?cmd=delete&id='+elem.id;
            div.style.display="flex";
            bg.classList.add("background");
        });
    });
  
    const patients=document.querySelectorAll('.table-row');
        const searchBar=document.getElementById('searchbar');
       
    
        function checker(){
        
        var re=new RegExp("^"+searchBar.value)
        patients.forEach((el)=>{
            comp=""+el.id;
            console.log(el.id);
            comp=comp.split("-");
          ;
            if(searchBar.value.length==0){
                // el.classList.add("none")
            }
            else if(re.test(comp[0]) || re.test(comp[1]) || re.test(comp[2])){
                el.classList.remove("none");
            }
            else{
                el.classList.add("none");
               
            }
            })
            if(searchBar.value.length==0){
                patients.forEach((el)=>{
                    el.classList.remove("none");
                }) 
            }
        }
        searchBar.addEventListener('input',checker);
        const rows=document.querySelectorAll('.table-row');
        rows.forEach((el)=>{
            el.addEventListener('click',()=>{
                comp=(""+el.id).split("-");
                location.href='update-channeling?cmd=view&id='+comp[3];
            })
        })
    
</script>