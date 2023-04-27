<?php
namespace app\core\component;

use app\core\Model;

class SearchBar{
    public $model;
    public string $placeholder;
    public string $attribute;
    public string $class;
    public string $id;

    public function __construct($model,string $attribute,string $class,string $placeholder,string $id="")
    {
        $this->model=$model;
        $this->attribute=$attribute;
        $this->class=$class;
        $this->placeholder=$placeholder;
        $this->id=$id;
   
    }
    
    public function __toString()
    {
        return sprintf('
       
        <div class="%s" >
            <input type="text" name="%s" placeholder="%s"  class="field-input--class1  reg-width" id="%s">
            <button id="b%s">Search</button>
        </div>
    
        ',$this->class,$this->attribute,$this->placeholder,$this->id,$this->id);
    }
}




?>