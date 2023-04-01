<?php
namespace app\core;
class Date{
    public string $date;
    public string $day;
    
    public function  _construct($date="0000-00-00",$day='Monday'){
        $this->date=$date;
        $this->day=$day;
    }

    public function getdate(){
        return $this->date;
    }
    
    public function addDate($date1,$date2){
        
    }
    public function get($date,$type){
        $list=explode("-",$date);
  
        switch($type){
            case 'day':
                if($list[2][0]=="0"){
                    return $list[2][1];
                }
                else{
                    return $list[2];
                }
            case 'month':
                if($list[1][0]=="0"){
                    return $list[1][1];
                }
                else{
                    return $list[1];
                }
            case 'year':
                return $list[0];

        }
    }
    
    public function isLeap($date){
        if($this->get($date,'year')%4==0){
            return true;
        }
        else{
            return false;
        }
    }

    public function arrayToDate($array){
      if(isset($array[4]) && $array[4]=='-') return $array;
        $day=$array[0];
        $month=$array[1];
        if(strlen($array[0])==1){
            $day="0".$array[0];
            
        }
        if(strlen($array[1])==1){
            $month="0".$array[1];
        }
        return  $array[2]."-".$month."-".$day;
    }
    //true if $time1<$time2
    public function greaterthan($time1,$time2){
        $day_1=$this->get($time1,'day');
        $month_1=$this->get($time1,'month');
        $year_1=$this->get($time1,'year');
        $day_2=$this->get($time2,'day');
        $month_2=$this->get($time2,'month');
        $year_2=$this->get($time2,'year');
        if($year_2-$year_1>0){
            return true;
        }
        else if($year_2-$year_1==0){
            if($month_2-$month_1>0){
                return true;
            }
            else if($month_2-$month_1==0){
                if($day_2-$day_1>0){
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }


    }
}
 

  

   
   


?>