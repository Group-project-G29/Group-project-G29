<?php
    use app\core\component\Component;
use app\core\form\Form;

    $form = new Form();
    $component=new Component();
?>
<section>
    <div>
         <div class="wrapper--referrals">
                    <div class="variable-container">
                        <table>
                            <tr>
                                <th>Medical Report</th><th>Added Date</th><th></th><th></th>
                            </tr>
                            <?php $count=0; ?>
                                <?php foreach($todayreport as $report): ?>
                                    <?php $count=$count+1; ?>
                                    <tr>
                                        <td><a href=<?="/ctest/doctor-report?spec=referral&mod=view&id=".$report['report_ID']?>><?=$report['type']."-".$report['report_ID']?></a></td><td><?=$report['uploaded_date'] ?></td>
                                        <?php if($report['uploaded_date']==Date('Y-m-d')):   ?>
                                            <td><?=$component->button('update','','Update','button--class-2-small ref-update',$report['report_ID']); ?></td>
                                            <td><?=$component->button('delete','','Delete','button--class-3-small ref-delete',$report['report_ID']); ?></td>
                                        <?php endif; ?>
                                        </tr>
                                <?php endforeach; ?>
                                <?php if($count==0): ?><script>(document.querySelector('.wrapper--referrals')).classList.add('hide');</script><?php endif; ?>
                        </table>
                    </div>
        </div>
    </div>
    <?php $form->begin('','post'); ?>
        <div>
            <div>
                <div>
                    <?= $form->select($model,'report_ID','','',['SOAP Report'=>'soap-report','Consultation Report'=>'consultation-report','Medical History Report'=>'medical-history-report','Refferal'=>'referral'],'select-main') ?>
                </div>
                <div>
                    <?= $component->button('','submit','Add Report','button--class-0'); ?>
                    
                </div>
            </div>
            <div>
                <?= $form->textarea($model,'medication','medication','Passed Medication',10,130,'');?>
                
            </div>
            <div>
                <?= $form->textarea($model,'allergies','allergies','Allergies',10,130,'');?>
                
            </div>    
            <div>
                <?= $form->textarea($model,'note','note','Note',10,130,'');?>
                
            </div>


        </div>
    <?php $form->end(); ?>
</section>
<script src="./media/js/main.js"></script>
<script>
    const mainSelect=e('select-main','id');
    mainSelect.value="medical-history-report";
    mainSelect.addEventListener('change',()=>{

        location.href="/ctest/doctor-report?spec="+mainSelect.value;
    })
</script>