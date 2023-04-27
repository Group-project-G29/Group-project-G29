<?php
namespace app\models;

use app\core\Application;
use app\core\DbModel;

    class Cart extends DbModel{
        public string $cart_ID="";
        public string $created_date="";
        public string $patient_ID="";

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
            return 'cart';
        }

        public function primaryKey(): string
        {
            return 'cart_ID';
        }
        
        public function tableRecords(): array{
            return ['cart'=>[]];
        }

        public function attributes(): array
        {
            return [];
        }


        public function addItem(string $itemID,string $cartID,string $amount){
            $medicineModel=new Medicine();
            //check item is in the cart
            $cart_item=$this->fetchAssocAllByName(['med_ID'=>$itemID,'cart_ID'=>$cartID],'medicine_cart');
            $unit_price=$medicineModel->getMedicinePrice($itemID);
            //update if it is in cart
            if($cart_item){
                $this->customFetchAll("update medicine_in_cart set amount=".$amount." where cart_ID=".$cartID." and med_ID=".$itemID);
                return true;
            }
            //add if it is not in cart
            else{

                $this->customFetchAll("insert into medicine_in_cart (med_ID,cart_ID,amount) values('$itemID','$cartID','$amount')");
            }

        }

        public function removeItem(string $itemID,string $cartID){
            //remove item
            $this->deleteRecordByName(['med_ID'=>$itemID,'cart_ID'=>$cartID],'medicine_in_cart');
        }

        public function getPatientCart($patientID){
            return $this->fetchAssocOne(['patient_ID'=>$patientID]);

        }
        public function getItemCount(){
            $cart=$this->getPatientCart(Application::$app->session->get('user'))[0]['cart_ID'];
            return $this->customFetchAll("select count(*) from medicine_in_cart where cart_ID=".$cart)[0]['count(*)']+$this->customFetchAll("select count(*) from prescription where cart_ID=".$cart)[0]['count(*)'];
        }
        public function getMedicinePrice($cart){
            $cartModel=new Cart();
            $medicineModel=new Medicine();
            $medicines=$cartModel->fetchAssocAllByName(['cart_ID'=>$cart],'medicine_in_cart');
            $total=0;
            foreach($medicines as $medicine){
                $med=$medicineModel->fetchAssocAll(['med_ID'=>$medicine['med_ID']])[0];
                $total=$total+$medicine['amount']*$med['unit_price'];
            }
            return $total;
        }
        public function getEpres($cart){
            $prescriptionModel=new Prescription();
            $prescriptions=$prescriptionModel->fetchAssocAll(['cart_ID'=>$cart,'type'=>'E-prescription']);
            $total=0;
            foreach($prescriptions as $prescription){
                $total=$total+$prescriptionModel->getPrice($prescription['prescription_ID']);
            }
            return $total;
        }
        public function getCartPrice(){
            $cartModel=new Cart();
            $cart=$cartModel->getPatientCart(Application::$app->session->get('user'))[0]['cart_ID'];
            $total=0;
            $total=$total+$this->getMedicinePrice($cart)+$this->getEpres($cart);
            return $total;

        }
        public function createCart($patientID){
             $this->customFetchAll("insert into cart (patient_ID) values('$patientID')");
        }
        public function getCartItem($cartID){
            return $this->fetchAssocAllByName(['cart_ID'=>$cartID],'medicine_in_cart');
        }
        //transfer item in cart to order and remove item in the cart
        //if order is a pickup $pickup_status should be true
        public function transferCartItem($cartID,$pickup_status,$deliveryModel){
            //create order
            $deliveryModel=new Delivery();
            $orderModel=new Order();
            $unavailableItems=[];
            $medicineModel=new Medicine();
            $prescriptionModel=new Prescription();
            $orderModel->pickup_status=$pickup_status;
            $orderModel->patient_ID=Application::$app->session->get('user');
            $orderModel->cart_ID=$cartID;
            if($pickup_status=='pickup'){
                $orderModel->delivery_ID=null;      
            }
            else{
                //create an delivery 
                $deliveryModel->createPIN();
                $delivery_id=$deliveryModel->save();
                $orderModel->delivery_ID=$delivery_id[0]['last_insert_id()'];
            }
            $orderID=$orderModel->save()[0]['last_insert_id()'];
            Application::$app->session->set('order_ID',$orderID);
            //get all item in cart
            //['0=>["cart_ID"=>"121"...]']
            $cartItems=$this->getCartItem($cartID);
            // ---------PIN------create a function to check whether cart has old items 
            //transfer item in to order
            foreach($cartItems as $item ){
                if(!$medicineModel->checkStock($item['med_ID'])){ 
                    array_push($unavailableItems,$medicineModel->getMedicineByID($item['med_ID']));
                    continue;
                }
                $orderModel->addItem($orderID,$item['med_ID'],$item['amount'],$medicineModel->customFetchAll("select * from medical_products where med_ID=".$item['med_ID'])[0]['unit_price']);// change here
                //reduce medicine
                $medicineModel->reduceMedicine($item['med_ID'],$item['amount'],true);
            }
            if($unavailableItems){
                $str='';
                foreach($unavailableItems as $item){
                    $str.=$item;
                    $str.=",<br> ";
                }
                Application::$app->session->setFlash('error',"Sorry, Medical Products named <br>".$str."<br> are out of stock. Remove this item from the stock to proceed.");
            }
            //check whether there is prescription in the cart
            //transfer prescription
            $prescriptionModel->addToOrder($orderID,$cartID);
            //delete all item in cart
            $this->deleteRecordByName(['cart_ID'=>$cartID],'medicine_in_cart');
            //if all items are available return true
            
            return true;
        }
        
    }

?>