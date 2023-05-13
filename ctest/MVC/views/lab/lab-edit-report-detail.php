<?php

use app\core\component\Component;

$component = new Component();

?>
<?php

use \app\core\form\Form;
?>
<div class="semi-header-container" style="left: 20vw;padding-top:0vw;">
  <img src="./media/images/logo-1.png" style="width:15vw;margin-left:13vw">

  <div class="field-container" style="margin-left: 5vw; line-height:1.5vw">
    <div class="field-container-left" style="width:50%">

      <!-- <h1 class="fs-200 fc-color--dark">Update Report</h1> -->
      <?php $age = "18" ?>
      <h5><?php if ($patients[0]['age'] < $age) {
            echo "*Pediatric";
          } else {
            echo "*Adult";
          } ?></h5>
      <h4><b>Request No : </b><?= $patients[0]['request_ID'] ?></h4>
      <h4><b>Doctor :</b>Dr.<?= $patients[0]['ename'] ?></h4>
      <h4><b>Patient Name : </b><?= $patients[0]['pname'] ?></h4>
      <h4><b>Age : </b><?= $patients[0]['age'] ?></h4>
      <h4><b>Gender : </b><?= $patients[0]['gender'] ?></h4>
    </div>
    <div class="field-container-right" style="width:50%">
    <h4><b> Test Name : </b><?= $patients[0]['tname'] ?></h4>
      <h4><b>Note : </b><?= $patients[0]['note'] ?></h4>
    </div>
  </div>

  <hr>
  <div class="field-container" style="margin-left: 5vw;">

    <?php $form = Form::begin('lab-edit-report-detail?cmd=update&id=' . $parameters[1]['id'], 'post'); ?>

    <div class="button" style="margin-left:20vw;margin-bottom:2vw">
      <?= $component->button('update-test', 'submit', 'Update', 'button--class-0', ''); ?>
    </div>
    <?php foreach ($reportDetail as $report) : ?>
      
      <div class="report-container">
        <label class="label1" for="cname"><?php echo $report["name"] ?></label>
        <label class="label2" for="cname"><?php echo $report["metric"] ?></label>
        <input class="" id=<?= "'" . $report["report_ID"] . "'" ?> name=<?= "'" . $report["content_ID"] . "'" ?> type="text" value=<?= "'" . $report['int_value'] . "'" ?>>

        <br>
      </div>
    <?php endforeach ?>

    <?php Form::end() ?>


  </div>




</div>