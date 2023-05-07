<?php
namespace app\models;

use app\core\Application;
use app\core\DbModel;

    class Delivery extends DbModel{
        public string $contact="";
        public string $address="";
        public string $delivery_rider="";
        public string $PIN="";
        public string $postal_code="";
        public string $name="";
        public string $city="";
        public string $comment="";


        public function __construct(){

        }
        public function rules(): array
        {
            return [
                'contact'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>10]],
                'address'=>[self::RULE_REQUIRED],
                'postal_code'=>[self::RULE_REQUIRED],
                'name'=>[self::RULE_REQUIRED,[self::RULE_CHARACTER_VALIDATION,'regex'=>"/^[a-z ,.'-]+$/i",'attribute'=>'name']],
                'city'=>[self::RULE_REQUIRED],
                'PIN'=>[self::RULE_PIN_VALIDATION]
            ]; 
        }
        public function fileDestination(): array
        {
            return [];
        }

        public function tableName(): string
        {
            return 'delivery';
        }

        public function primaryKey(): string
        {
            return 'delivery_ID';
        }
        
        public function tableRecords(): array{
            return ['delivery'=>['contact','address','postal_code','name','city','comment','PIN']];
        }

        public function attributes(): array
        {
            return ['contact','address','postal_code','name','city','comment','PIN'];
        }
        public function setAttributes($contact,$address,$delivery_rider,$PIN,$postal_code,$name,$comment){
            $this->contact=$contact;
            $this->address=$address;
            $this->delivery_rider=$delivery_rider;
            $this->PIN=$PIN;
            $this->postal_code=$postal_code;
            $this->PIN=$PIN;
            $this->name=$name;
            $this->comment=$comment;
        }
        public function createPIN(){
            
            $this->PIN= "".(rand(0,9)).(rand(0,9)).(rand(0,9)).(rand(0,9));
        }
        
// functions

    public function get_unfinished_deliveries( $emp_ID ) {
        return $this->customFetchAll("SELECT * FROM delivery INNER JOIN _order ON delivery.delivery_ID=_order.delivery_ID WHERE delivery.completed_date IS NULL AND delivery.delivery_rider = $emp_ID");
    }

    public function get_finished_deliveries( $emp_ID ) {
        return $this->customFetchAll("SELECT * FROM delivery WHERE completed_date IS NOT NULL AND delivery_rider = $emp_ID");
    }

    public function view_delivery_details( $delivery_ID ) {
        return $this->customFetchAll("SELECT * FROM delivery INNER JOIN _order ON delivery.delivery_ID = _order.delivery_ID WHERE delivery.delivery_ID = $delivery_ID");
    }

    public function get_processing_delivery( $delivery_ID ) {
        return $this->customFetchAll("SELECT * FROM delivery INNER JOIN _order ON delivery.delivery_ID = _order.delivery_ID WHERE delivery.delivery_ID = $delivery_ID");
    }

    public function update_completed_date_time_delivery( $delivery_ID ) {
        return $this->customFetchAll("UPDATE delivery SET completed_date = CURRENT_TIMESTAMP, completed_time = CURRENT_TIMESTAMP  WHERE delivery_ID = $delivery_ID");
    }

    public function update_processing_status_order( $delivery_ID ) {
        return $this->customFetchAll("UPDATE _order SET processing_status='pickedup' WHERE delivery_ID = $delivery_ID");
    }

    public function update_rider_ID( $delivery_ID, $delivery_rider ) {
        return $this->customFetchAll("UPDATE delivery SET delivery_rider = $delivery_rider WHERE delivery_ID = $delivery_ID");
    }

    public function set_delivery_without_rider( $delivery_ID ) {
        return $this->customFetchAll("UPDATE delivery SET delivery_rider = NULL WHERE delivery_ID = $delivery_ID");
    }

    public function get_null_rider_deliveries() {
        //completed date time
        return $this->customFetchAll("SELECT * FROM delivery INNER JOIN _order On _order.delivery_ID=delivery.delivery_ID WHERE delivery.delivery_rider IS NULL AND _order.processing_status='packed';");
        // return $this->customFetchAll("SELECT * FROM delivery WHERE delivery_rider IS NULL");
    }

    public function get_nearby_deliveries( $postal_code ) {
        return $this->customFetchAll("SELECT * FROM delivery INNER JOIN _order WHERE delivery.delivery_rider IS NULL AND _order.processing_status='packed' AND delivery.postal_code BETWEEN $postal_code-10 AND $postal_code+10");
    }


    }

?>