<?php
namespace app\core\component;
use app\core\Model;
use app\core\form\SpanSelect;


class Component{
    
    public function searchbar($model,$attribute,$class,$placeholder="",$id=""){
        return new SearchBar($model,$attribute,$class,$placeholder,$id);
    } 
    public function button($name,$type,$value,$class,$id=""){
        return new Button($name,$type,$value,$class,$id);
    }
    public function popup($value,$valuestyle,$style,$id){
        return new PopUp($value,$valuestyle,$style,$id);
    }
  }
?>