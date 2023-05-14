<?php

use app\core\component\Component;

$component = new Component();

?>
<?php

use \app\core\form\Form;
?>

<div class="base-container">
    <!-- <img src="./media/images/logo-1.png" style="width:15vw;margin-left:13vw"> -->
    <div class="field-container-1">
        <div class="field--container-left">
            <?php $age = "18" ?>
            <h3><?php if ($patients[0]['age'] < $age) {
                    echo "*Pediatric";
                } else {
                    echo "*Adult";
                } ?>
            </h3>
            <div class="test-name"><?= $patients[0]['tname'] ?></div>
            

            <?php if($patients[0]['note']){ ?>
            <div class="field-content-data">
                <div>Note : </div><?= $patients[0]['note'] ?>
            </div>
            <?php } ?>
        </div>
        <div class="field--container-right" >
            
            <div class="field-content-data">
                <div>Request No : </div><?= $patients[0]['request_ID'] ?>
            </div>
            <div class="field-content-data">
                <div>Doctor :</div>Dr.<?= $patients[0]['ename'] ?>
            </div>
            <div class="field-content-data">
                <div>Patient Name : </div><?= $patients[0]['pname'] ?>
            </div>
            <div class="field-content-data">
                <div>Age : </div><?= $patients[0]['age'] ?>
            </div>
            <div class="field-content-data">
                <div>Gender : </div><?= $patients[0]['gender'] ?>
            </div>
        </div>
    </div>

    <hr>


    <div class="field-container-2">
        <?php $form = Form::begin('lab-edit-report-detail?cmd=update&id=' . $parameters[1]['id'], 'post'); ?>

        <div class="button" style="margin-left:40vw;margin-bottom:2vw">
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



