<?php 
    namespace app\core\component;
    use app\core\Application;
    use app\models\Cart;
use app\models\Medicine;
use app\models\Prescription;

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
            $prescriptionModel=new Prescription();
            $items= $cartModel->fetchAssocAllByName(['cart_ID'=>$cartModel->getPatientCart(Application::$app->session->get('user'))[0]['cart_ID']],'medicine_cart');
            $stritem="";
            $prescriptions=$prescriptionModel->fetchAssocAllByName(['cart_ID'=>$cartModel->getPatientCart(Application::$app->session->get('user'))[0]['cart_ID']],'prescription');
            foreach($prescriptions as $pres){
                $stritem.="<div>
                                <h5>Added Date:".$pres['uploaded_date']."</h5>
                                <div class='cart-item'><a href=#>".$pres['type']."</a><a href='patient-pharmacy?spec=prescription&cmd=remove&id=".$pres['prescription_ID']."'>x</a></div>
                        </div>";
            }
            foreach($items as $item){
                if($medicineModel->checkStock($item['med_ID'])){
                    $stritem.= '
                    <div class="cart-item">
                            <img src="./media/images/medicine/'.$item["img"].'">
                            <div class="scrollable-body">
                                <h3 class="fs-50">'.$item["name"]." ".$item["strength"]." ".$item["unit"].'</h3>
                                <input type="number" id='.'"'."amount2_".$item['med_ID'].'"'.' value='.$item['amount'].'>
                                '.$component->button('update','','Change Amount','update-buttons-cart','cartbtn_'.$item['med_ID'])
                                .'<a href='."'patient-pharmacy?spec=medicine&cmd=delete&item=".$item['med_ID']."'".'><font class="fs-50">X</font></a>'.
                            '</div>
                    </div>';
                }
                else{
                    $stritem.= '
                    <div class="cart-item">
                            <img src=./media/images/medicine/'.$item["img"].'>
                            <div class="scrollable-body">
                                <h3 class="fs-50">'.$item["name"]." ".$item["strength"]." ".$item["unit"].'</h3>
                                <div class="flex">
                                <h3 color="red">Out of stock</h3>
                                <a href='."'patient-pharmacy?spec=medicine&cmd=delete&item=".$item['med_ID']."'".'><font class="fs-50">X</font></a>'.
                            '</div>
                            </div>
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
                <div class="cart-content"> <div class="cart--wrapper">'.
                    $component->button("","","Proceed to Payment","cart-payment-button",'proceed-to-payment')."</hr>".$stritem

                .'</div></div>
                </section> 
                <script src="./media/js/main.js"></script>
            <script>
               const updateButtons=e(".update-buttons-cart","classall");
                updateButtons.forEach((elem)=>{
                    elem.addEventListener("click",()=>{
                        let element=(""+elem.id).split("_")[1];
                        let input_amount=e("amount2_"+element);
                        let amount=input_amount.value;
                        if(!amount){
                            
                        }
                        else{
                            location.href="patient-pharmacy?spec=medicine&cmd=add&item="+element+"&amount="+amount;
                        }
                    })
                })
                const paymentbtn=e("proceed-to-payment");
                paymentbtn.addEventListener("click",()=>{
                    location.href="patient-medicine-order?spec=order&mod=view";
                })
            </script>
            ',$cartModel->getItemCount(),$this->count);     
            
        
    }
    
}
