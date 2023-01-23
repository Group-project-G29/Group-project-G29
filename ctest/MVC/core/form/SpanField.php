<?php
namespace app\core\form;

use app\core\Model;

class SpanField{
    public Model $model;
    public string $attribute;
    public string $class;
    public string $type;
    public string $id;
    public function __construct(Model $model,string $attribute,string $label,string $class, string $type,string $id=""){
        $this->model=$model;
        $this->attribute=$attribute;
        $this->class=$class;
        $this->id=$id;
        $this->type=$type;
        $this->label=$label;
    }

    public function __toString(){
    return sprintf('
        <tr>
            <div class="%s" id="%s">
                
                <td>
                    <label for="%s">%s</label>
                </td>
                <td>
                    <h3 class="fs-50  fc-color--error">%s</h3>
                    <input type="%s" name="%s" value="%s"  class="field-input--class1  reg-width">
                </td>
            
            </div>
        </tr>
    
        ',$this->class,$this->id,$this->attribute,$this->label,(($this->model->errors)[$this->attribute][0]) ??' ',$this->type,$this->attribute,$this->model->{$this->attribute},$this->attribute,$this->id);
    }
}

