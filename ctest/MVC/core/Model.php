<?php 
namespace app\core;

abstract class Model{
    public const RULE_REQUIRED='required';
    public const RULE_EMAIL='email';
    public const RULE_MIN='min';
    public const RULE_MAX='max';
    public const RULE_MATCH='match';
    public const RULE_UNIQUE='attribute';
    public const RULE_FILE_TYPE="type";
    public const RULE_MAX_FILE_SIZE="max_size";
    public const RULE_CHARACTER_VALIDATION="regex";
    public const RULE_PASSWORD_VALIDATION="regexx";
    public const RULE_DATE_VALIDATION="date";
    public const RULE_NUMBERS='num';
    public const RULE_PIN_VALIDATION="pin_confirm";
    public const RULE_TIME="time";

    public const RULE_INCOMPLETE_PAYMENT="asa";


    public function loadData($data){
        foreach($data as $key=>$value){
         
            if(property_exists($this,$key)){
                $this->{$key}=$value;
            }
        }

    }
    //Input $_FILES 
    public function loadFiles($files){
        
        
        foreach($files as $key=>$value){
            if(property_exists($this,$key)){

                $this->{$key}=uniqid().$value['name'];
                
            }

        }
    }
    public function updateData($data,$fileDestination){
       
        foreach($data[0] as $key=>$value){
            if(property_exists($this,$key) && $value!=NULL){
                if(array_key_exists($key,$fileDestination)){
                    $_FILE[$key]=file_get_contents($fileDestination[$key].$value);
                    $this->{$key}=$value;
                }
                else{
                    $this->{$key}=$value;

                }
            }

        }
        return true;
    }

    abstract public function rules():array;

    public array $errors=[];
    public  function validate(){
        
        foreach($this->rules() as $attribute=>$rules){
            $value=$this->{$attribute};
            $time=new Time();
            foreach($rules as $rule){
                $ruleName=$rule;
                if(!is_string($ruleName)){
                    $ruleName=$rule[0];
                }
                if($ruleName===self::RULE_REQUIRED && (!$value||$value=="select"||$value==0||$value=='')){
                    $this->addError($attribute,self::RULE_REQUIRED);

                }
                if($ruleName===self::RULE_MIN && strlen($value)<$rule['min']){
                    $this->addError($attribute,self::RULE_MIN,$rule);
                }
                if($ruleName==self::RULE_MAX && strlen($value)>$rule['max']){
                    $this->addError($attribute,self::RULE_MAX,$rule);
                }
                if($ruleName==self::RULE_EMAIL && !filter_var($value,FILTER_VALIDATE_EMAIL)){
                    $this->addError($attribute,self::RULE_EMAIL);
                }
                if($ruleName==self::RULE_MATCH && strcmp($value,$rule['retype'])){
               
                    $this->addError($attribute,self::RULE_MATCH);
                }
                if($ruleName==self::RULE_UNIQUE){
                    $tablename=$rule['tablename'];
                    $attribute=$rule['attribute'];
                    $statement=Application::$app->db->prepare("SELECT * FROM $tablename WHERE $attribute= :attr");
                    $statement->bindValue(":attr",$value);
                    $statement->execute();
                    $record=$statement->fetchObject();
                    if($record){
                        $this->addError($attribute,self::RULE_UNIQUE,['attribute'=>$attribute]);
                    }
                }
                if($ruleName==self::RULE_MAX_FILE_SIZE && $_FILES[$attribute]['size'] >$rule['max_size']){
                        $this->addError($attribute,self::RULE_MAX_FILE_SIZE);
                }
                if($ruleName==self::RULE_CHARACTER_VALIDATION && !(preg_match($rule['regex'],$value))){
                    $this->addError($attribute,self::RULE_CHARACTER_VALIDATION,['attribute'=>$rule['attribute']]);
                }  
                if($ruleName==self::RULE_PASSWORD_VALIDATION && !(preg_match($rule['regex'],$value))){
                    $this->addError($attribute,self::RULE_PASSWORD_VALIDATION);
                }
                if($ruleName==self::RULE_DATE_VALIDATION ){
                    $dateModel=new Date();
                    
                    if(!$value){
                        $this->addError($attribute,self::RULE_DATE_VALIDATION);
                    }
                    
                    else if($dateModel->greaterthan($value,date("Y-m-d"))){
                        $this->addError($attribute,self::RULE_DATE_VALIDATION);
                    }
                }

                if($ruleName==self::RULE_NUMBERS && !(preg_match("/^[1-9][0-9]*$/",$value))){
                    $this->addError($attribute,self::RULE_NUMBERS);
                }

                if($ruleName==self::RULE_PIN_VALIDATION ){

                }
                if($ruleName==self::RULE_TIME && $value<$time->addTime(Date('H:i'),'00:30')){
                    $this->addError($attribute,self::RULE_TIME);
                    
                    
                }

                // if($ruleName==self::RULE_INCOMPLETE_PAYMENT ){

                // }

            }
        }
        
        return empty($this->errors);
    }
    public function customAddError(string $attribute,string $error){
        $this->errors[$attribute][]=$error;
    }
    public function addError(string $attribute,string $rule,$params=[]){
        $message=$this->errorMessages()[$rule] ?? '';
        foreach($params as $key=>$value){
            $message=str_replace("{{$key}}",$value,$message);

        }
        $this->errors[$attribute][]=$message;
    }
    public function errorMessages(){
        return [
            self::RULE_REQUIRED=>"This field is required",
            self::RULE_EMAIL=>"This must be a valid email address",
            self::RULE_MIN=>"Min length of this field must be {min}",
            self::RULE_MAX=>"Max length of this field must be {max}",
            self::RULE_MATCH=>"Passwords should match",
            self::RULE_UNIQUE=>"{attribute} already exists",
            self::RULE_MAX_FILE_SIZE=>"Maximum size of the file should be {max_size}",
            self::RULE_CHARACTER_VALIDATION=>"Wrong {attribute}",
            self::RULE_PASSWORD_VALIDATION=>"Password should be at least length 8</br>
                                            and should contain</br>
                                            at least one upper case and lower case letter</br>
                                            at least one number</br>
                                            at least one special character
                                            ",
            self::RULE_DATE_VALIDATION=>"Passsed date is chosen",
            self::RULE_NUMBERS=>"This field should be numeric type",
            self::RULE_PIN_VALIDATION=>"Incorrect Pin",
            self::RULE_TIME=>"Wrong time"
            // self::RULE_INCOMPLETE_PAYMENT=>"Payment not done"
        ];
    }
    public function hasError($attribute){
        return $this->errors[$attribute] ?? false;

    }

    public function getFirstError($attribute){
        return $this->errors[$attribute][0]?? false;
    }
}

?>