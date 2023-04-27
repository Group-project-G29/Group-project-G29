<?php 
    namespace app\core\component;

    class Cart{
        public int $count;
        public function __construct($count)
        {
            $this->count=$count;
        }
        public function __toString()
        {
            return sprintf('<section>
                <div class="cart-container" id="cart">
                    <img src="./media/images/patient/cart.png">
                </div>
                <div class="cart-counter">
                    <h6>%s</h6>
                </div>
            </section> ',$this->count);     
            
        }
    }

?>