<?php
    use app\core\component\Component;
use app\core\form\Form;
?>

<?php $form = new Form();?>
<?php $component=new Component(); ?>

    <div class="referral-container">
         <div class="wrapper--referrals-showit">
                    <div class="variable-container-mu">
                        <table>
                            <tr>
                                <th>Medical Report</th><th>Added Date</th><th></th><th></th>
                            </tr>
                            <?php $count=0; ?>
                                <?php foreach($todayreport as $report): ?>
                                    <?php $count=$count+1; ?>
                                    <tr>
                                        <td><a href=<?="/ctest/doctor-report?spec=".$report['type']."&mod=view&id=".$report['report_ID']?>><?=$report['type']."-".$report['report_ID']?></a></td><td><?=$report['uploaded_date'] ?></td>
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

    <?php  $form->begin('','post')?>
    <div class="document-container-form">
        <div>
            <div>
                <?= $form->select($model,'report_ID','','',['SOAP Report'=>'soap-report','Consultation Report'=>'consultation-report','Medical History Report'=>'medical-history-report','Refferal'=>'referral'],'select-main') ?>
            </div>
            <div class="write-referral-btn">
                <?= $component->button('','submit','Add Report','button--class-0'); ?>
            </div>
        </div>
        <div>
            <?= $form->textarea($model,'subjective','subjective','Subjective',10,100,$model->subjective);?>
            
        </div>
        <div>
            <?= $form->textarea($model,'objective','objective','Objective',10,100,$model->objective);?>
            
        </div>    
        <div>
            <?= $form->textarea($model,'assessment','assessment','Assessment',10,100,$model->assessment);?>
            
        </div>
        <div>
            <?= $form->textarea($model,'plan','plan','Plan',10,100,$model->plan);?>
            
        </div>
        <div>
            <?= $form->textarea($model,'additional_note','additional_note','Additional Note',10,100,$model->additional_note);?>
            
        </div>


    </div>
<?php $form->end(); ?>
<script src="./media/js/main.js"></script>
<script>
    const mainSelect=e('select-main','id');
    mainSelect.value="soap-report";
    mainSelect.addEventListener('change',()=>{

        location.href="/ctest/doctor-report?spec="+mainSelect.value;
    })
</script>