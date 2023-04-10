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

    // =========CREATE NEW ORDER===============

    public function add_med_rec ($med_ID, $prescription_ID, $amount) {
        return $this->customFetchAll(" INSERT INTO prescription_medicine ( med_ID, prescription_ID, amount ) VALUES ( $med_ID, $prescription_ID, $amount ); ");
    }

    public function get_curr_orders($prescription_ID) {
        return $this->customFetchAll("SELECT *, prescription_medicine.amount AS order_amount, medical_products.amount AS available_amount FROM prescription_medicine INNER JOIN prescription ON prescription_medicine.prescription_ID=prescription.prescription_ID INNER JOIN medical_products ON prescription_medicine.med_ID=medical_products.med_ID WHERE prescription_medicine.prescription_ID=$prescription_ID; ");
    }

    public function get_patient_details($prescription_ID) {
        return $this->customFetchAll("SELECT * FROM prescription INNER JOIN patient ON prescription.patient=patient.patient_ID WHERE prescription.prescription_ID=$prescription_ID;");
    }

    public function get_prescription_location( $order_ID ) {
        return $this->customFetchAll(" SELECT *, patient.name AS p_name FROM patient INNER JOIN _order ON patient.patient_ID=_order.patient_ID INNER JOIN prescription ON _order.order_ID=prescription.order_ID WHERE prescription.order_ID=$order_ID ");
    }
}   
