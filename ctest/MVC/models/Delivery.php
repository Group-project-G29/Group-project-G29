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
                'city'=>[self::RULE_REQUIRED]
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
        
    }

?>