<?php
    use app\core\component\Component;
    use \app\core\form\Form;
    $component=new Component();
// var_dump($delivery);
// exit;

?>

<h1>Delivery ID : <?= $delivery['delivery_ID']?></h1>

<div class="table-container">
        <table border="0">
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
<br><br><hr><br>

<?php
$form=Form::begin('','post');?> 

<section class="form-body" style="padding-bottom:100px">

    <div class="main_title">
        <h2 class="fs-150 fc-color--dark">Enter PIN here</h2>
       
    </div>
    <div class="form-body-fields">
    <table>
    <?php echo $form->spanfield($model,'pin','','field','text') ?>
    </table>
    <div><?php echo $component->button("complete","submit","Complete Order","button--class-0","complete")?></div>
    
    </div>
   
    
    <?php Form::end() ?>  


<!-- form to get the pin
button to confirm -->

<script>
    elementsArray = document.querySelectorAll(".button--class-2");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href='delivery-view-delivery?id='+elem.id;
        });
    });
</script>