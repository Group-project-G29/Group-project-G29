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
        return ['medicine'=> ['name','brand','strength','availability','category','unit','unit_price','amount','img']];
    }

    public function attributes(): array
    {
        return  ['name','brand','strength','availability','category','unit','unit_price','amount','img'];
    }

    
}   



?>