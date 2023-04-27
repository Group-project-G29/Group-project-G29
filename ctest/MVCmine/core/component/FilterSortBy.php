<?php 
    namespace app\core\component;
    use app\core\Application;
    use app\models\Cart;
use app\models\Medicine;

    class FilterSortBy{
        public string $link1;
        public string $link2;
        public  $array1;
        public  $array2;
        public function __construct($link1,$link2,$array1,$array2)
        {
            $this->link1=$link1;
            $this->link2=$link2;
            $this->array1=$array1;
            $this->array2=$array2;
        }
        public function __toString()
        {   
            $filter="<option value='' selected>Sort By</option>";
            $sort="<option value='' selected>Filter By</option>";
           
            foreach($this->array1 as $name=>$value){
                $filter .= "<option value='$value' >" . ucfirst($name) . "</option>";
                
            }
            foreach($this->array2 as $name=>$value){
                $sort .= "<option value='$value' >" . ucfirst($name) . "</option>";
                
            }
            $sorter="<div class='flex'>
                        <div class='filter-by'>
                            <select id='filter-select'>
                                %s
                            </select>
                        </div>
                        <div class='sort-by'>
                            <select id='sort-select'>
                            %s
                            </select>
                        </div>
            
                    </div>";
            return sprintf($sorter,$filter,$sort);     
            
        
    }
}