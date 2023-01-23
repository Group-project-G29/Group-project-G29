<?php
namespace app\models;
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
            //check item is in the cart
            $cart_item=$this->fetchAssocAllByName(['med_ID'=>$itemID,'cart_ID'=>$cartID],'medicine_cart');
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
        public function createCart($patientID){
            $this->customFetchAll("insert into cart (patient_ID) values('$patientID')");
        }
        
    }

?>