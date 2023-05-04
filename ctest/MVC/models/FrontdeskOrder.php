<?php
namespace app\models;

use app\core\Application;
use app\core\DbModel;

    class FrontdeskOrder extends DbModel{
        public string $name='';
        public int $age=0;
        public string $date='';
        public string $time='';
        public string $doctor='NA';
        public string $payment_status="pending";
        public string $processing_status='pending';
        public string $contact='';
        public int $total=0;
        public int $pharmacist_ID;

        public function __construct(){

        }
        public function addFrontdeskOrder(){
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
            return 'frontdesk_order';
        }

        public function primaryKey(): string
        {
            return 'order_ID';
        }
        
        public function tableRecords(): array{
            return ['frontdesk_order'=>['name','age','date','time','doctor','payment_status','processing_status','contact','total','pharmacist_ID']];
        }

        public function attributes(): array
        {
            return ['frontdesk_order'=>['name','age','date','time','doctor','payment_status','processing_status','contact','total','pharmacist_ID']];
        }

        public function completePayment($orderID){
            //update payment status to completed
            return $this->updateRecord(['frontdesk_order'=>['order_ID'=>$orderID]],['_order'=>['payment_status'=>'completed']]);
        }

        public function addItem($order,$item,$amount,$unit_price){
            return $this->saveByName(['frontdesk_medicine'=>['order_ID'=>$order,'med_ID'=>$item,'amount'=>$amount,'current_price'=>$unit_price]]);
        }


        public function getOrderItem($orderID){
            if(!$orderID) return false;
            //create view here
            return $this->customFetchAll("SELECT * FROM frontdesk_medicine LEFT JOIN _order ON _order.order_ID=frontdesk_medicine.order_ID RIGHT JOIN medical_products ON medical_products.med_ID=medicine_in_order.med_ID WHERE medicine_in_order.order_ID=$orderID")??'';
        }

     
        //functions for front desk orders
       
        public function get_frontdesk_pending_orders() {
            return $this->customFetchAll("SELECT * FROM frontdesk_order WHERE processing_status='pending' ORDER BY date");
        }

        public function get_frontdesk_finished_orders() {
            return $this->customFetchAll("SELECT * FROM frontdesk_order WHERE processing_status='completed' ORDER BY date");
        }

        public function set_processing_status ( $order_ID, $status ) {
            return $this->customFetchAll("UPDATE frontdesk_order SET processing_status = '$status', completed_time=CURRENT_TIME, completed_date=CURRENT_DATE WHERE order_ID = $order_ID");
        }

        public function setOrderStatus($orderID,$status){
            $this->customFetchAll("UPDATE frontdesk_order SET processing_status= $status WHERE order_ID=.$orderID");
        }
        
        public function write_total ( $order_ID, $total ) {
            return $this->customFetchAll("UPDATE frontdesk_order SET total_price = $total WHERE order_ID = $order_ID;");
        }

        public function get_order_details( $order_ID ){
            return $this->customFetchAll("SELECT * FROM frontdesk_order WHERE order_ID=$order_ID");
        }

        public function get_order_medicines( $order_ID ){
            return $this->customFetchAll("SELECT *, frontdesk_medicine.amount AS order_amount, medical_products.amount AS available_amount FROM frontdesk_medicine INNER JOIN medical_products ON frontdesk_medicine.med_ID=medical_products.med_ID WHERE order_ID=$order_ID");
        }

        // public function get_frontdesk_last_order ( $name ) {
        //     return $this->customFetchAll(" SELECT * FROM _order WHERE patient_ID=$name ");
        // }
        
    }

?>