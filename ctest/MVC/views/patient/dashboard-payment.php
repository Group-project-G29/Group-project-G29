<?php

use app\core\component\Component;
use app\core\form\Form;
use app\models\Appointment;
use app\models\LabReport;
use app\models\Order;
use app\models\Payment;
    $form=new Form();
    $payment=new Payment();
    $appointmentModel=new Appointment();
    $orderModel=new Order();
    $labreport=new LabReport();
    $total=0;
    $component=new Component();

?>
<?php $form->begin('','post'); ?>
<section class="payment-lslip">
    <div class="all-payment-holder">
        <table border='0' class="payment-table">
            <tr class="payment-lslip-header"><th class="width-10">Payment</th><th class="width-10">Created Date</th><th>Price</th><th class="width-10">Status</th><th class="width-10">Select All<input type="checkbox" id="sel"><th></tr>
            <?php foreach($payments as $payment):?>
                <?php

                    switch($payment['type']){
                        case 'order':
                            $orderDetail=$orderModel->fetchAssocAll(['order_ID'=>$payment['order_ID']]);
                            $name='Medicine Order('.ucfirst($orderDetail[0]['pickup_status']).")";
                            break;
                        case 'appointment':
                            $appointmentDetail=($appointmentModel->getAppointmentDetail($payment['appointment_ID']))?$appointmentModel->getAppointmentDetail($payment['appointment_ID'])[0]:[];
                            $name="Dr.".($appointmentDetail['name']??'')." channeling appointment";
                            break;
                        case 'labreport':
                            $labreportDetail=$labreport->getReport($payment['name']);
                            $name=$labreportDetail[0]['title'];
                            break;

                    }    
                    
                    
                ?>
                <tr>
                    <td class="width-10" align='left'><?="$name"?></td>
                    <td class="width-10" align='center'><?=$payment['generated_timestamp']?></td>
                    <td class="width-10" align='center'><?="LKR ".number_format($payment['amount'], 2, '.', '')?></td>
                    <td class="width-10" align='center'><?=ucfirst($payment['payment_status']) ?></td>
                    <td class="width-10" align='center'>
                    <?php if($payment['payment_status']!='done'): ?>
                        <?php if($sel_payment==$payment['payment_ID']):?>
                            <input type="checkbox" class="checks" name="sel_pays[]" value=<?="'".$payment['payment_ID']."'" ?> checked>
                        <?php else:?>
                            <input type="checkbox" class="checks" name="sel_pays[]" value=<?="'".$payment['payment_ID']."'" ?> >
                        <?php endif;?>
                    <?php else:?>
                        <img src="./media/anim_icons/animcompleted.gif">
                    <?php endif;?>
                    </td>
                </tr>
                    <?php if($payment['payment_status']):?>
                        <?php $total=$total+$payment['amount'] ?>
                    <?php endif;?>
                <?php endforeach;?>
            </table>
        </div>
        <div class="total-payment flex">
            <h3><?="Total Payment : LKR ".number_format($total, 2, '.', '')?></h3>
            <?=$component->button('btn','submit','Pay Now','button--class-0','');?>
        </div>
</section>
<?php $form->end(); ?>
<script>
    const mainc=document.getElementById('sel');
    checks=document.querySelectorAll(".checks");
    if(!checks) checks=document.querySelector(".sel_pays");
    mainc.addEventListener('click',()=>{
        checks.forEach((el)=>{
            if(mainc.checked) el.checked=true;
            else el.checked=false;
        })
    })
    checks.forEach((el)=>{
        el.addEventListener('click',()=>{
            if(!el.checked) mainc.checked=false;
        })
    })
</script>

