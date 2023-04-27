<?php
namespace app\core\form;
use app\core\Model;

class ReportTextArea
{
    public Model $model;
    public string $attribute="";
    public string $name="";
    public string $label="";
    public int $row=0;
    public int $col=0;
    public string $id="";
    public array $catsuggestions=[];

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
        $str = "
            <div>
                <label for='%s'>%s</label>
                <h3 class='fs-50  fc-color--error'>%s</h3>
                <textarea id='%s' name='%s' rows='%s' cols='%s'>
                    
                </textarea>
            </div> 
            <div class='suggestion-box-".$this->name."'>

            </div>";

        return sprintf($str,$this->label,$this->label,($this->model->errors)[$this->attribute][0] ?? '',$this->id,$this->name,$this->row,$this->col);
    }


}



?>
