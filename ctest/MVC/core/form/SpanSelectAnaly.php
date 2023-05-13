<?php
    namespace app\core\form;
    
    use app\core\Model;

   

    class SpanSelectAnaly{
        public string $class;
        public string $id;
        public array $options;
   

        public function __construct(string $class,array $options,string $id)
        {
       
            $this->class=$class;
            $this->options=$options;     
            $this->id=$id;
          
        }
        public function __toString(){
            $str="";
            $array = [];
            $i=0;
            foreach($this->options as $name=>$value){
           
             
                    $str .= "<option value='$value' >" . ucfirst($name) . "</option>";
                
             
            }

            $str='
        
            <div class="%s">
            
            
    
                    <select name="%s" class="field-input--class1" id="%s">'.
                         $str
                   .'</select>
                </div>
            ';
            
          return sprintf($str,$this->class,'span',$this->id);
        }
    }



?>