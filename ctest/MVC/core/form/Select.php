<?php
    namespace app\core\form;
    
    use app\core\Model;

    class Select{
        public Model $model;
        public string $class;
        public string $id;
        public string $attribute;
        public array $options;

        public function __construct(Model $model,string $attribute,string $class,array $options,string $id)
        {
            $this->model=$model;
            $this->class=$class;
            $this->options=$options;   
            $this->attribute=$attribute;
            $this->id=$id;
        }
        public function __toString()
        {
            $str="";
            foreach($this->options as $option){
                $str.="<option value='$option'>".ucfirst($option)."</option>";
            }
            $str='<div class="%s" id=%s>
                    <div class="field-upper_text">
                        <label for="%s">%s</label>
                        <h3 class="fs-50  fc-color--error">%s</h3>
                    </div>
                   <select name="%s" class="field-input--class1" id="%s">'.
                         $str
                   .'</select>
                  </div>';
            return sprintf($str,$this->class,$this->attribute,$this->attribute,ucfirst($this->attribute),($this->model->errors)[$this->attribute][0] ?? '',$this->attribute,$this->id);
        }
    }

    // class SpanSelect{
    //     public Model $model;
    //     public string $class;
    //     public string $id;
    //     public string $attribute;
    //     public array $options;

    //     public function __construct(Model $model,string $attribute,string $class,array $options,string $id){
    //         $this->model=$model;
    //         $this->class=$class;
    //         $this->options=$options;   
    //         $this->attribute=$attribute;
    //         $this->id=$id;
    //     }

    //     public function __toString(){
    //         $str="";
    //         foreach($this->options as $option){
    //             $str.="<option value='$option'>".ucfirst($option)."</option>";
    //         }
    //         $str='<div class="%s">
    //             <h3 class="fs-50  fc-color--error">%s</h3>
    //                 <div class="field-upper_text">
    //                    <label for="%s" class="fc-color--dark">%s</label>
    //                    <select name="%s" class="field-input--class1" id="%s">'.
    //                      $str
    //                .'</select>
    //                 </div>
    //             </div>';
    //       return sprintf($str,$this->class,($this->model->errors)[$this->attribute][0] ?? '',$this->attribute,ucfirst($this->attribute),$this->attribute,$this->id);
    //     }
    // }



?>