<?php
    namespace app\core\form;
    
    use app\core\Model;

   

    class EditableSelect{
   
        public string $class;
        public array $options;
        public string $label;
        public string $name;
        public string $id;

        public function __construct(string $name,string $label,string $class,array $options)
        {
  
            $this->class=$class;
            $this->options=$options;   
            $this->label = $label;
            $this->name=$name;
            
        }
        public function __toString(){
            $str="";
            $array = [];
            $i=0;
        
            foreach($this->options as $name=>$value){
                $str .= "<div class='ed-se-item-".$this->name." hide edse' id='".$value."'>".$name."</div>";
                
            }

            $str='<div class="edsecon">
                <label>%s  </label><input type="text" name="%s" id="input-%s" class="sl-%s in">
                <div class="ed-se-item-container-%s %s edse-container">
                    '.$str.'
                </div>
            </div>
            <script>
                // take the field element
                //onchange if empty hide all items
                 items%s=document.querySelectorAll(".ed-se-item-%s");
                 text_input%s=document.querySelector(".sl-%s");
                text_input%s.addEventListener("input",()=>{
                        var re=new RegExp(("^"+text_input%s.value).toLowerCase())
                        items%s.forEach(element => {
                            if(re.test(element.id.toLowerCase())){ 
                                element.classList.remove("hide");
                                text_input%s.classList.remove("border-red");
                            }
                            else{
                                text_input%s.classList.add("border-red");
                                element.classList.add("hide");
                            }
                            
                            
                        });
                        if(text_input%s.value.length==0) 
                        items%s.forEach(element => {
                            if(re.test(text_input%s.value.toLowerCase())) 
                            element.classList.add("hide");
                            
                            
                        });
                    });
                    let %scarry="";
                    items%s.forEach(element=>{ 
                        element.addEventListener("click",()=>{
                        comp%s=(""+element.id).split("_");
                        text_input%s.value=comp%s[0];
                        if(comp%s.length==2) %scarry=comp%s[1];
                        items%s.forEach(element=>{
                            element.classList.add("hide");
                        })
                    })
                })
            </script>
        
        ';
          return sprintf($str,$this->label,$this->name,$this->name,$this->name,$this->name,$this->class,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name,$this->name);
        }
    }



?>