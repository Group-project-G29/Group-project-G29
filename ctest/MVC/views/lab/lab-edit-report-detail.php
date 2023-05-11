<?php

use app\core\component\Component;

$component = new Component();

?>


<?php

use \app\core\form\Form;
?>
<div class="semi-header-container">
  <div class="field-container" style="margin-left: 5vw;">
    <h1 class="fs-200 fc-color--dark">Update Report</h1>
    <?php $age = "18" ?>
    <h5><?php if ($patients[0]['age'] < $age) {
          echo "*Pediatric";
        } else {
          echo "*Adult";
        } ?></h5>
    <h4><b>Request No : </b><?= $patients[0]['request_ID'] ?></h4>
    <h4><b>Name : </b><?= $patients[0]['pname'] ?></h4>
    <h4><b>Age : </b><?= $patients[0]['age'] ?></h4>
    <h4><b>Gender : </b><?= $patients[0]['gender'] ?></h4>
  </div>
  <hr>
  <div class="field-container" style="margin-left: 5vw;">

    <?php $form = Form::begin('lab-edit-report-detail?cmd=update&id=' . $parameters[1]['id'], 'post'); ?>
    <div class="inputbox">
      <?php foreach ($reportDetail as $report) : ?>
        <table border='0'>
          <tr> <label for="cname"><?php echo $report["name"] ?></label></tr>
          <tr><input type='text' id=<?= "'" . $report["report_ID"] . "'" ?> name=<?= "'" . $report["content_ID"] . "'" ?> value=<?= "'" . $report['int_value'] . "'" ?>></tr>
          <tr> <label for="cname"><?php echo $report["metric"] ?></label><br><br></tr>
        <?php endforeach ?>

        </table>
        <div class="button" style="margin-top: 2vh;">
          <?= $component->button('update-test', 'submit', 'Update', 'button--class-0', ''); ?>
        </div>
    </div>

    </section>
    <?php Form::end() ?>
  </div>

</div>
