<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class Prescription extends DbModel{
    public string $doctor='';
    public string $patient='';
    public string $order_ID='';
    public string $uploaded_date='';
    public string $type='';
    public string $location='';
    public string $note='';
    public string $last_processed_timestamp='';
    public string $cart_ID='';
   
  
 
    public function rules(): array
    {
        return [];
    }

    public function fileDestination(): array
    {
        return ['img'=>"media/images/patient/prescription/".$this->location];
    }
    public function tableName(): string
    {
        return 'prescription';
    }
    public function primaryKey(): string
    {
        return 'prescription_ID';
    }
    public function tableRecords(): array{

        return ['prescription'=> ['doctor','patient','order_ID','uploaded_date','type','location','note','last_processed_timestamp']];
    }

    public function attributes(): array
    {
        return  ['doctor','patient','order_ID','uploaded_date','type','location','note','last_processed_timestamp'];

    }
    public function getPrescriptionInOrder($orderID){
        return  $this->fetchAssocAll(['order_ID'=>$orderID]);
    }
    public function getPrescriptionByPatient(){
        $patient=Application::$app->session->get('user');
        return $this->customFetchAll("select * from prescription where patient=".$patient);
    }

}   
