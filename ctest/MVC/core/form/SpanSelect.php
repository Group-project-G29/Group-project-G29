<?php
    namespace app\core\form;
    
    use app\core\Model;

   

    class SpanSelect{
        public Model $model;
        public string $class;
        public string $id;
        public string $attribute;
        public array $options;
        public string $label;

        public function __construct(Model $model,string $attribute,string $label,string $class,array $options,string $id)
        {
            $this->model=$model;
            $this->class=$class;
            $this->options=$options;   
            $this->attribute=$attribute;
            $this->id=$id;
            $this->label = $label;
        }
        public function __toString(){
            $str="";
            $array = [];
            $i=0;
            foreach($this->options as $name=>$value){
           
                if ($this->model->{$this->attribute} && strcmp($this->model->{$this->attribute},$value)) {
                    $str .= "<option value='$value' >" . ucfirst($name) . "</option>";
                }
                else{
                    $str .= "<option value='$value' selected>" . ucfirst($name) . "</option>";
                }
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
          return sprintf($str,$this->class,$this->label,ucfirst($this->label),($this->model->errors)[$this->attribute][0] ?? '',$this->attribute,$this->id);
        }
    }



?>