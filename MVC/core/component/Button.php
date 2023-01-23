<?php
namespace app\core\component;

use app\core\Model;

class Button{
    public string $name;
    public string $value;
    public string $class;
    public string $id;

    public function __construct(string $name,string $type,string $value,string $class,string $id="")
    {
        $this->name=$name;
        $this->value=$value;
        $this->class=$class;
        $this->id=$id;
        $this->type=$type;
   
    }
    
    public function __toString()
    {
        return sprintf('<button name="%s" type="%s" class="%s" id="%s">
                            %s
                        </button>
    
        ',$this->name,$this->type,$this->class,$this->id,$this->value);
    }
}




?>