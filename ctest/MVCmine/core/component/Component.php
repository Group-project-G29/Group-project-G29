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
    public function cartview(){
        return new CartView();
    }
    public function filtersortby($link1,$link2,$array1,$array2){
        return new FilterSortBy($link1,$link2,$array1,$array2);
    }
  }
?>