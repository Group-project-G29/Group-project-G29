<?php
namespace app\models;

use app\core\Application;
use app\core\DbModel;

    class Order extends DbModel{
        public string $pickup_status="";
        public string $patient_ID="";
        public ?string $cart_ID="";
        public ?string $delivery_ID="";
        public string $payment_status="pending"; //pending,completed
        public string $processing_status="pending";
        public string $name='';
        public string $address='';
        public string $contact='';
        public int $total_price=0;

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
            return ['_order'=>['pickup_status','patient_ID','cart_ID','delivery_ID','payment_status','processing_status','name','address','contact','total_price']];
        }

        public function attributes(): array
        {
            return ['_order'=>['pickup_status','patient_ID','cart_ID','delivery_ID','payment_status','processing_status','name','address','contact','total_price']];
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

        public function addItem($order,$item,$amount,$unit_price){
            return $this->saveByName(['medicine_in_order'=>['order_ID'=>$order,'med_ID'=>$item,'amount'=>$amount,'order_current_price'=>$unit_price,'status'=>"'include'"]]);
        }

        public function getPrescriptionsInOrder($order){
            return $this->fetchAssocAllByName(['order_ID'=>$order],'prescription');
        }


        public function getOrderItem($orderID){
            if(!$orderID) return false;
            //create view here
            return $this->customFetchAll("SELECT * FROM medicine_in_order LEFT JOIN _order ON _order.order_ID=medicine_in_order.order_ID RIGHT JOIN medical_products ON medical_products.med_ID=medicine_in_order.med_ID WHERE medicine_in_order.order_ID=$orderID")??'';
        }

        public function isAllPriceSet($order_ID){
            $orderModel=new Order();
            $prescriptoinModel=new Prescription();
            $prescriptions=$orderModel->getPrescriptionsInOrder($order_ID);
            foreach($prescriptions as $prescription){
                $pres=$prescriptoinModel->fetchAssocAll(['prescription_ID'=>$prescription['prescription_ID']])[0]['total_price'];
                if($pres){
                    return true;
                }
                else{
                    return false;
                }
            }
            
        }
        //functions for orders

        public function get_previous_orders() {
            return $this->customFetchAll("SELECT *, _order.name AS ordered_person FROM _order INNER JOIN patient ON _order.patient_ID = patient.patient_ID WHERE _order.processing_status IN ('pickedup','deleted') ORDER BY created_date ASC");
        }

        public function get_pending_orders() {
            return $this->customFetchAll("SELECT *, _order.name AS ordered_person FROM _order INNER JOIN patient ON _order.patient_ID = patient.patient_ID WHERE _order.processing_status IN ('pending','waiting','rejected','accepted') ORDER BY created_date ASC");
        }
        
        public function get_processing_orders() {
            return $this->customFetchAll("SELECT *, _order.name AS ordered_person FROM _order INNER JOIN patient ON _order.patient_ID = patient.patient_ID WHERE _order.processing_status = 'processing' ORDER BY created_date ASC");
        }
        
        public function get_packed_orders() {
            return $this->customFetchAll("SELECT *, _order.name AS ordered_person FROM _order INNER JOIN patient ON _order.patient_ID = patient.patient_ID WHERE _order.processing_status = 'packed' ORDER BY created_date ASC");
        }
        
        public function get_pickedup_orders() {
            return $this->customFetchAll("SELECT *, _order.name AS ordered_person FROM _order INNER JOIN patient ON _order.patient_ID = patient.patient_ID WHERE _order.processing_status='pickedup'");
        }
       
        // public function get_frontdesk_orders() {
        //     return $this->customFetchAll("SELECT * FROM _frontdesk_order ORDER BY date");
        // }

        public function set_processing_status ( $order_ID, $status ) {
            return $this->customFetchAll("UPDATE _order SET processing_status = '$status', completed_time=CURRENT_TIME, completed_date=CURRENT_DATE WHERE order_ID = $order_ID");
        }

        // public function pick_up_order ( $order_ID, $status ) {
        //     return $this->customFetchAll("UPDATE _order SET processing_status = '$status', completed_date=CURRENT_DATE, completed_time=CURRENT_TIME WHERE order_ID = $order_ID");
        // }
        
        public function get_postal_code( $order_ID ) {
            //error in sql
            return $this->customFetchAll("SELECT delivery.postal_code, _order.order_ID, delivery.delivery_ID, _order.pickup_status FROM delivery INNER JOIN _order ON delivery.delivery_ID = _order.delivery_ID WHERE _order.order_id = $order_ID");
        }

      

        public function getOrderByID($orderID) {
            return $this->customFetchAll("SELECT * FROM _order WHERE order_ID = $orderID");
        }

        public function getPatientOrder($that=false){
            $patientID=Application::$app->session->get('user');
            $reuslt=$this->customFetchAll("select * from delivery right join _order on _order.delivery_ID=delivery.delivery_ID where _order.patient_ID=$patientID and _order.processing_status<>'pickedup'");
            if($that){
                if($reuslt){
                    return $reuslt[0];
                }
                else return '';

            }
            else{
                 $od=$this->customFetchAll("select * from delivery right join _order on _order.delivery_ID=delivery.delivery_ID where _order.patient_ID=$patientID and _order.processing_status<>'pickedup' and _order.processing_status<>'deleted' and _order.processing_status<>'rejected'");
                 if($od){
                    return $od[0];
                 }
                 $result=$this->customFetchAll("select * from delivery right join _order on _order.delivery_ID=delivery.delivery_ID where _order.patient_ID=$patientID and _order.processing_status='pickedup' order by _order.order_ID desc");
                 if($result) return $result[0];
                 else return '';
            }
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
        public function view_previous_online_order_details( $order_ID ) {
            return $this->customFetchAll("SELECT 
            patient.patient_ID, patient.name AS p_name, patient.age, patient.contact, patient.gender, patient.address, 
            _order.order_ID, _order.pickup_status, _order.completed_date, _order.processing_status, _order.completed_time, 
            medicine_in_order.amount AS order_amount, medicine_in_order.order_current_price AS current_price, medicine_in_order.status
            medical_products.med_ID, medical_products.name, medical_products.brand, medical_products.strength, medical_products.unit_price, medical_products.amount AS available_amount 
            FROM medical_products INNER JOIN medicine_in_order ON medicine_in_order.med_ID=medical_products.med_ID INNER JOIN _order ON _order.order_ID=medicine_in_order.order_ID INNER JOIN patient ON _order.patient_ID=patient.patient_ID WHERE medicine_in_order.order_ID = $order_ID");
        }

        public function view_previous_prescription__order_details( $order_ID ) {
            return $this->customFetchAll(" SELECT 
            patient.patient_ID, patient.name AS p_name, patient.age, patient.contact, patient.gender, patient.address, 
            _order.order_ID, _order.pickup_status, _order.completed_date, _order.processing_status, _order.completed_time, 
            prescription_medicine.total_med_amount, prescription_medicine.prescription_current_price AS current_price, prescription_medicine.status,
            medical_products.med_ID, medical_products.name, medical_products.brand, medical_products.strength, medical_products.unit_price 
                        
            FROM patient INNER JOIN _order ON patient.patient_ID=_order.patient_ID
            INNER JOIN prescription ON _order.order_ID=prescription.order_ID 
            INNER JOIN prescription_medicine ON prescription.prescription_ID=prescription_medicine.prescription_ID 
            INNER JOIN medical_products ON prescription_medicine.med_ID=medical_products.med_ID
            
            WHERE _order.order_ID=$order_ID; ");
        }

        public function view_online_order_details( $order_ID ) {
            return $this->customFetchAll("SELECT  
            medicine_in_order.amount AS order_amount, medicine_in_order.order_current_price AS current_price, medicine_in_order.status,
            medical_products.med_ID, medical_products.name, medical_products.brand, medical_products.strength, medical_products.unit_price, medical_products.amount AS available_amount 
            FROM medical_products INNER JOIN medicine_in_order ON medicine_in_order.med_ID=medical_products.med_ID INNER JOIN _order ON _order.order_ID=medicine_in_order.order_ID WHERE medicine_in_order.order_ID = $order_ID AND medicine_in_order.status='include' ");
        }

        public function view_prescription_details( $prescription_ID ) {
            return $this->customFetchAll(" SELECT 
            patient.patient_ID, patient.name AS p_name, patient.age, patient.contact, patient.gender, patient.address, 
            _order.order_ID, _order.pickup_status, _order.created_date, _order.processing_status, _order.created_time, _order.payment_status,
            prescription_medicine.total_med_amount AS order_amount, 
            medical_products.med_ID, medical_products.name, medical_products.brand, medical_products.strength, medical_products.unit_price, medical_products.amount AS available_amount, 
            prescription.prescription_ID, prescription_medicine.prescription_current_price AS current_price, prescription_medicine.status      
            FROM patient INNER JOIN _order ON patient.patient_ID=_order.patient_ID
            INNER JOIN prescription ON _order.order_ID=prescription.order_ID 
            INNER JOIN prescription_medicine ON prescription.prescription_ID=prescription_medicine.prescription_ID 
            INNER JOIN medical_products ON prescription_medicine.med_ID=medical_products.med_ID
            WHERE prescription.prescription_ID=$prescription_ID ");
        }
        public function take_ep_orders( $orderID ){
            return $this->customFetchAll("SELECT * FROM prescription WHERE order_ID = $orderID AND type='E-prescription'");
            
        }

        public function take_sf_orders( $orderID ){
            return $this->customFetchAll("SELECT * FROM prescription WHERE order_ID = $orderID AND type='softcopy prescription'");
            
        }
        public function get_order_details ( $order_ID ) {
            return $this->customFetchAll(" SELECT * FROM _order WHERE order_ID = $order_ID ");
        }

        public function write_total ( $order_ID, $total ) {
            return $this->customFetchAll("UPDATE _order SET total_price = $total WHERE order_ID = $order_ID;");
        }

        public function reset_total ( $order_ID ) {
            return $this->customFetchAll(" UPDATE _order SET total_price=0 WHERE order_ID = $order_ID; ");
        }

        public function update_payment_status ( $order_ID ) {
            return $this->customFetchAll(" UPDATE _order SET payment_status='completed' WHERE order_ID = $order_ID; ");
        }

        // public function get_frontdesk_last_order ( $name ) {
        //     return $this->customFetchAll(" SELECT * FROM _order WHERE patient_ID=$name ");
        // }
    }

?>