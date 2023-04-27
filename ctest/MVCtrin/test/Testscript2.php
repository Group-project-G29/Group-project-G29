<?php

use app\core\test\Tester;

include "./Tester.php";

// class to handle time formats
class Time{
    public string $time;
    public function __construct(string $time)
    {
        $this->time=$time;
        
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

    public function getTime($time):array{
        $values=explode(":",$time);
        //fix hours
        $prefix="AM";
        $hours=$this->convertToInt($values[0]);
        $minutes=$this->convertToInt($values[1]);
        if($hours>=12 ){
            $prefix="PM";
        }
        return [$hours,$minutes,$prefix];
    }

    public function addTime($time_2):array{
        $time_1=$this->time;
        $time_1=explode(":",$time_1);
        $time_2=explode(":",$time_2);
        //fix hours
        $hours_1=$this->convertToInt($time_1[0]);
        $minutes_1=$this->convertToInt($time_1[1]);
        $hours_2=$this->convertToInt($time_2[0]);
        $minutes_2=$this->convertToInt($time_2[1]);
        $minutes=0;
        $hours=0;
        $minutes+=($minutes_1+$minutes_2);
        $carry=intdiv($minutes,60);
        $minutes%=60;
        $hours+=$carry;
        $hours+=($hours_1+$hours_2);
        $carry=intdiv($hours,24);
        $hours%=24;
        return ["days"=>$carry,"hours"=>$hours,"minutes"=>$minutes];

    }
    

}

    $new=new Time("12:30");
    $test1=new Tester($new);
    $test1->set_test_cases(['getTime'=>[["03:40"]],'addTime'=>[[1,20]],'convertToInt'=>[]]);
    $test1->set_test_result(['getTime'=>[[3,40,"AM"]],'addTime'=>[["days"=>0,"hours"=>1,"minutes"=>50]]]);
    $test1->execute();
    

?>