<?php
    use app\core\component\Component;
use app\models\Employee;

    $component=new Component();

?>
<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar('',"name","search-bar--class1","Search by medicine name","searh");?>
    </div>
    <?php echo $component->button('new-medicine','','Add New Employee','button--class-0  width-10','new-medicine');?>
    
</div>
<div class="table-container">
<table border="0">
    <tr>
       <th>Name</th><th>Role</th><th>Status</th><th></th>
    </tr>
    <?php foreach($accounts as $key=>$account): ?>
    <tr class="table-row">
       
        <td><?=$account['name']?></td>
        <td><?=$account['role']?></td>  
        <td><?php 
                $able=true;
                $employee=new Employee();
                if($account['role']=='doctor'){
                    $res=$employee->customFetchAll("select * from channeling where doctor=".$account['nic']);
                    if($res){
                        $able=false;
                        echo "Active Account";
                    }
                    else{
                        echo "Non Active Account";
                    }
                }
                else if($account['role']=='nurse'){
                    if($employee->customFetchAll("select * from  nurse_channeling_allocataion where emp_ID=".$account['emp_ID'])){
                        $able=false;
                        echo "Active Account";
                    }
                    else{
                        echo "Non Active Account";
                    }
                    
                }
                else{
                    echo "Non Active Account";
                }
                
        
            ?>
        </td>
        <td>
            <?php if($able):?>
            <div>
                <?php echo $component->button('update','','Update','button--class-2',$account['emp_ID']) ?>
                <?php echo $component->button('delete',' ','Delete','button--class-3',$account['emp_ID']) ?>
            </div>
            <?php endif;?>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>

</div>

<script>
    const btn=document.getElementById("new-medicine");
    btn.addEventListener('click',function(){
        location.href="admin?mod=add";
    })
    elementsArray = document.querySelectorAll(".button--class-2");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='admin?mod=update&id='+elem.id;
        });
    });
    elementsArray = document.querySelectorAll(".button--class-3");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='admin?cmd=delete&id='+elem.id;
        });
    });
</script>