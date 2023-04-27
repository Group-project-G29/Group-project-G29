<?php 
namespace app\models;

use app\core\DbModel;
use app\core\Application;
use app\core\UserModel;

class PharmacyAdvertisement extends DbModel {
    public string $title='';
    public string $description='';
    public string $remark='';
    public string $type='Pharmacy';
    public string $img='';

    public function change_image($imgName){
        $this->img = $imgName;
    }
    
    public function addAdvertisement(){
        echo "phamacy";exit;
        return parent::save();
    }
 
    public function rules(): array {
        return [
            'title'=>[self::RULE_REQUIRED],
            'description'=>[self::RULE_REQUIRED],
            'remark'=>[self::RULE_REQUIRED],
        ];
    }

    public function fileDestination(): array {
        return ['img'=>"media/images/advertisements/pharmacy/".$this->img];
    }

    public function deleteImage($imgName){
        $path = "media/images/advertisements//pharmacy/".$imgName;
        unlink($path);
    }

    public function tableName(): string {
        return 'advertisement';
    }

    public function primaryKey(): string {
        return 'ad_ID';
    }

    public function tableRecords(): array {
        return ['advertisement'=> ['title','description','remark','type','img']];
    }

    public function attributes(): array {
        return  ['title','description','remark','type','img'];
    }

    public function get_selected_advertisement_details( $ad_ID ) {
        return $this->customFetchAll("SELECT * FROM advertisement WHERE ad_ID=$ad_ID");
    }

    public function select_pharmacy_advertisements( $ad_ID ) {
        return $this->customFetchAll("SELECT * FROM advertisement WHERE type='Pharmacy' ORDER BY name ASC");
    }

    public function get_advertisements() {
        return $this->customFetchAll("SELECT * FROM advertisement WHERE type='Pharmacy' ORDER BY title ASC");
    }
}   



?>