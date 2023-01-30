<?php
    use app\core\component\Component;
    $component=new Component();
// var_dump($delivery);
// exit;

?>

<div class="detail-container">
    
    <h1><?= $delivery['delivery_ID']?></h1>
        <table>
            <tr class="table-row">
                <td>Contact</td>
                <td><?= $delivery['contact'] ?></td>
            </tr>
            <tr class="table-row">
                <td>Address</td>
                <td><?= $delivery['address'] ?></td>
            </tr>
            <tr class="table-row">
                <td>Postal Code</td>
                <td><?= $delivery['postal_code'] ?></td>
            </tr>
        </table>
</div>



<!-- form to get the pin
button to confirm -->

<script>
    // const btn=document.getElementById("new-delivery");
    // btn.addEventListener('click',function(){
    //     location.href="handle-delivery";
    // })
    elementsArray = document.querySelectorAll(".button--class-2");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='delivery-view-delivery?id='+elem.id;
        });
    });
    // elementsArray = document.querySelectorAll(".button--class-3");
    // elementsArray.forEach(function(elem) {
    //     elem.addEventListener("click", function() {
    //         location.href='handle-delivery?cmd=delete&id='+elem.id;
    //     });
    // });
</script>