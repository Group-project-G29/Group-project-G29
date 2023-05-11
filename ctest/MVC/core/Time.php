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
    //getTime(03:45,'hours') will return int(3)
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
    //arrayToTime(['hours'=>3,'minutes'=>45]) will return 03:45
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
    // add two time in the format 10:45 +02:03   => 12:48
    public function addTime($time_1,$time_2){
        $time_2=$this->getTime($time_2,'hours')*60+$this->getTime($time_2,'minutes');;
        // $time_1=explode(":",$time_1);
        // $time_2=explode(":",$time_2);
        // $day=false;
        // //fix hours
        // $hours_1=$this->convertToInt($time_1[0]);
        // $minutes_1=$this->convertToInt($time_1[1]);
        // $hours_2=$this->convertToInt($time_2[0]);
        // $minutes_2=$this->convertToInt($time_2[1]);
        // $minutes=0; 
        // $minutes+=($minutes_1+$minutes_2);
        // $carry=intdiv($minutes,60);
        // $minutes%=60;
        // $hours=$carry;
        // $hours+=($hours_1+$hours_2);
        // $tothours=$hours;
        // $carry=intdiv($hours,24);
        // $hours%=24;
        // if($tothours>24){
        //     $day=true;
        // }
        // return ["hours"=>$hours,"minutes"=>$minutes,"day"=>$day];
        $date = $time_1;
        $newtime = date('H:i:s', strtotime($date. ' +'.$time_2.' minutes'));
        return $newtime;

  


    }
    public function subTime($time_1,$time_2){
        $time_2=$this->getTime($time_2,'hours')*60+$this->getTime($time_2,'minutes');;
        // $time_1=explode(":",$time_1);
        // $time_2=explode(":",$time_2);
        // $day=false;
        // //fix hours
        // $hours_1=$this->convertToInt($time_1[0]);
        // $minutes_1=$this->convertToInt($time_1[1]);
        // $hours_2=$this->convertToInt($time_2[0]);
        // $minutes_2=$this->convertToInt($time_2[1]);
        // $minutes=0; 
        // $minutes+=($minutes_1+$minutes_2);
        // $carry=intdiv($minutes,60);
        // $minutes%=60;
        // $hours=$carry;
        // $hours+=($hours_1+$hours_2);
        // $tothours=$hours;
        // $carry=intdiv($hours,24);
        // $hours%=24;
        // if($tothours>24){
        //     $day=true;
        // }
        // return ["hours"=>$hours,"minutes"=>$minutes,"day"=>$day];
        $date = $time_1;
        $newtime = date('H:i:s', strtotime($date. ' -'.$time_2.' minutes'));
        return $newtime;

  


    }

    //time1<time2 will return true
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
        $sub_time=$this->subTime($time,$span);
        var_dump($sub_time);

        if($this->greaterthan($sub_time,$intime) && $this->greaterthan($intime,$add_time)){
                return true;
            
                
                
            }
        return false;


    }

    public function time_format($time){
        $time_array = explode(':',$time);
        if($time_array[0]>22){
            $formatted_time = ($time_array[0]-12).':'.$time_array[1].' PM';
        } else if($time_array[0]>=12){
            $formatted_time = '0'.($time_array[0]-12).':'.$time_array[1].' PM';
        } else if ($time_array[0]>=10) {
            $formatted_time = ($time_array[0]).':'.$time_array[1].' AM';
        } else {
            $formatted_time = '0'.($time_array[0]).':'.$time_array[1].' AM';
        }
        return $formatted_time;
    }
}

    
    





?>