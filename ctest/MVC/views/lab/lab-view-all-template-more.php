<?php

use app\core\component\Component;

$component = new Component();

use \app\core\form\Form;
use app\core\Application;

?>


<div class="semi-header-container-1">
    <div class="flex" style="margin-top:2vh; margin-left:4vw;">

        <h1><?= $detail["title"] ?><h1>
                <h2><?= $detail["subtitle"] ?></h2>

    </div>
    <?php if ($templates) : ?>
        <div class="table-container">
            <table border="0">
                <?php foreach ($templates as $template) : ?>
                    <tr class='table-row  ' id=<?= $template["content_ID"] ?>>

                        <?php if ($template["type"] === "field") : ?>
                            <td><b>Type :</b><?= $template['type'] ?> </td>
                            <td><b>Name :</b><?= $template['name'] ?> </td>
                            <td><b>Metric :</b><?= $template['metric'] ?> </td>
                            <td><b>Reference Ranges :</b><?= $template['reference_ranges'] ?> </td>

                            <td><?= $component->button('btn', '', 'Remove Content', 'btn-1', $template["content_ID"]); ?></td>


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



                            <td><?= $component->button('btn', '', 'Remove Content', 'btn-1', $template["content_ID"]) ?></td>

                        <?php endif; ?>
                    </tr>

                <?php endforeach; ?>

            </table>

        </div>
        <div class="">
            <?php echo $component->button('edit-details', '', 'Edit content', 'button--class-7 edit-btn', $template['template_ID']) ?>
        </div>
        
        <?php else : ?>
            <div class="empty-div">
                <img src="media/images/common/emptycontent.png" class="emptycontent">
                <p class="empty-para">Looks Like There's No Content</p>
            </div>
            <div class="" style="padding-right:10vw">
                <?php echo $component->button('edit-details', '', 'Add content', 'button--class-0 edit-btn ', $tempID['template_ID']) ?>
            </div>
    <?php endif; ?>



</div>


<script>
    // const txt1 = document.getElementById('template');
    // const btn1 = document.getElementById('button');
    // const out1 = document.getElementById('output');

    function fun1() {
        out1.innerHTML = txt1.value;

    }
    elementsArray = document.querySelectorAll(".btn-1");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-view-all-template-more?cmd=delete&id=' + elem.id;
        });
    });
    elementsArray = document.querySelectorAll(".edit-btn");
    elementsArray.forEach(function(elem) {
        elem.addEventListener("click", function() {
            location.href = 'lab-test-template?id=' + elem.id;
        });
    });

    // elementsArray = document.querySelectorAll("#btn-4");
    // elementsArray.forEach(function(elem) {
    //     elem.addEventListener("click", function() {
    //         location.href = 'lab-view-all-template';
    //     });
    // });
</script>