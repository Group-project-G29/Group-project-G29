<?php

use app\core\component\Component;

$component = new Component();
?>

<div class="search-bar-container flex" style="padding-left:15vw">
  <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by test name', 'searchbar'); ?>
  <div style="margin-left:-25vw">
    <?php echo $component->button('edit-details', '', 'Add New Test', 'button--class-0  width-10', 'edit-details'); ?>
  </div>
</div>
<div class="large-div">
<?php foreach ($tests as $key=> $test) : ?>
  
  <div class="tests"  id=<?= "'".$test['name']."'" ?>>
    
  <div class="card-new">
    
    <div class="card-details">
      <p class="text-title"><?= $test['name'] ?></p>
      <p class="text-body"><b>Test Fee :</b><?="LKR ".$test['test_fee'].".00" ?> </p>
      <p class="text-body"><b>Hospital Fee :</b><?= "LKR".$test['hospital_fee'].".00" ?> </p>
    </div>
      <?php echo $component->button('edit-details', '', 'Edit Details', 'button--class-7 ', $test['name']) ?>
  <?php if($test['report_ID']!=NULL):?>
  <?php echo $component->button('edit-details', '', 'Delete', 'button--class-9', $test['name']) ?>
  <?php endif;?>
  
</div>
</div>
<?php endforeach; ?>
</div>
<script>

const tests=document.querySelectorAll('.tests');
        const searchBar=document.getElementById('searchbar');

        function checker(){
        
            var re=new RegExp(("^"+searchBar.value).toLowerCase())
        tests.forEach((el)=>{
            d=""+el.id;
            if(re.test((d).toLowerCase())){
                el.classList.remove("none");
            }
            else{
                el.classList.add("none");
               
            }
            })
            if(searchBar.value.length==0){
                tests.forEach((el)=>{
                    el.classList.remove("none");
                }) 
            }
        }
        searchBar.addEventListener('input',checker);

  elementsArray = document.querySelectorAll(".button--class-0");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-add-new-test'; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-7");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-test-update?mod=update&id=' + elem.id; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-5");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-test-template'; //pass the variable value
    });
  });

  elementsArray = document.querySelectorAll(".button--class-9");
  console.log(elementsArray);
  elementsArray.forEach(function(elem) {
    elem.addEventListener("click", function() {
      location.href = 'lab-test-delete?cmd=delete&id=' + elem.id; //pass the variable value
    });
  });

  // const color=[];

  // color[1]=
</script>