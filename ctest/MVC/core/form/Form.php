<?php
namespace app\core\form;
use app\core\Model;
use app\core\form\SpanSelect;
use app\core\form\TextArea;


class Form{
    public static function begin($action,$method){
        echo sprintf('<form action="%s" method="%s"  enctype="multipart/form-data">',$action,$method);
        return new Form();
        
    }
    public static function end(){
        echo '</form>';
    }
    public function field(Model $model,$attribute,$label,$class,$type,$id=""){
        return new Field($model,$attribute,$label,$class,$type,$id);
    } 
    public function select(Model $model,$name,$label,$class,$options,$id=""){
        return new Select($model,$name,$label,$class,$options,$id);
    }
    public function spanselect(Model $model,$name,$label,$class,$options,$id=""){
        return new SpanSelect($model,$name,$label,$class,$options,$id);
    }
    public function spanfield(Model $model,$attribute,$label,$class,$type,$id=""){
        return new SpanField($model,$attribute,$label,$class,$type,$id);
    }
    public function textarea(Model $model,$attribute,$name,$label,$row,$col,$id=""){
        return new TextArea($model, $attribute, $name, $label, $row, $col, $id);
    }
   

}
?>