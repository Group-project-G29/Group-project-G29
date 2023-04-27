<?php
    /*calender test */
    namespace app\core;

use app\core\Calendar as CoreCalendar;
use app\models\OpenedChanneling;

    class Date{
        public string $date;
        public string $day;
        
        public function  _construct($date,$day){
            $this->date=$date;
            $this->day=$day;
        }
    
        public function getdate(){
            return $this->date;
        }
        
    
        public function get($date,$type){
            $list=explode("-",$date);
          
            switch($type){
                case 'day':
                    if($list[2][0]=="0"){
                        return $list[2][1];
                    }
                    else{
                        return $list[2];
                    }
                case 'month':
                    if($list[1][0]=="0"){
                        return $list[1][1];
                    }
                    else{
                        return $list[1];
                    }
                case 'year':
                    return $list[0];
    
            }
        }
        
        public function isLeap($date){
            if($this->get($date,'year')%4==0){
                return true;
            }
            else{
                return false;
            }
        }
    
        public function arrayToDate($array){
            $day=$array[0];
            $month=$array[1];
            if(strlen($array[0])==1){
                $day="0".$array[0];
                
            }
            if(strlen($array[1])==1){
                $month="0".$array[1];
            }
            return  $array[2]."-".$month."-".$day;
        }
        
    
     
       
       
    
    }
class Calendar{
    public array $months=['1'=>31,'2'=>28,'2L'=>29,'3'=>31,'4'=>30,'5'=>'31','6'=>30,'7'=>31,'8'=>30,'9'=>31,'10'=>30,'11'=>31,'12'=>30];
  
      public function findDateByDay($startdate,$startday,$findday){
       
           
          $date=new Date();
          $array=['Monday'=>1,'Tuesday'=>2,'Wednesday'=>3,'Thursday'=>4,'Friday'=>5,'Saturday'=>6,'Sunday'=>7];
          $i=$array[$startday];
          $day=$date->get($startdate,'day');
          
          $month=$date->get($startdate,'month');
          
          $year=$date->get($startdate,'year');
      
          //how many days should be added
          $difference=$array[$startday]-$array[$findday];
          $add_days=($difference<0)?$day+abs($difference):$day+7-abs($difference);
        
          if($add_days>$this->months[$month]){
              if($month=='12'){
                  $month=1;
                  $year+=1;
                  return [(string)$add_days-$this->months[12],(string)$month,(string)$year];
              }
              return [(string)$add_days-$this->months[$month],(string)$month+1,$year];
              
          }
          else{
            
            return [(string)$add_days,$month,$year];
          }
        
  
      } 
      
  
  }
      
    
    class Tester{
        public $class;
        public $test_cases;
        public $result;
    
        public function __construct($object){
            $this->class=$object;
        }
    
      
    
        public function set_test_cases($array){
            $this->test_cases=$array;
        }
        public function set_test_result($array){
            $this->result=$array;
        }
    
        public function execute(){
            $success=true;
            foreach(get_class_methods($this->class) as $method){
              
                if(!array_key_exists($method,$this->test_cases)){
                  echo $method."\nis not in test cases \n";
                  $success=false;
                }
                if(empty($this->test_cases[$method])) continue;
                $input_array=[];
                $i=0;
                foreach($this->test_cases[$method] as $paramset){
                if(is_array($paramset )){
                    $input_array=$paramset;                
                }
                else{
                    array_push($input_array,$paramset);
                }
                 
                    
                        $a=call_user_func_array([$this->class,$method],$input_array);
                        $b=($this->result)[$method][$i];
                        if($a==$b){
                            echo "\npass\n";
                        }
                        else{
                            echo "\nMethod ".$method." failed on input".print_r($this->test_cases[$method])." test gave".print_r($a)."\n";
                            $success=false; 
                        }
                        $i++;
                    
                 }
                    
                }
            
            if($success) echo "\nAll tests are successful\n";
            else echo "\nFailed tests\n";
        }
    
    
    }
    
    $new=new Calendar();
    $test1=new Tester($new);
    $test1->set_test_cases(['findDateByDay'=>[["2022-07-04","Monday","Sunday"],["2020-12-28","Sunday","Monday"]]]);
    $test1->set_test_result(['findDateByDay'=>[["10","7","2022"],["29","12","2020"]]]);
    $test1->execute();

?>
