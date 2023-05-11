<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\Date;
use app\core\UserModel;

class Payment extends DbModel{
    public string $patient_ID='';
    public string $name='';
    public string $type='';
    public string $payment_status="";
    public float $amount=0;
    public ?string $order_ID=null;
    public ?string $appointment_ID=null;

    //get payment information of a patient
    public function getOrderPay($patient){
        return $this->fetchAssocAll(['type'=>'order','payment_status'=>'pending','patient_ID'=>$patient]);
    }

    //pay  payment
    public function createAppointmenPay($patient_ID,$name,$amount,$appointment_ID,$paymentval){
        //create payment
       
        $payment=new Payment();
        $payment->patient_ID=$patient_ID;
        $payment->name=$name;
        $payment->type='appointment';
        $payment->payment_status=$paymentval;
        $payment->amount=$amount;
        $payment->appointment_ID=$appointment_ID;
        return $payment->save();

    }   
    
     public function createOrderPay($patient_ID,$name,$amount,$payme,$order){
        //create payment
        $payment=new Payment();
        $payment->patient_ID=$patient_ID;
        $payment->name=$name;
        $payment->type='order';
        $payment->payment_status=$payme;
        $payment->amount=$amount;
        $payment->order_ID=$order;
        return $payment->save();

    } 
        
    public function getCheckerPayments(){
        $total=0;
        // ["sel_pays"]=> array(2) { [0]=> string(3) "105" [1]=> string(3) "106" }
        Application::$app->session->set('sel_pays',$_POST['sel_pays']);
        foreach($_POST['sel_pays'] as $payments){
            $pay=$this->fetchAssocAll(['payment_ID'=>$payments]);
            $fee=$pay[0]['amount'];
            $total=$total+$fee;
        }
        return $total;
    }    
    public function donePayment(){
        $result=Application::$app->session->get('sel_pays',$_POST['sel_pays']);
        foreach($result as $payments){
            $this->customFetchAll("update payment set payment_status='done' where payment_ID=",$payments);
        }
        return true;
    }
    public function earningValues($para){
        $dateModel=new Date();
        $today=Date('Y-m-d');
        $year=$dateModel->get($today,'year')-1;
        $month=$dateModel->get($today,'month');
        $update=$dateModel->arrayToDate([01,$month,$dateModel->get($today,'year')]);

        if($para == 'year'){
            $lowdate=$dateModel->arrayToDate([01,$month,$year]);
            
            $result = $this->customFetchAll("SELECT MONTH(generated_timestamp), SUM(amount) FROM `payment` WHERE generated_timestamp>='$lowdate' and generated_timestamp<='$update' GROUP by MONTH(generated_timestamp);");
            // var_dump($result);exit;
    
            $value=[0,0,0,0,0,0,0,0,0,0,0,0];
            foreach($result as $row){
                $value[$row['MONTH(generated_timestamp)']-1]=$row['SUM(amount)'];
            }
        }
        elseif($para == 'month'){
            $value = $this->customFetchAll("SELECT SUM(amount) FROM `payment` WHERE month(generated_timestamp)=month('".Date('Y-m-d')."');");
        }
        return ['value'=>$value];
    }

    //pay  payment

    //create payment


    //sandbox
    
    public function rules():array{
        return [];
    }
    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'payment';
    }
    public function primaryKey(): string
    {
        return 'payment_ID';
    }
    public function tableRecords(): array{
        return ['payment'=>['patient_ID','name','type','payment_status','amount','order_ID','appointment_ID']];
    }

    public function attributes(): array
    {
        return ['patient_ID','name','type','payment_status','amount','order_ID','appointment_ID'];
    }
    



    public function payNow($amount,$text,Patient $patientModel,$order,$hash,$return_complete,$return_fail){
        $hash1 = strtoupper(
                            md5(
                                '1222960'. 
                                "Medicine Order-".Application::$app->session->get('user'). 
                                number_format($amount, 2, '.', ''). 
                                'LKR'.  
                                strtoupper(md5('MzA1MDU0OTcyMjM4NDk5OTEyMTMwNzQ1NjE1ODAxMzI3Nzc3MjIx')) 
                            ) 
        );
        $str='<script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
    <script>
    // Payment completed. It can be a successful failure.
    payhere.onCompleted = function onCompleted(orderId) {
        location.href="%s";
        // Note: validate the payment and show success or failure page to the customer
    };

    // Payment window closed
    payhere.onDismissed = function onDismissed() {
        // Note: Prompt user to pay again or show an error page
        location.href="%s";
    };

    // Error occurred
    payhere.onError = function onError(error) {
        // Note: show an error page
        location.href="%s";
    };

    // Put the payment variables here
    var payment = {
        "sandbox": true,
        "merchant_id": "1222960",    // Replace your Merchant ID
        "return_url": undefined,     // Important
        "cancel_url": undefined,     // Important
        "notify_url": "http://sample.com/notify",
        "order_id": "%s",
        "items": "%s",
        "amount": "%s",
        "currency": "LKR",
        "hash": "%s", // *Replace with generated hash retrieved from backend
        "first_name": "%s",
        "last_name": "%s",
        "email": "%s",
        "phone": "%s",
        "address": "%s",
        "city": "Colombo",
        "country": "Sri Lanka",
        "delivery_address": "No. 46, Galle road, Kalutara South",
        "delivery_city": "Kalutara",
        "delivery_country": "Sri Lanka",
        "custom_1": "",
        "custom_2": ""
    };

    // Show the payhere.js popup, when "PayHere Pay" is clicked
    
        payhere.startPayment(payment);
    
</script>';   
        return sprintf($str,$return_complete,$return_fail,$return_fail,$order,$text,number_format($amount,'0','.',''),$hash1,explode(" ",$patientModel->name)[0],explode(" ",$patientModel->name)[1]??'',$patientModel->email,$patientModel->contact,$patientModel->address);
    }

    public function update_payment_status ($order_ID) {
        return $this->customFetchAll("UPDATE _order SET payment_status = 'done' WHERE order_ID = $order_ID;");
    }


    
}   



?>