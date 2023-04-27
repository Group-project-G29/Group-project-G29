<?php
    use app\core\component\Component;
    use app\core\form\Form;
    use app\models\Employee;

    $form = new Form();
    $component=new Component();
    $employeeModel=new Employee();
?>

    <div class="referral-container">
         <div class="wrapper--referrals-showit">
                    <div class="variable-container-mu">
                        <table>
                            <tr>
                                <th>Referral</th><th>Added Date</th><th></th><th></th>
                            </tr>
                            <?php $count=0; ?>
                            <?php if($referrals['written']): ?>
                                <?php foreach($referrals['written'] as $referral): ?>
                                    <?php $count=$count+1; ?>
                                    <tr>
                                        <td><a href=<?="/ctest/doctor-report?spec=referral&mod=view&id=".$referral['ref_ID']?>><?=$referral['doctor']?"Referral to Dr. ".$employeeModel->getDocName($referral['doctor']):"Referral to speciality ".$referral['speciality']?></a></td><td><?=$referral['date'] ?></td>
                                        <?php if($referral['date']==Date('Y-m-d')):   ?>
                                            <td><?=$component->button('update','','Update','button--class-2-small ref-update',$referral['ref_ID']); ?></td>
                                            <td><?=$component->button('delete','','Delete','button--class-3-small ref-delete',$referral['ref_ID']); ?></td>
                                        <?php endif; ?>
                                        
                                        </tr>
                                <?php endforeach; ?>
                                <?php if($count==0): ?><script>(document.querySelector('.wrapper--referrals')).classList.add('hide');</script><?php endif; ?>
                            <?php else: ?>
                                <h3>No Referrals</h3>
                            <?php endif; ?>
                        </table>
                    </div>
        </div>
    </div>
    <div class="write-referral-form">
        <div class='write-referral-head'>
            <div>
                <?php $form->begin('','post')  ?>
                <div>
                    <?= $form->spanselect($model,'type','','field',['SOAP Report'=>'soap-report','Consultation Report'=>'consultation-report','Medical History Report'=>'medical-history-report','Referral'=>'referral'],'select-main') ?>

                </div>
                <?= $form->spanselect($model,'doctor','Refer to Doctor','field',$doctors,''); ?>
                <?= $form->spanselect($model,'speciality','Select speciality','field',$specialities,'select-main') ?>
                <div class="write-referral-btn">
                    <?= $component->button('','submit','Add Report','button--class-0'); ?>
                </div>
                
            </div>
            <div>
                <div>
                    <label>Refer External Party :</label><input type="checkbox" class="checkbox" > 
                </div>
                <div class="choose-doctor hide">
                    <div>
                        <?= $form->field($model,'third_party','Refer to(Practitioner/Institute/Department)','field','text'); ?>
                    
                    </div>
                </div>
            </div>
        </div>
        <div>
            <?= $form->textarea($model,'history','history','Patient Medical History',10,100,'','');?>
            
        </div>
        <div>
            <?= $form->textarea($model,'reason','reason','Reason for Referral',10,100,'','');?>
            
        </div>
        <div>
            <?= $form->textarea($model,'assessment','assessment','Medical Assessment',10,100,'','');?>

        </div>    
        <div>
            <?= $form->textarea($model,'note','note','Note',10,100,'','');?>
            
        </div>

    </div>

    </div>

<?php $form->end(); ?>
<script src="./media/js/main.js"></script>
<script>
    //refferal update and delete
    const refdel=document.querySelectorAll(".ref-delete");
        refdel.forEach((el)=>{
           el.addEventListener('click',()=>{
               location.href="/ctest/doctor-report?spec=referral&cmd=delete&id="+el.id;
           })
    })
    const mainSelect=e('select-main','id');
    mainSelect.value="referral";
    mainSelect.addEventListener('change',()=>{

        location.href="/ctest/doctor-report?spec="+mainSelect.value;
    })
    const optionSelect=e('.checkbox','class');
    const chooseDiv=e('.choose-doctor','class');
    optionSelect.addEventListener('change',()=>{
        if(!optionSelect.checked){
            hide(chooseDiv);
        }
        else{
            visible(chooseDiv);
        }
    });
    const refupd=document.querySelectorAll(".ref-update");
        refupd.forEach((el)=>{
           el.addEventListener('click',()=>{
               location.href="/ctest/doctor-report?spec=referral&mod=update&id="+el.id;
           })
    })
</script>