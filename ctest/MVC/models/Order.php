<?php
namespace app\models;
use app\core\DbModel;

    class Order extends DbModel{
        public string $pickup_status="";
        public string $patient_ID="";
        public string $cart_ID="";
        public ?string $delivery_ID="";
        public string $payment_status="pending"; //pending,completed
        public string $processing_status="processing";
        
        public function __construct(){

        }
        public function rules(): array
        {
            return []; 
        }
        public function fileDestination(): array
        {
            return [];
        }

        public function tableName(): string
        {
            return '_order';
        }

        public function primaryKey(): string
        {
            return 'order_ID';
        }
        
        public function tableRecords(): array{
            return ['_order'=>['pickup_status','patient_ID','cart_ID','delivery_ID','payment_status','processing_status']];
        }

        public function attributes(): array
        {
            return ['_order'=>['pickup_status','patient_ID','cart_ID','delivery_ID','payment_status','processing_status']];
        }

        public function completePayment($orderID){
            //update payment status to completed
            return $this->updateRecord(['_order'=>['order_ID'=>$orderID]],['_order'=>['payment_status'=>'completed']]);
        }
        //order is pending when pharmacist start packing orders it should change to Processing
        public function makeOrderStatusProcessing($orderID){

            return $this->updateRecord(['_order'=>['order_ID'=>$orderID]],['_order'=>['processing_status'=>'processing']]);
        }
        //order status is deliver when it is out for delivering
        public function makeOrderStatusDeliver($orderID){

            return $this->updateRecord(['_order'=>['order_ID'=>$orderID]],['_order'=>['processing_status'=>'deliver']]);
        }
        //state between deliver and completing preocessing (to be delivered orders)
        public function makeOrderStatusPacked($orderID){

            return $this->updateRecord(['_order'=>['order_ID'=>$orderID]],['_order'=>['processing_status'=>'packed']]);
        }
        //order is picked up or delivered by the client
        
        public function makeOrderStatusCompleted($orderID){

            return $this->updateRecord(['_order'=>['order_ID'=>$orderID]],['_order'=>['processing_status'=>'completed']]);
        }

        public function addItem($order,$item,$amount){
            return $this->saveByName(['medicine_in_order'=>['order_ID'=>$order,'med_ID'=>$item,'amount'=>$amount]]);
        }

        public function getOrderItem($orderID){
            //create view here
            return $this->customFetchAll("select * from medicine_in_order left join _order on _order.order_ID=medicine_in_order.order_ID right join medical_products on medical_products.med_ID=medicine_in_order.med_ID where medicine_in_order.order_ID=$orderID");
        }
    }

?>