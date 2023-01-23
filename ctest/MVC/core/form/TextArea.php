<?php
namespace app\core\form;
use app\core\Model;

class TextArea
{
    public Model $model;
    public string $attribute;
    public string $name;
    public string $label;
    public int $row;
    public int $col;
    public string $id;

    public function __construct($model,$attribute,$name, $label, $row, $col, $id)
    {
        $this->model = $model;
        $this->name = $name;
        $this->label = $label;
        $this->row = $row;
        $this->col = $col;
        $this->id = $id;
        $this->attribute = $attribute;
    }

    public function __toString()
    {
        $str = "<label for='%s'>%s</label>
        <h3 class='fs-50  fc-color--error'>%s</h3>
        <textarea id='%s' name='%s' rows='%s' cols='%s'>
        
        </textarea> ";

        return sprintf($str,$this->label,$this->label,($this->model->errors)[$this->attribute][0] ?? '',$this->id,$this->name,$this->row,$this->col);
    }


}



?>
