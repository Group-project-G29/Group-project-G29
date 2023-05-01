<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class AdminNotification extends DbModel{
    public string $doctor='';
    public string $content='';
    public string $command='';
    public string $channeling_ID='';
    public string $is_read='0';

   
  

  
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

    public function getNotification($type){
        return $this->fetchAssocAll(['type'=>$type]);
    }
   

    public function fileDestination(): array
    {
        return [];
    }
    public function tableName(): string
    {
        return 'admin_notification';
    }
    public function primaryKey(): string
    {
        return 'noti_ID,doctor';
    }
    public function tableRecords(): array{
        return ['admin_notification'=>['doctor','content','is_read','channeling_ID','command']];
    }

    public function attributes(): array
    {
        return ['doctor','content','is_read','channeling_ID','command'];
    }

    public function chSetNoti($channeling,$command){
        $this->doctor=Application::$app->session->get('userObject')->nic;
        $ops=$this->customFetchAll("select * from opened_channeling left join channeling on channeling.channeling_ID=opened_channeling.channeling_ID where opened_channeling.opened_channeling_ID=".$channeling);
        var_dump($ops);
        $employee=new Employee();
        $detail=$employee->fetchAssocAll(['nic'=>$this->doctor]);
        if($command=='open'){

            $this->content="Dr.".$detail[0]['name']." requested to open channeling session on ".$ops[0]['channeling_date']." at ".$ops[0]['time'];
        }
        else if($command=='cancel'){

            $this->content="Dr.".$detail[0]['name']." requested to cancel channeling session on ".$ops[0]['channeling_date']." at ".$ops[0]['time'];
        }
        else{
            
            $this->content="Dr.".$detail[0]['name']." requested to close channeling session on ".$ops[0]['channeling_date']." at ".$ops[0]['time'];

        }
        $this->command=$command;
        $this->channeling_ID=$channeling;
        return $this->save();
    }
    public function isThereNoti($channeling){
        $result=$this->fetchAssocAll(['channeling_ID'=>$channeling,'is_read'=>0]);
        if($result){
            return $result[0]['command'];
        }
        else{
            return false;
        }
    }
    public function makeRead($channeling_ID){
        $this->customFetchAll("update admin_notification set is_read=1 where is_read=0 and channeling_ID=".$channeling_ID);
    }
    public function chCancelNoti($channeling){
        if($this->isThereNoti($channeling)!='cancel'){
            $this->makeRead($channeling);
            $this->chSetNoti($channeling,'cancel');
        }
        
    }

    public function chOpenNoti($channeling){
        if($this->isThereNoti($channeling)!='open'){
            $this->makeRead($channeling);
            $this->chSetNoti($channeling,'open');
        }
    
    }
     public function chCloseNoti($channeling){

        if($this->isThereNoti($channeling)!='close'){
            $this->makeRead($channeling);
            $this->chSetNoti($channeling,'close');
        }
    }

    
}   



?>