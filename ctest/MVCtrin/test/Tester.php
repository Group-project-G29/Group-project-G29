<?php
namespace app\core\test;

use Exception;
use TypeError;

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
          
            if($method!="__construct" && !array_key_exists($method,$this->test_cases)){
              echo $method."is not in test cases \n";
              $success=false;
            }
            if(empty($this->test_cases[$method])) continue;
            $input_array=[];
            $i=0;
            foreach($this->test_cases[$method] as $inputs){
                    if(is_array($inputs)){
                        foreach($inputs as $input){
                            array_push($input_array,$input);
                        }
                    }
                    else{
                        array_push($input_array,$inputs);
                    }
             
                    try{
                        $a=call_user_func_array([$this->class,$method],$input_array);
                        $b=($this->result)[$method][$i];
                    }
                    catch(TypeError){
                        echo "TypeErro";
                        $success=false;
                    }
                    if($a===$b){
                        echo "true pass";
                    }
                    else{
                        echo "Method ".$method." failed on input ".$input." output results is ".var_dump($a)."\n";
                        $success=false; 
                    }
                
                
                
            }
            $i++;
        }
        if($success) echo "All tests are successful";
        else echo "Failed tests";
    }


}







?>

