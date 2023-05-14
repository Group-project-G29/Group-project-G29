<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class Medicine extends DbModel{
    public string $name='';
    public string $brand='';
    public string $strength='';
    public string $availability='A';
    public string $category='';
    public string $unit='';
    public  $unit_price=0;
    public  $amount=0;
    public string $img='';

    public $restrict_status=0;
   
    public function addMedicine(){
        if($this->amount==0){
            $this->availability="NA";
        }
        
        return parent::save();
        
    }
 
    public function rules(): array
    {
        return [
            'name'=>[self::RULE_REQUIRED],
            'brand'=>[self::RULE_REQUIRED],
            'strength'=>[self::RULE_REQUIRED],
            'category'=>[self::RULE_REQUIRED],
            'unit'=>[self::RULE_REQUIRED],
            'unit_price'=>[self::RULE_REQUIRED,self::RULE_NUMBERS],       
            'amount'=>[self::RULE_NUMBERS,self::RULE_NUMBERS],
        ];
    }

    public function getMedicineAmount($id){
        return $this->fetchAssocAll(['med_ID'=>$id])[0]['amount'];
    }

    public function reduceMedicine($id,$amount,$updateDB=false){
        //get medicine amount
        $cur_amount=$this->fetchAssocAll(['med_ID'=>$id])[0]['amount'];
        //reduce amount
        $cur_amount-=$amount;
        //if reduce amount is negative return false
        if($cur_amount<0){
            return false;
        }
        //else update table || return true;
        else if($updateDB){
            $this->customFetchAll("update medical_products set amount=$cur_amount where med_ID=$id");
            return true;
        }
        else{
            return true;
        }
    }

    public function increaseMedicine($id,$amount,$updateDB=false){
        //get medicine amount
        $cur_amount=$this->fetchAssocAll(['med_ID'=>$id])[0]['amount'];
        //increase amount
        $cur_amount+=$amount;
        if($updateDB){
            $this->customFetchAll("update medical_products set amount=$cur_amount where med_ID=$id");
            return true;
        }
        else{
            return true;
        }
    }

    public function checkStock($medicine){
        //get medicine amount if  amount=0 return false else true
        $amount=$this->getMedicineAmount($medicine);
        if($amount>0){
            return true;
        }
        else{
            return false;
        }

    }
    public function isResctricted($medicine){
        $result=$this->fetchAssocAll(['med_ID'=>$medicine,'restrict_status'=>0]);
        if($result) return true;
        else return false;
    }
    public function getAllMedicine(){
        $medicines=$this->customFetchAll("select * from medical_products");
        $medarray=[];

        foreach($medicines as $med){
            $str=$med['strength']?'-'.$med['strength']:'';
            $medarray[$med['name'].$str]=$med['name'].$str."_".$med['unit'];
        }
        return $medarray;

    }
    public function getMedicineByPage($content_amount,$page,$parameters):array{
        $medicines=$this->fetchAssocAll($parameters);
        $array=[];
        $start=$content_amount*($page-1);
        for($i=0;$i<$content_amount;$i++){
            if(!isset($medicines[$start+$i])){
                break;
            }
            $array[$i]=$medicines[$start+$i];
        }
        return $array;
    }

    public function searchMedicineByPage($content_amount,$page,$keyword):array{
        $medicines=$this->findByKeyword('name',$keyword);
        $array=[];
        $start=$content_amount*($page-1);
        for($i=0;$i<$content_amount;$i++){
            if(!isset($medicines[$start+$i])) {
                break;
            }
            $array[$i]=$medicines[$start+$i];
        }
        return $array;
    }
    public function getMedicineID($name,$strength){
        
        $result=$this->fetchAssocAll(['name'=>$name,'strength'=>$strength]);
        if($result){
            return $result[0]['med_ID'];
        }
        else{
            return false;
        }
    }
    public function getMedicineByID($ID){
        return $this->fetchAssocAll(['med_ID'=>$ID])[0]['name'];
    }
    public function fileDestination(): array
    {
        return ['img'=>"media/images/medicine/".$this->img];
    }
    public function tableName(): string
    {
        return 'medical_products';
    }
    public function primaryKey(): string
    {
        return 'medicine_ID';
    }
    public function tableRecords(): array{
        return ['medical_products'=> ['name','brand','strength','availability','category','unit','unit_price','amount','img','restrict_status']];
    }

    public function attributes(): array
    {
        return  ['name','brand','strength','availability','category','unit','unit_price','amount','img','restrict_status'];
    }

    //functions

    public function get_medicine_details( $med_ID ) {
        return $this->customFetchAll("SELECT * FROM medical_products WHERE med_ID=$med_ID");
    }

    public function select_medical_products() {
        return $this->customFetchAll("SELECT * FROM medical_products ORDER BY name ASC");
    }

    public function getMedicinePrice($med_ID){
        return $this->fetchAssocAll(['med_ID'=>$med_ID])[0]['unit_price'];
    }

    public function MedicineIDbyNameStrength($name,$strength){
        return $this->fetchAssocAll(['name'=>$name,'strength'=>$strength]);
    }
    public function isUnsedmedicine ( $med_ID ){
        $online_orders = $this->customFetchAll(" SELECT * FROM medicine_in_order WHERE med_ID = $med_ID ");
        $cart_orders = $this->customFetchAll(" SELECT * FROM medicine_in_cart WHERE med_ID = $med_ID ");
        $prescription_orders = $this->customFetchAll(" SELECT * FROM prescription_medicine WHERE med_ID = $med_ID ");

        if ( $online_orders==NULL && $cart_orders==NULL && $prescription_orders==NULL ){
            return true;
        } else {
            return false;
        }
    }

    
}   



?>