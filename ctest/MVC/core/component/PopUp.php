<?php
   
    namespace app\core\component;
    
    use app\core\Model;
    
    class PopUp{
        public array $item;
        public string $value;
        public string $valuestyle;
        public string  $style;
        public string $id;
        public function __construct($value,$valuestyle,$style,$id='yes')
        {
            $this->value=$value;
            $this->valuestyle=$valuestyle;
            $this->style=$style;
            $this->id=$id;
        }
        public function __toString()
        {
            $str="
            
                <div class='%s' id='popup-main'>
                    <div class='%s'>
                        %s
                    </div>
                    <div class='popup-button-section'>
                        <button id='%s' class='popup-button'>Yes</button>
                        <button id='cancel'>Cancel</button>
                    </div>
            
            </div>".
            "<script>
                
                bgo=document.querySelector('.bg');
                const div=document.getElementById('popup-main');  
                const yes=document.getElementById('yes');
                const cancel=document.getElementById('cancel');
                cancel.addEventListener('click',()=>{
                    div.style.display='none';
                    bgo.classList.remove('background');
                    
                });
              
            </script>";
            
            return sprintf($str,$this->style,$this->valuestyle,$this->value,$this->id);
        }
    }
    ?>
    
    
    
    
    
    



