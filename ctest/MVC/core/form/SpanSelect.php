<?php
    namespace app\core\form;
    
    use app\core\Model;

   

    class SpanSelect{
        public Model $model;
        public string $class;
        public string $id;
        public string $attribute;
        public array $options;

        public function __construct(Model $model,string $attribute,string $class,array $options,string $id=""){
            $this->model=$model;
            $this->class=$class;
            $this->options=$options;   
            $this->attribute=$attribute;
            $this->id=$id;
        }

        public function __toString(){
            $str="";
            foreach($this->options as $name=>$value){
                $str.="<option value='$value'>".ucfirst($name)."</option>";
            }
            $str='
            <tr>
            <div class="%s">
               <td>
                    <label for="%s">%s</label>
                </td>
                <td>
                    <h3 class="fs-50  fc-color--error">%s</h3>
                    <select name="%s" class="field-input--class1" id="%s">'.
                         $str
                   .'</select>
                </td>
                </div>
            </tr>';
          return sprintf($str,$this->class,$this->attribute,ucfirst($this->attribute),($this->model->errors)[$this->attribute][0] ?? '',$this->attribute,$this->id);
        }
    }



?>