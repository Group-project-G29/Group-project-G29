<?php
namespace app\models;

use app\core\Application;
use app\core\DbModel;

    class Order extends DbModel{
        public string $pickup_status="";
        public string $patient_ID="";
        public string $cart_ID="";
        public ?string $delivery_ID="";
        public string $payment_status="pending"; //pending,completed
        public string $processing_status="pending";
        
        public function __construct(){

        }
        public function addOrder(){
            return parent::save();
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

        public function getPrescriptionsInOrder($order){
            return $this->fetchAssocAllByName(['order_ID'=>$order],'prescription');
        }

        public function getOrderItem($orderID){
            //create view here
            return $this->customFetchAll("select * from medicine_in_order left join _order on _order.order_ID=medicine_in_order.order_ID right join medical_products on medical_products.med_ID=medicine_in_order.med_ID where medicine_in_order.order_ID=$orderID");
        }

        public function getPatientOrder(){
            $patientID=Application::$app->session->get('user');
            return $this->customFetchAll("select * from delivery left join _order on _order.delivery_ID=delivery.delivery_ID where _order.patient_ID=$patientID and _order.processing_status<>'completed'")[0];
        }
        public function getLackedItems(){
            $order=$this->getPatientOrder()['order_ID']??'';
            $medicineModel=new Medicine();
            $prescriptoinModel=new Prescription();
            $na_array=[];
            //medicine in order
            if($order){
                $items=$this->getOrderItem($order);
                foreach($items as $item){
                    if(!$medicineModel->checkStock($item['med_ID'])){
                        array_push($na_array,$medicineModel->getMedicineByID($item['med_ID']));

                    }
                    
                }
            }
            //medicine in prescription
            $prescriptions=$this->getPrescriptionsInOrder($order);
            if($prescriptions){
                foreach($prescriptions as $pres){
                    $items=$prescriptoinModel->getPrescriptionMedicine($pres['prescription_ID']);
                    if($items){
                        foreach($items as $item){
                            if(!$medicineModel->checkStock($item['med_ID'])){
                                array_push($na_array,$medicineModel->getMedicineByID($item['med_ID']));
                            }
                        }
                    }
                }
            }
            return $na_array;

        }
    }

?>