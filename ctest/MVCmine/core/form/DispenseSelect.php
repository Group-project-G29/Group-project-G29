<?php
    namespace app\core\form;
    
    use app\core\Model;

   

    class DispenseSelect{
   
        public string $class;
        public string $label;
        public string $name;

        public function __construct(string $name,string $label,string $class)
        {
  
            $this->class=$class;
            $this->label = $label;
            $this->name=$name;
        }
        public function __toString(){
            $array = [];
            $i=0;
            

            $str='<div>
                <label>%s</label><input type="text" name="%s" id="input" class="sl in">
                <div class="ed-se-item-container hide %s">
                    <div class="ed-se-item" id="days">
                        days
                    </div>
                    <div class="ed-se-item" id="week">
                        weeks
                    </div>
                    <div class="ed-se-item" id="months">
                        months
                    </div>
                    
                </div>
            </div>
            <script>
                // take the field element
                //onchange if empty hide all itemsd
                let itemsd=document.querySelectorAll(".ed-se-item");
                let text_inputd=document.querySelector(".sl");
                let container=document.querySelector(".ed-se-item-container");
                text_inputd.addEventListener("input",()=>{
                        itemsd.forEach(element => {
                            container.classList.remove("hide");
                            
                            
                        });
                        if(text_inputd.value.length==0) 
                        itemsd.forEach(element => {
                            container.classList.add("hide");
                            
                            
                        });
                    });
                    itemsd.forEach(element=>{ 
                    element.addEventListener("click",()=>{
                        text_inputd.value=text_inputd.value.split(" ")[0]+" "+element.id;
                        itemsd.forEach(element=>{
                            itemsd.classList.add("hide");
                        })
                    })
                })
            </script>
        
        ';
          return sprintf($str,$this->label,$this->name,$this->class);
        }
    }



?>