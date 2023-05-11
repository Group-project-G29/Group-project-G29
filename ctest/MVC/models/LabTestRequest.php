<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\Request;
use app\core\UserModel;

class LabTestRequest extends DbModel{

    public string $name='';
    public int $patient_ID=0;
    public int $doctor=0;
    public string $note="";
    public string $status="";
   
    public function addTest(){
        parent::save();
    }
 
    public function rules(): array{
        return [];
    }

    public function fileDestination(): array{
        return [];
    }

    public function tableName(): string
    {
        return 'lab_request';
    }
    public function primaryKey(): string
    {
        return 'name';
    }
    public function tableRecords(): array
    {
        return ['lab_request'=> ['name','patient_ID','doctor','note','status']];
    }

    public function attributes(): array
    {
        return  ['name','patient_ID','doctor','note','status'];
    }

    public function getLabTestRequests(){
        $doctor=Application::$app->session->get('userObject')->nic;
        $patient=Application::$app->session->get('cur_patient');
        return $this->fetchAssocAll(['doctor'=>$doctor,'patient_ID'=>$patient,'status'=>'pending']);
    }
    public function createLabTestRequest($model){
        
        $model->patient_ID=Application::$app->session->get('cur_patient');
        $model->doctor=Application::$app->session->get('userObject')->nic;
        $model->status='pending';
        return $model->savenofiles();
    }
    public function isThereTest($name){
        $result=$this->fetchAssocAll(['name'=>$name]);
        if($result){
            return true;
        }
        else{
            return false;
        }
    }
    public function isExist($name){
        $patientID=Application::$app->session->get('cur_patient');
        $doctor=Application::$app->session->get('userObject')->nic;
        $result=$this->fetchAssocAll(['patient_ID'=>$patientID,'name'=>$name,'doctor'=>$doctor,'status'=>'pending']);
        if($result){ 
            $this->deleteRecord(['request_ID'=>$result[0]['request_ID']]);    
            return true;
        }
        else return false;
    }
    

    
}   



?>
