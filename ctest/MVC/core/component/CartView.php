<?php 
    namespace app\core\component;
    use app\core\Application;
    use app\models\Cart;
use app\models\Medicine;

    class CartView{
        public int $count;
        public function __construct()
        {
            $cartModel=new Cart();
            $this->count=$cartModel->getItemCount();
        }
        public function __toString()
        {   
            $component=new Component();
            $cartModel=new Cart();
            $medicineModel=new Medicine();
            $items= $cartModel->fetchAssocAllByName(['cart_ID'=>$cartModel->getPatientCart(Application::$app->session->get('user'))[0]['cart_ID']],'medicine_cart');
            $stritem="";
            foreach($items as $item){
                if($medicineModel->checkStock($item['med_ID'])){
                    $stritem.= '
                    <div class="cart-item">
                            <img src="./media/images/medicine/'.$item["img"].'">
                            <div class="scrollable-body">
                                <h3 class="fs-50">'.$item["name"]." ".$item["strength"]." ".$item["unit"].'</h3>
                                <input type="number" id='.'"'."amount2_".$item['med_ID'].'"'.' value='.$item['amount'].'>
                                '.$component->button('update','','Change Amount','update-buttons','cartbtn_'.$item['med_ID'])
                                .'<a href='."patient-pharmacy?spec=medicine&cmd=delete&item=".$item['med_ID'].'><h3 class="fs-50">X</h3></a>'.
                            '</div>
                    </div>';
                }
                else{
                    $stritem.= '
                    <div class="cart-item">
                            <img src=<?="./media/images/medicine/"'.$item["img"].'?>>
                            <div class="scrollable-body">
                                <h3 class="fs-50">'.$item["name"]." ".$item["strength"]." ".$item["unit"].'</h3>
                                <h3 color="red">Out of stock</h3>'.'>
                                '.'<a href='."patient-pharmacy?spec=medicine&cmd=delete&item=".$item['med_ID'].'><h3 class="fs-50">Remove</h3></a>'.
                            '</div>
                    </div>';
                }
            }
            return sprintf('
            <section class="hover-cart">
                <div class="cart-container cart" id="$s">
                    <img src="./media/images/patient/cart.png">
                </div>
                <div class="cart-counter">
                    <h6>%s</h6>
                </div>
                <div class="cart-content">'.
                    $stritem.$component->button("","","Process to Patyment","",'proceed-to-payment')

                .'</div>
                </section> 
            <script>
                const cart=document.querySelectorAll(".cart");
                cart.addEventListener("click",()=>{
                    location.href="#";
                });
            </script>
            ',$cartModel->getItemCount(),$this->count);     
            
        
    }
}