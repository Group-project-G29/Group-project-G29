<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\Email;
use app\core\UserModel;

class PatientNotification extends DbModel{
    public ?string $type='';
    public ?string $text='';
    public ?string $patient_ID="";
    public ?string $order_ID="";
    public int $is_read=0;
   
  

  
    public function rules(): array
    {
        return [
            // 'name'=>[self::RULE_REQUIRED],
            //'nic'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>12],[self::RULE_MAX,'max'=>15],[self::RULE_UNIQUE,'attribute'=>'nic','tablename'=>'employee']],
            // 'age'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>0],[self::RULE_MAX,'max'=>120]],
            // 'contact'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>10]],
            // 'email'=>[self::RULE_EMAIL],
            // 'address'=>[],       
            // 'role'=>[self::RULE_REQUIRED],
            // 'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8],[self::RULE_MATCH,'retype'=>($this->cpassword)]]
           


        ];
    }
    public function channelingCancelNoti($openedchanneling){
        $results=$this->customFetchAll("select * from opened_channeling left join appointment on appointment.opened_channeling_ID=opened_channeling.opened_channeling_ID where appointment.opened_channeling_ID=".$openedchanneling);
        $doctor=$this->customFetchAll("select employee.name from channeling left join opened_channeling on channeling.channeling_ID=opened_channeling.channeling_ID left join employee on employee.nic=channeling.doctor where opened_channeling.opened_channeling_ID=".$openedchanneling)[0]['name'];

        foreach($results as $result){
            $emailModel=new Email();
            $this->type="channeling cancellation";
            $this->text="Sorry,Dr.".$doctor." channeling session on ".$result['channeling_date']." has been cancelled";
            $this->patient_ID=$result['patient_ID'];
            // $email=$this->customFetchAll("select email from patient where patient_ID=".$result['patient_ID'])[0]['email'];
            // $str="
            //     <section>
            //         ".$this->text."
            //     </section>
            // ";
            // $emailModel->sendemail=$email;
            // $emailModel->subject="Cancelled Channeling Session";
            // $emailModel->body=$str;
            // $emailModel->sendEmail();
            $this->order_ID=null;
            $this->savenofiles();
        }
    }
    public function getNotification($type){
        return $this->fetchAssocAll(['type'=>$type]);
    }
   
    public function createNotifications($patient,$content,$type,$order){
        $this->type=$type;
        $this->text=$content;
        $this->patient_ID=$patient;
        $this->order_ID=$order;
        $this->is_read=0;
        return $this->save();

    }
    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'patient_notification';
    }
    public function primaryKey(): string
    {
        return 'noti_ID';
    }
    public function tableRecords(): array{
        return ['patient_notification'=>['noti_ID','type','patient_ID','text','is_read','order_ID']];
    }

    public function attributes(): array
    {
        return ['noti_ID','type','patient_ID','text','is_read','order_ID'];
    }

    public function createOrderNotification( $orderID, $patientID ) {
        return $this->customFetchAll("INSERT INTO patient_notification (created_date_time, type, text, patient_ID, order_ID, is_read) VALUES (CURRENT_TIMESTAMP, 'order', 'Notify NA Medical products', $patientID, $orderID, 0);");
    }

    public function getNotificationIDs( $orderID ) {
        return $this->customFetchAll("SELECT noti_ID FROM patient_notification WHERE order_ID = $orderID AND is_read=0");
    }

    public function removeNotifications ( $notificationID ) {
        return $this->customFetchAll("update patient_notification set is_read=1 WHERE noti_ID = $notificationID");
    }
    public function getNotifcationCount(){
        $result=$this->fetchAssocAll(['patient_ID'=>Application::$app->session->get('user'),'is_read'=>0]);
        if(!$result) return 0;
        else return count($result);
    }
    
}   



?>