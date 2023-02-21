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

        public function getOrderItem($orderID){
            //create view here
            return $this->customFetchAll("SELECT * FROM medicine_in_order LEFT JOIN _order ON _order.order_ID=medicine_in_order.order_ID RIGHT JOIN medical_products ON medical_products.med_ID=medicine_in_order.med_ID WHERE medicine_in_order.order_ID=$orderID");
        }

        public function getPatientOrder(){
            $patientID=Application::$app->session->get('user');
            return $this->customFetchAll("SELECT * FROM delivery LEFT JOIN _order ON _order.delivery_ID=delivery.delivery_ID WHERE _order.patient_ID=$patientID AND _order.processing_status<>'completed'")[0];
        }


        //functions for orders

        public function get_pending_orders() {
            return $this->customFetchAll("SELECT * FROM _order INNER JOIN patient ON _order.patient_ID = patient.patient_ID WHERE _order.processing_status = 'pending' ORDER BY created_date ASC");
        }

        public function view_order_details( $order_ID ) {
            return $this->customFetchAll("SELECT patient.patient_ID, patient.name AS p_name, patient.age, patient.contact, patient.gender, patient.address, 
            _order.order_ID, _order.pickup_status, _order.created_date, _order.processing_status, _order.created_time, 
            medicine_in_order.amount, medical_products.med_ID, medical_products.name, medical_products.brand, medical_products.strength, medical_products.unit_price 
            FROM medical_products INNER JOIN medicine_in_order ON medicine_in_order.med_ID=medical_products.med_ID INNER JOIN _order ON _order.order_ID=medicine_in_order.order_ID INNER JOIN patient ON _order.patient_ID=patient.patient_ID WHERE medicine_in_order.order_ID = $order_ID");
        }

        public function set_processing_status ( $order_ID, $status ) {
            return $this->customFetchAll("UPDATE _order SET processing_status = '$status' WHERE order_ID = $order_ID");
        }

        public function get_processing_orders() {
            return $this->customFetchAll("SELECT * FROM _order INNER JOIN patient ON _order.patient_ID = patient.patient_ID WHERE _order.processing_status = 'processing' ORDER BY created_date ASC");
        }

        public function get_delivering_orders() {
            return $this->customFetchAll("SELECT * FROM _order INNER JOIN patient ON _order.patient_ID = patient.patient_ID WHERE _order.processing_status = 'packed' ORDER BY created_date ASC");
        }

        public function get_postal_code( $order_ID ) {
            return $this->customFetchAll("SELECT delivery.postal_code, _order.order_ID, delivery.delivery_ID FROM delivery INNER JOIN _order ON delivery.delivery_ID = _order.delivery_ID WHERE _order.order_ID = $order_ID");
        }

        

    }

?>