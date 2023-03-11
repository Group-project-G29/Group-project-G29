<?php

namespace app\core;
use app\core\Date;

class Calendar{
  public array $months=['1'=>31,'2'=>28,'2L'=>29,'3'=>31,'4'=>30,'5'=>'31','6'=>30,'7'=>31,'8'=>30,'9'=>31,'10'=>30,'11'=>31,'12'=>30];
    //given start day and date this function return the date of the first day given as third parameter
    public function findDateByDay($startdate,$startday,$findday){
 
         
        $date=new Date();
        $array=['Monday'=>1,'Tuesday'=>2,'Wednesday'=>3,'Thursday'=>4,'Friday'=>5,'Saturday'=>6,'Sunday'=>7];
        $i=$array[$startday];
        $day=$date->get($startdate,'day');
        
        $month=$date->get($startdate,'month');
        
        $year=$date->get($startdate,'year');
    
        //how many days should be added
        if($array[$startday]<$array[$findday]){
          $day_gap=$array[$startday]-$array[$findday];
          
          $add_days=$day+abs($day_gap);
        }
        else{
          $day_gap=7-$array[$startday]+$array[$findday];
          $add_days=$day+$day_gap;
        }
       
        
        if($add_days>$this->months[$month]){
            if($month=='12'){
                $month=1;
                $year+=1;
                return [(string)$add_days-$this->months[12],(string)$month,(string)$year];
            }
            return [(string)$add_days-$this->months[$month],(string)$month+1,$year];
            
        }
        else{
          
          return [(string)$add_days,$month,$year];
        }
      

    } 
    
    public function addDaysToDate($date,$days){
      // //strip the date
      // $dateModel=new Date();
      // $day=$dateModel->get($date,'day');
      // $month=$dateModel->get($date,'month');
      // $year=$dateModel->get($date,'year');
      // //add days to date part
      // $day+=$days;
      // //check with this month total day 
      // $monthdays=($this->months)[$month];
      // //if clause return date in strin formate
      // if($day>$monthdays){
      //   $day-=$monthdays;
      //   $month+=1;
      // }
      // if($month>=13){
      //   $month-=12;
      //   $year+=1;
      // }
     $date=date_create($date);
     date_add($date,date_interval_create_from_date_string($days." days"));
     return date_format($date,"Y-m-d"); 
  }

  
  //get number of days as output, parameters=>[1,'week'],[2,'month',1]
  public function getDayCount($array){
    $mul=1;
    if($array[1]=='year'){
      $mul=365;
    }
    else if($array[1]=='month'){
      $mul=$this->months[$array[2]];
    }
    else{
      $mul=7;

    }
    return $mul*$array[0];

  }

  public function subDaysByDate($date,$days){
    $date=date_create($date);
    date_sub($date,date_interval_create_from_date_string($date." days"));
    return date_format($date,"Y-m-d");

  }
  public function monthDays($date,$number_of_months){
      $dateModel=new Date(); 
      $montharray=['1'=>31,'2'=>28,'2L'=>29,'3'=>31,'4'=>30,'5'=>'31','6'=>30,'7'=>31,'8'=>30,'9'=>31,'10'=>30,'11'=>31,'12'=>30];
      $count=0;
      $day=$dateModel->get($date,'day');
      $month=$dateModel->get($date,'month');
      $year=$dateModel->get($date,'year');
      $month_days=0;
      //add number of days in month using the array
      while($count<$number_of_months){
        $month_days+=$month_days["'".$month+$count."'"];
        $count++;
      }
      return $month_days;
  }

  // public function generateDays($startdate,$startday,$finday,$duration,$hopduration){
  //     $dateModel=new Date(); 
  //     $duration_count=explode(' ',$duration)[0];
  //     $duration_type=explode(' ',$duration)[1];
  //     //get the start day
  //     $result_date=$this->findDateByDay($startdate,date('l', strtotime($startdate)),$finday);
  //     if($duration_type=='weeks'){
  //       $duration_days=7*(0+$duration_count);
  //     }
  //     else if($duration_type=='months'){
  //       $duration_days=$this->monthDays($startdate,$duration_count);
  //     }
  //     else if($duration_type=='year'){
  //       $duration_days=365*$duration_count;
  //     }
  //     else{
  //       $duration_days=$duration_count;
  //     }
  //     //get the last day
  //     $last_date=$this->addDaysToDate($result_date,$duration_days);
  //     $newdate=$result_date;
  //     $dayarrays=[];
  //     //get all the days:(date<last date)
  //     while($dateModel->greaterthan($newdate,$last_date)){
  //       $newdate=$dateModel->addDate($newdate,);
  //       array_push($dayarrays,$dateModel->addDate());

  //     }
      

  // }

    

}



?>