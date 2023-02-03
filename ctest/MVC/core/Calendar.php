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
      //strip the date
      $dateModel=new Date();
      $day=$dateModel->get($date,'day');
      $month=$dateModel->get($date,'month');
      $year=$dateModel->get($date,'year');
      //add days to date part
      $day+=$days;
      //check with this month total day 
      $monthdays=($this->months)[$month];
      //if clause return date in strin formate
      if($day>$monthdays){
        $day-=$monthdays;
        $month+=1;
      }
      if($month>=13){
        $month-=12;
        $year+=1;
      }
      return  $dateModel->arrayToDate([$day,$month,$year]);




  }

    

}



?>