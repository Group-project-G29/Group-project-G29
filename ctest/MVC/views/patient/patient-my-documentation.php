<?php

use app\core\component\Component;
use app\models\LabReport;

$component=New Component();
$labreportModel=new LabReport();
?>
<section class="document-section">
<section class="docu-searchbar-container">
    <?=$component->searchbar($component,'','search-bar--class1','Search by type','search-bar'); ?>
</section>
<section class="document-main-container">
    <?php  foreach($labreports as $labreport): ?>
        <div class="document-container">
            <div class="document-image">
                <img src="./media/images/common/labreport.png">
            </div>
            <a href=<?="'"."handle-labreports?spec=lab-report&cmd=view&id=".$labreport['report_ID']."'" ?>><?=($labreportModel->getTitle($labreport['report_ID'])?$labreportModel->getTitle($labreport['report_ID']):'Lab Report')."-".$labreport['report_ID']?></a>
        </div>
    <?php endforeach;?>
    <?php  foreach($reports as $report): ?>
        <div class="document-container">
            <div class="document-image">
                <img src="./media/images/common/medicalreport.png">
            </div>
            <a href=<?="'"."handle-documentation?spec=".$report['type']."&mod=view&id=".$report['report_ID']."'" ?>><?=$report['type']." Report"?></a>
        </div>
    <?php endforeach;?>
    <?php  foreach($prescriptions as $prescription): ?>
        <div class="document-container">
            <div class="document-image">
                <img src="./media/images/common/prescription.png">
            </div>
            <a href=<?="'"."handle-documentation?spec=prescription"."&mod=view&id=".$prescription['prescription_ID']."'" ?>><?="Prescription-".$prescription['prescription_ID']?></a>
        </div>
    <?php endforeach;?>
   

</section>
</section>
