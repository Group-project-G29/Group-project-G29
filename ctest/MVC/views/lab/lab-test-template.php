<?php

use app\core\component\Component;
// var_dump("hgf");
// exit;
$component = new Component();
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;

$form = Form::begin('', 'post'); ?>
<div class="">
    <div class="reg-body-spec_title">
        <h1 class="fs-200 fc-color--dark" style="padding-bottom: 0vh;">Add Report Template</h1>
    </div>
    <div class="semi-header-container">
        <!-- <?php echo $form->field($templatemodel, 'title', 'Title*', 'field', 'text', 'title-1') ?>
        <?php echo $form->field($templatemodel, 'subtitle', 'Sub Title', 'field', 'text', 'sub') ?>  -->
        <!-- <div class="button" style="margin-top: 2vh;">
            <?= $component->button('btn', '', '+ Add', 'button--class-0','btn-1'); ?>
        </div> -->


    </div>
    <!-- <?php Form::end() ?> -->
   
    <div class="semi-header-container">

        <div class="field-container">
            <section class="reg_body-spec" style="padding-bottom:50px">
            <?php $form = Form::begin('', 'post'); ?>

                <!-- <div class="reg-body-spec fields" style="padding-left:15vw"> -->

                <table class="template">
                    <?php echo $form->select($contentmodel, 'type', 'Type', 'field', ['select','image', 'field', 'text'], 'picker'); ?>
                    <?php echo $form->field($contentmodel, 'name', 'Name', 'hide', 'text', 'name'); ?>
                    <?php echo $form->field($contentmodel, 'reference_ranges', 'Reference Range', 'hide', 'text', 'range'); ?>
                    <?php echo $form->select($contentmodel, 'metric', 'Metric', 'hide', ['select','K/UL', 'MIL/UL', 'G/UL'], 'metric'); ?>
                    <?php echo $form->field($contentmodel, 'position', 'Upload', 'hide', 'text', 'img'); ?>
                    <?php echo $form->field($contentmodel, 'description', 'Description', 'hide', 'text', 'des'); ?>

                </table>
                <div class="button" style="margin-top: 2vh;">
                    <?= $component->button('btn', '', 'Add', 'button--class-0','btn-2'); ?>
                </div>
                

                <!-- </div> -->

        </div>

        <?php Form::end() ?>
        </section>

        <div class="main-card ">
  <?php foreach ($contents as $content) : ?>

    <div class="card" >

      <div class="card-header-1 " style="padding-top: 5vh;padding-bottom:7.9vh">
        <h5><b>Name :</b><?= $content['metric'] ?> </h5>

        <h5><b>Patient :</b><?= $content['reference_ranges'] ?> </h5>
        <!-- <h5><b>Req date & Time :</b><?= $content['requested_date_time'] ?> </h5>

        <h5><b>content :</b><?= $content['test_name'] ?> </h5> -->

      </div>
      <!-- <a href="lab-write-test-result.php"> -->
     


    </div>

  <?php endforeach; ?>

</div>
    </div>
</div>



<script>
    const select = document.querySelector("#picker");
    const name = document.querySelector("#name");
    const reference_ranges = document.querySelector('#range');
    const metric = document.querySelector('#metric');
    const position = document.querySelector('#position');
    const text = document.querySelector('#des');

    function hide(element, hideClass = 'hide', visibleClass = 'field') {
        element.classList.remove(visibleClass);
        element.classList.add(hideClass);
    }

    function visible(element, hideClass = 'hide', visibleClass = 'field') {
        element.classList.remove(hideClass);
        element.classList.add(visibleClass);
    }
    select.addEventListener('change', () => {

        if (select.value == 'field') {
            visible(name);
            visible(reference_ranges);
            visible(metric);
            hide(img);
            hide(des);
        } else if (select.value == 'image') {
            visible(img);
            visible(des);
            hide(name);
            hide(reference_ranges);
            hide(metric)


        } else if (select.value == 'text') {
            visible(des);
            hide(name);
            hide(reference_ranges);
            hide(metric)
            hide(img);


        }

    });
</script>

<script>
    // const txt1 = document.getElementById('template');
    // const btn1 = document.getElementById('button');
    // const out1 = document.getElementById('output');

    // function fun1() {
    //     out1.innerHTML = txt1.value;

    // }

    // btn1.addEventListener('click', fun1);
</script>

