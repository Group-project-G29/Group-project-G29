<?php
    use app\core\component\Component;
    $component=new Component();

?>

<p class="navigation-text-line-p"> 
    <a class="navigation-text-line-link" href="/ctest/pharmacy-orders-pending">orders</a>/
    <a class="navigation-text-line-link">processing orders</a> 
</p>

<div class='upper-container'>
    <?php echo $component->button('pending','','Pending Orders','button--class-0-deactive  width-10','pending');?>
    <?php echo $component->button('processing','','Processing Orders','button--class-0-active  width-10','processing');?>
    <?php echo $component->button('delivering','','Packed Orders','button--class-0-deactive  width-10','delivering');?>
</div>

<div class='upper-container'>
    <div class="search-bar-container">
        <?php echo $component->searchbar($model,"name","search-bar--class1","Search by order ID, Name","search");?>
    </div>
</div>
   
<div class="table-container">
    <?php if($orders): ?>
        <table border="0">
            <tr>
                <th>Order ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Address</th>
                <!-- <th>Total Price</th> -->
                <!-- <th>Note</th> -->
                <th>PickUp Status</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        
            <?php foreach($orders as $key=>$order): ?>
                    <tr class="table-row search-class" id=<?= $order['order_ID'].'-'.$order['ordered_person'] ?> >
                        <td><?=$order['order_ID']?></td>
                        <td><?=$order['ordered_person']?></td> 
                        <td><?=$order['contact']?></td> 
                        <td><?=$order['address']?></td> 
                        <!-- <td><?=$order['total_price']?></td>  -->
                        <!-- <td>
                            <?php if($order['text']!=NULL): ?>
                                <?=$order['text']?>
                            <?php else: ?>
                                <?= 'NA' ?>
                            <?php endif; ?>
                        </td>  -->
                        <td><?=$order['pickup_status']?></td> 
                        <!-- deliveryd by -> delivery rider -->
                        <td><?=$order['created_date']?></td> 
                        <td><?= $timeModel->time_format($order['created_time']) ?></td> 
                    </tr>
            <?php endforeach; ?>
        </table>

    <?php else: ?>
        <br><br><br><h2>No Current Processing Orders</h2>
    <?php endif; ?>
</div>


<!-- ==================== -->
<script>

    elementsArray = document.querySelectorAll(".table-row");
    elementsArray.forEach(function(elem) {
        comp=""+elem.id; 
        comp=comp.split("-");
        elem.addEventListener("click", function() {
            location.href='pharmacy-view-processing-order?id='+comp[0]; 
        });
    });

    const btn1=document.getElementById("pending");
    btn1.addEventListener('click',function(){
        location.href="pharmacy-orders-pending"; //get
    })

    const btn2=document.getElementById("processing");
    btn2.addEventListener('click',function(){
        location.href="pharmacy-orders-processing"; //get
    })

    const btn3=document.getElementById("delivering");
    btn3.addEventListener('click',function(){
        location.href="pharmacy-orders-delivering"; //get
    })


    const orders=document.querySelectorAll('.search-class');
    const searchBar=document.getElementById('search');
    searchBar.addEventListener('input',checker);
    function checker(){
        var re=new RegExp(("^"+searchBar.value).toLowerCase())
        orders.forEach((el)=>{
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
            orders.forEach((el)=>{
                el.classList.remove("none");
            }) 
        }
    }
</script>