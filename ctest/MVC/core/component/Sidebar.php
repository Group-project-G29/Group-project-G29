<?php
namespace app\core\component;

use app\core\Model;

class Sidebar{
    public array $item;
    public string $select;
    public function __construct($item,$select)
    {
        $this->item=$item;
        $this->select=$select;
    }
    public function __toString()
    {
        $str='';
        foreach($this->item as $name=>$link){
            if($name==$this->select){
                $str.= "<div class='sidebar_grid-item sidebar-selected' id=$link>
                        <h4>$name</h4>
                    </div>";    
            }
            else{
                $str.= "<div class='sidebar_grid-item' id=$link>
                         <h4>$name</h4>
                        </div>";
            }
        }
        $str="<div class='sidebar'><div class='sidebar_grid-container'>".$str."</div> </div>
        <script>
            let elementsArray = document.querySelectorAll(\".sidebar_grid-item\");
            elementsArray.forEach(function(elem) {
                elem.addEventListener(\"click\", function() {
                    location.href=elem.id;
                });
            });
        </script>";
        return sprintf($str);
    }
}
?>





