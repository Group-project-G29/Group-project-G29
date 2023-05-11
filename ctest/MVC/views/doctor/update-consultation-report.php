<?php
    use app\core\component\Component;
    use app\core\form\Form;

    $form = new Form();
    $component=new Component();
?>
<?php  $form->begin('','post')?>
    <div class="referral-container"">
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
    <div class="write-referral-form">
        <div class='write-referral-head'>
            <div>
                <?= $form->select($model,'report_ID','','',['SOAP Report'=>'soap-report','Consultation Report'=>'consultation-report','Medical History Report'=>'medical-history-report','Refferal'=>'referral'],'select-main') ?>
            </div>
        </div>
        <div class="write-referral-btn">
            <?= $component->button('','submit','Update Report','button--class-0'); ?>
        </div>
        <div>
            <?= $form->textarea($model,'examination','examination','Examination',10,120,$model->examination);?>
            
        </div>
        <div>
            <?= $form->textarea($model,'consultation','consultation','Consultation',18,120,$model->consultation);?>
            
        </div>    
        <div>
            <?= $form->textarea($model,'recommendation','recomendation','Recommendation',18,120,$model->recommendation);?>
            
        </div> 


    </div>
<?php $form->end(); ?>
<script src="./media/js/main.js"></script>
<script>
    const mainSelect=e('select-main','id');
    mainSelect.value="consultation-report";
    mainSelect.addEventListener('change',()=>{
        console.log("change");
        location.href="/ctest/doctor-report?spec="+mainSelect.value;

    })
    //delete buttons
    const deletes=document.querySelectorAll(".ref-delete");
    deletes.forEach((el)=>{     
        el.addEventListener('click',(event)=>{
            event.preventDefault();
            console.log("change");
            location.href="/ctest/doctor-report?cmd=delete&id="+el.id;
    
        })
    })

    //update buttons
    const updates=document.querySelectorAll(".ref-update");
    updates.forEach((el)=>{     
        el.addEventListener('click',(event)=>{
            event.preventDefault();
            location.href="/ctest/doctor-report?mod=update&id="+el.id;
    
        })
    })
</script>