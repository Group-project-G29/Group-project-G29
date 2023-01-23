<?php
    /** @var $model \app\models\User */
?>


<?php

use app\core\DbModel;
use \app\core\form\Form;
$form=Form::begin('','post');?> 
<section class="reg_body" style="padding-bottom:100px">
    <div class="reg-body_title">
        <h1 class="fs-200 ">Employee Registration</h1>
    </div>
    <div class="reg-body_fields">
    <?php echo $form->field($model,'name','Name*','field','text') ?>
    <?php echo $form->field($model,'nic','NIC*','field','text') ?>
    <?php echo $form->field($model,'age','Age*','field','text') ?>
    <?php echo $form->select($model,'gender','Gender*','field',['select','male','female'],'gender')?>
    <?php echo $form->field($model,'contact','Contact*','field','text') ?>
    <?php echo $form->field($model,'email','Email*','field','text') ?>
    <?php echo $form->field($model,'address','Address','field ','text') ?>
    <?php echo $form->select($model,'role','Role','field',['select','doctor','nurse','pharmacist','receptionist'],'picker')?>
    <?php echo $form->select($model,'speciality','Speciality','hide',['select','Cardiologist','Gastrologist','Radiologist'],'speciality');?>
    <?php echo $form->field($model,'description','Description','hide','text','description');?>
    <?php echo $form->field($model,'img','Profile Picture','field','file') ?>
    <?php echo $form->field($model,'password','Password*','field','password') ?>
    <?php echo $form->field($model,'cpassword','Retype Password*','field','password') ?>
    <div class="button-container"><input class="button--class-1" style="margin-bottom:3vh;" type="submit" value="Register"></div>
    </div>
   
    
    <?php Form::end() ?>   
     <script>
    
        const select=document.querySelector("#picker");
        const speciality=document.querySelector("#speciality");
        const  description=document.querySelector('#description');
        function hide(element,hideClass='hide',visibleClass='field'){
            element.classList.remove(visibleClass);
            element.classList.add(hideClass);
        }
        function visible(element,hideClass='hide',visibleClass='field'){
            element.classList.remove(hideClass);
            element.classList.add(visibleClass);
        }
        select.addEventListener('change',()=>{
            
            if(select.value=='doctor') {
                visible(speciality);
                visible(description);
                
            }
            else{
               hide(speciality);
               hide(description);
            }
            
        });
        
    </script>
    </section>
    </body>
</html>