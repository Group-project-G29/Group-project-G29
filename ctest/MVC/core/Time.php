<?php
namespace app\core;

// class to handle time formats
class Time{
 
    public function __construct()
    {
        
        
    }
    //string to int coversion "02"=>2
    public function convertToInt($str){
        $sum=0;
        foreach(str_split($str) as $int){
            $sum*=10;
            $sum+=$int;
            
        }
        
        return $sum;
    }

    public function getTime($time,$type){
        $values=explode(":",$time);
        //fix hours
        $prefix="AM";
        $hours=$this->convertToInt($values[0]);
        $minutes=$this->convertToInt($values[1]);
        if($hours>=12 ){
            $prefix="PM";
        }
        if($type=='hours'){
            return $hours;
        }
        else if($type=='minutes'){
            return $minutes;
        }
        
    }

    public function arrayToTime($array){
        $time="";
        if($array['hours']<9){
            $time.="0".$array['hours'];
        }
        else{
            $time.=$array['hours'];
        }
        $time.=":";
        if($array['minutes']<9){
            $time.="0".$array['minutes'];
        }
        else{
            $time.=$array['minutes'];
        }
        return $time;
    }

    public function addTime($time_1,$time_2):array{
        $time_1=explode(":",$time_1);
        $time_2=explode(":",$time_2);
        $day=false;
        //fix hours
        $hours_1=$this->convertToInt($time_1[0]);
        $minutes_1=$this->convertToInt($time_1[1]);
        $hours_2=$this->convertToInt($time_2[0]);
        $minutes_2=$this->convertToInt($time_2[1]);
        $minutes=0; 
        $minutes+=($minutes_1+$minutes_2);
        $carry=intdiv($minutes,60);
        $minutes%=60;
        $hours=$carry;
        $hours+=($hours_1+$hours_2);
        $tothours=$hours;
        $carry=intdiv($hours,24);
        $hours%=24;
        if($tothours>24){
            $day=true;
        }
        return ["hours"=>$hours,"minutes"=>$minutes,"day"=>$day];

    }

    public function greaterthan($time1,$time2){
     
        $month_1=$this->getTime($time1,'minutes');
        $year_1=$this->getTime($time1,'hours');
    
        $month_2=$this->getTime($time2,'minutes');
        $year_2=$this->getTime($time2,'hours');
        if($year_2-$year_1>0){
            return true;
        }
        else if($year_2-$year_1==0){
            if($month_2-$month_1>0){
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
    public function isInRange($time,$span,$intime){
        $add_time=$this->addTime($time,$span);
        if($add_time['day']){
            return false;
        }
        else{
            return $this->greaterthan($intime,$this->arrayToTime($add_time));

            
        }


    }
}

    
    





?>