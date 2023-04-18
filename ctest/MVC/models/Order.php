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
            return $this->customFetchAll("SELECT * FROM medicine_in_order LEFT JOIN _order ON _order.order_ID=medicine_in_order.order_ID RIGHT JOIN medical_products ON medical_products.med_ID=medicine_in_order.med_ID WHERE medicine_in_order.order_ID=$orderID");
        }

     
        //functions for orders

        public function get_previous_orders() {
            return $this->customFetchAll("SELECT * FROM _order INNER JOIN patient ON _order.patient_ID = patient.patient_ID WHERE _order.processing_status IN ('pickedup','deleted') ORDER BY created_date ASC");
        }

        public function get_pending_orders() {
            return $this->customFetchAll("SELECT * FROM _order INNER JOIN patient ON _order.patient_ID = patient.patient_ID WHERE _order.processing_status IN ('pending','waiting','rejected','accepted') ORDER BY created_date ASC");
        }
        
        public function get_processing_orders() {
            return $this->customFetchAll("SELECT * FROM _order INNER JOIN patient ON _order.patient_ID = patient.patient_ID WHERE _order.processing_status = 'processing' ORDER BY created_date ASC");
        }
        
        public function get_packed_orders() {
            return $this->customFetchAll("SELECT * FROM _order INNER JOIN patient ON _order.patient_ID = patient.patient_ID WHERE _order.processing_status = 'packed' ORDER BY created_date ASC");
        }
        
        public function view_previous_online_order_details( $order_ID ) {
            return $this->customFetchAll("SELECT 
            patient.patient_ID, patient.name AS p_name, patient.age, patient.contact, patient.gender, patient.address, 
            _order.order_ID, _order.pickup_status, _order.completed_date, _order.processing_status, _order.completed_time, 
            medicine_in_order.amount AS order_amount, medicine_in_order.order_current_price AS current_price,
            medical_products.med_ID, medical_products.name, medical_products.brand, medical_products.strength, medical_products.unit_price, medical_products.amount AS available_amount 
            FROM medical_products INNER JOIN medicine_in_order ON medicine_in_order.med_ID=medical_products.med_ID INNER JOIN _order ON _order.order_ID=medicine_in_order.order_ID INNER JOIN patient ON _order.patient_ID=patient.patient_ID WHERE medicine_in_order.order_ID = $order_ID");
        }

        public function view_previous_prescription__order_details( $order_ID ) {
            return $this->customFetchAll(" SELECT 
            patient.patient_ID, patient.name AS p_name, patient.age, patient.contact, patient.gender, patient.address, 
            _order.order_ID, _order.pickup_status, _order.completed_date, _order.processing_status, _order.completed_time, 
            prescription_medicine.amount, prescription_medicine.prescription_current_price AS current_price,
            medical_products.med_ID, medical_products.name, medical_products.brand, medical_products.strength, medical_products.unit_price 
                        
            FROM patient INNER JOIN _order ON patient.patient_ID=_order.patient_ID
            INNER JOIN prescription ON _order.order_ID=prescription.order_ID 
            INNER JOIN prescription_medicine ON prescription.prescription_ID=prescription_medicine.prescription_ID 
            INNER JOIN medical_products ON prescription_medicine.med_ID=medical_products.med_ID
            
            WHERE _order.order_ID=$order_ID; ");
        }

        public function view_online_order_details( $order_ID ) {
            return $this->customFetchAll("SELECT 
            patient.patient_ID, patient.name AS p_name, patient.age, patient.contact, patient.gender, patient.address, 
            _order.order_ID, _order.pickup_status, _order.created_date, _order.processing_status, _order.created_time, _order.payment_status,
            medicine_in_order.amount AS order_amount, medicine_in_order.order_current_price AS current_price,
            medical_products.med_ID, medical_products.name, medical_products.brand, medical_products.strength, medical_products.unit_price, medical_products.amount AS available_amount 
            FROM medical_products INNER JOIN medicine_in_order ON medicine_in_order.med_ID=medical_products.med_ID INNER JOIN _order ON _order.order_ID=medicine_in_order.order_ID INNER JOIN patient ON _order.patient_ID=patient.patient_ID WHERE medicine_in_order.order_ID = $order_ID");
        }

        public function view_prescription_details( $order_ID ) {
            return $this->customFetchAll(" SELECT 
            patient.patient_ID, patient.name AS p_name, patient.age, patient.contact, patient.gender, patient.address, 
            _order.order_ID, _order.pickup_status, _order.created_date, _order.processing_status, _order.created_time, _order.payment_status,
            prescription_medicine.amount AS order_amount, 
            medical_products.med_ID, medical_products.name, medical_products.brand, medical_products.strength, medical_products.unit_price, medical_products.amount AS available_amount, 
            prescription.prescription_ID, prescription_medicine.prescription_current_price AS current_price      
            FROM patient INNER JOIN _order ON patient.patient_ID=_order.patient_ID
            INNER JOIN prescription ON _order.order_ID=prescription.order_ID 
            INNER JOIN prescription_medicine ON prescription.prescription_ID=prescription_medicine.prescription_ID 
            INNER JOIN medical_products ON prescription_medicine.med_ID=medical_products.med_ID
            
            WHERE _order.order_ID=$order_ID; ");
        }

        public function set_processing_status ( $order_ID, $status ) {
            return $this->customFetchAll("UPDATE _order SET processing_status = '$status', completed_time=CURRENT_TIME, completed_date=CURRENT_DATE WHERE order_ID = $order_ID");
        }

        // public function pick_up_order ( $order_ID, $status ) {
        //     return $this->customFetchAll("UPDATE _order SET processing_status = '$status', completed_date=CURRENT_DATE, completed_time=CURRENT_TIME WHERE order_ID = $order_ID");
        // }
        
        public function get_postal_code( $delivery_ID ) {
            //error in sql
            return $this->customFetchAll("SELECT delivery.postal_code, _order.order_ID, delivery.delivery_ID, _order.pickup_status FROM delivery INNER JOIN _order ON delivery.delivery_ID = delivery.delivery_ID WHERE delivery.delivery_ID = $delivery_ID");
        }

        public function getOrderType( $order_ID ) {
            $online_order = $this->customFetchAll(" SELECT * FROM medicine_in_order WHERE order_ID = $order_ID; ");
            $e_prescription = $this->customFetchAll(" SELECT * FROM prescription WHERE order_ID = $order_ID AND type = 'ep'; ");
            $soft_copy_prescription = $this->customFetchAll(" SELECT * FROM prescription WHERE order_ID = $order_ID AND type = 'sf'; ");

            if ( sizeof($online_order)>0 ){
                return 'Online Order';
            } else if ( sizeof($e_prescription)>0 ){
                return 'E-prescription';
            } else if ( sizeof($soft_copy_prescription)>0 ){
                return 'Softcopy-prescription';
            }

        }

        public function getOrderByID($orderID) {
            return $this->customFetchAll("SELECT * FROM _order WHERE order_ID = $orderID");
        }

        public function getPatientOrder(){
            $patientID=Application::$app->session->get('user');
            return $this->customFetchAll("select * from delivery right join _order on _order.delivery_ID=delivery.delivery_ID where _order.patient_ID=$patientID and _order.processing_status<>'completed'")[0];
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
                    if(!$medicineModel->checkStock($item['med_ID']) && ($item['processing_status']=='pending' || $item['processing_status']=='processing' ) ){
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
        public function setOrderStatus($orderID,$status){
            $this->customFetchAll("update _order set processing_status="."'".$status."'"." where order_ID=".$orderID);
        }
    }

?>