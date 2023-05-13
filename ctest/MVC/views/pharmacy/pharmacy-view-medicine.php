<?php
    use app\core\component\Component;
    use app\models\Medicine;

    $component=new Component();
    $medicineModel = new Medicine();
?>
<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"name","search-bar--class1","Search by medicine name","search");?>
    </div>
    <?php echo $component->button('new-medicine','','Add New Medicine','button--class-0  width-10','new-medicine');?>
    
</div>
<div class="table-container">
<table border="0">
    <tr>
        <th></th>
        <th>Product</th>
        <th>Unit Price</th>
        <!-- <th>Availabilty</th> -->
        <th>Available Amount</th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach($medicines as $key=>$medicine): ?>
    <tr class="table-row unselectable" id=<?= $medicine['med_ID'].'-'.$medicine['name'] ?> >
        <td class="row-img-col"><img class="row-img" src=<?="./media/images/medicine/".$medicine['img']?> alt="No image"></td>
        <td><?=$medicine['name']." ".$medicine['strength']." ".$medicine['unit']?></td>
        <td><?= 'LKR. '. number_format($medicine['unit_price'],2,'.','') ?></td> 
        <!-- <td><?=($medicine['availability']=="NA")?"Not Available":"Available"; ?></td> -->
        <td><?=$medicine['amount']?></td>
        <td><div> <?php echo $component->button('update','','Update','button--class-2',$medicine['med_ID']) ?> </div></td>
        <td>
            <div> <?php 
                    if ( $medicineModel->isUnsedmedicine($medicine['med_ID'])){
                        echo $component->button('delete',' ','Delete','button--class-3',$medicine['med_ID']); 
                    }
                ?> </div>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>

</div>

<script>
    const btn=document.getElementById("new-medicine");
    btn.addEventListener('click',function(){
        location.href="handle-medicine";
    })

    elementsArray = document.querySelectorAll(".button--class-2");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='update-medicine?mod=update&id='+elem.id;
        });
    });
    
    elementsArray = document.querySelectorAll(".button--class-3");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='handle-medicine?cmd=delete&id='+elem.id;
        });
    });

    const medicines=document.querySelectorAll('.search-class');
    const searchBar=document.getElementById('search');
    searchBar.addEventListener('input',checker);
    function checker(){
        var re=new RegExp(("^"+searchBar.value).toLowerCase())
        medicines.forEach((el)=>{
        comp=""+el.id; 
        comp=comp.split("-");
        
        if(searchBar.value.length==0){
            // el.classList.add("none")
        }
        else if(re.test(comp[0].toLowerCase()) || re.test(comp[1].toLowerCase()) ){
            el.classList.remove("none");
        }
        else{
            el.classList.add("none");
            
        }
        })
        if(searchBar.value.length==0){
            medicines.forEach((el)=>{
                el.classList.remove("none");
            }) 
        }
    }
</script>