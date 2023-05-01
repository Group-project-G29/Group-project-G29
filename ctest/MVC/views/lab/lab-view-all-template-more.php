<?php

use app\core\component\Component;

$component = new Component();
use \app\core\form\Form;
use app\core\Application;
// $curr_ID =$model->content_ID;
?>

<div class="search-bar-container" style="padding-left:19vw">
  <?php echo $component->searchbar('', 'search', 'search-bar--class2', 'Search by name,specilaity', 'searchbar'); ?>
</div>
<div class="semi-header-container-1">
    <div class="semi-field-container" style="margin-top:5vw">
        <tr>
             <td><b><?= $detail["title"] ?></b></td><br>
            <td><?= $detail["subtitle"] ?></td><br><br>
        </tr>
    </div>
<div class="table-container">
    <table border="0">
                    <?php foreach ($templates as $template) : ?>
                        <tr class='table-row  ' id=<?= $template["content_ID"] ?>>

                            <?php if ($template["type"] === "field") : ?>
                                <td><b>Type :</b><?= $template['type'] ?> </td>
                                <td><b>Name :</b><?= $template['name'] ?> </td>
                                <td><b>Metric :</b><?= $template['metric'] ?> </td>
                                <td><b>Reference Ranges :</b><?= $template['reference_ranges'] ?> </td>
                               
                                <!-- <td> <i class="fa fa-trash" aria-hidden="true"></i> -->
                                <td><?= $component->button('btn', '', 'X', 'btn-1', $template["content_ID"]); ?></td>
                                <td><?= $component->button('btn', '', 'Edit', 'btn-2', $template["content_ID"]); ?></td>


                            <?php endif; ?>

                            <?php if ($template["type"] === "image") : ?>
                                <td><b>Type :</b><?= $template['type'] ?> </td>
                                <td><b>Name :</b><?= $template['name'] ?> </td>
                                <td><b> </b><?    ?> </td>
                                <td><b> </b><?    ?> </td>
                                


                                <td><?= $component->button('btn', '', 'X', 'btn-1', $template["content_ID"]); ?></td>
                                <td><?= $component->button('btn', '', 'Edit', 'btn-2', $template["content_ID"]); ?></td>

                            <?php endif; ?>

                            <?php if ($template["type"] === "text") : ?>
                                <td><b>Type :</b><?= $template['type'] ?> </td>
                                <td><b>Name :</b><?= $template['name'] ?> </td>
                                <td><b> </b><?    ?> </td>
                                <td><b> </b><?    ?> </td>
                               


                                <td><?= $component->button('btn', '', 'X', 'btn-1', $template["content_ID"]) ?></td>
                                <td><?= $component->button('btn', '', 'Edit', 'btn-2', $template["content_ID"]); ?></td>

                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
    </table>
</div>
</div>

<!-- //popup -->

<!-- 
 <div class="popup-container" id="popup">
    <div class="modal-form">
        
        <h1 class="modal-title">Update Template content</h1>
        
        <div class="form-body">
            
            <?php $form = Form::begin('lab-view-all-template-more?cmd=update&id=', 'post '); ?>
            <?php if ($template['type'] === "field"): ?>
          
            <?php echo $form->field($model, 'name', 'Name', '', 'text', 'name'); ?>
            <?php echo $form->field($model, 'reference_ranges', 'Reference Range', '', 'text', 'range'); ?>
             <?php echo $form->select($model, 'metric', 'Metric', '', [ 'K/UL', 'MIL/UL', 'G/UL', 'FL','select'], 'metric'); ?>
             <?php endif ?>
             <?php if ($template['type'] === "image") :?>
              <?php  echo $form->field($model, 'name', 'Name', '', 'text', 'name'); ?>
              <?php endif ?>
          
            <?php if ($template['type'] === "text"): ?>
              <?php  echo $form->field($model, 'name', 'Name', '', 'text', 'name'); ?>

              <?php endif ?>

            <?= $component->button('btn', 'submit', 'update', 'button--class-5', 'btn-3'); ?>


            <?php Form::end() ?> 
           

        </div>
        <?= $component->button('btn', 'submit', "&times", '', 'closebtn'); ?>

    </div>

</div>  -->


<script>
    
    const txt1 = document.getElementById('template');
    // const btn1 = document.getElementById('button');
    const out1 = document.getElementById('output');

    function fun1() {
        out1.innerHTML = txt1.value;

    }

    //btn1.addEventListener('click', fun1);

   
    
    // var popup=document.getElementById("popup");
    // var closebtn=document.getElementById("closebtn");
    // var addeditebtn=document.getElementById("btn-2");
    // // var add=document.getElementById("btn-2");
    // addeditebtn.onclick=function(){
    //     popup.style.display="block";
        
    // }
    // closebtn.onclick=function(){
    //     popup.style.display="none";
    // } 
   
    // window.onclick=function(event){
    //     if(event.target== popup){
           
    //     }
    // }

        // document.getElementById("btn-2").addEventListener("click",function(){
        //     document.querySelector(".popup-container").style.display="flex";
        // })

    // elementsArray = document.querySelectorAll(".btn-2");
    // elementsArray.forEach(function(elem) {
    //     elem.addEventListener("click", function() {
    //         location.href = 'lab-view-all-template-more?mod=update&id=' + elem.id;
    //     });
    // });
    elementsArray = document.querySelectorAll(".btn-1");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-view-all-template-more?cmd=delete&id='+ elem.id;
        });
    });

    elementsArray = document.querySelectorAll("#btn-4");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-view-all-template';
        });
    });
</script>