<?php declare(strict_types=1);

use app\core\Application;
use app\core\Calendar;
use app\core\Time;
use app\models\Appointment;
use PHPUnit\Framework\TestCase;

final class CalendarModelTest extends TestCase
{
    public function test_mehtod_findDateByDay(): void
    {  
        $calendarModel=new Calendar();
        $inputs=[
            ['2023-01-15','Monday','Monday',true],
            ['2023-01-15','Monday','Monday',false],
            ['2023-01-15','Monday','Tuesday',true],
            ['2023-01-15','Monday','Tuesday',false],
            ['2023-03-31','Tuesday','Wednesday',false],
            ['2023-01-31','Tuesday','Monday',true],
            ['2023-12-30','Tuesday','Monday',false],
            ['2023-12-30','Thursday','Thursday',false],
            ['2023-12-30','Thursday','Thursday',true],
    
        ];
        $output=[['15','1','2023'],['15','1','2023'],['16','1','2023'],['16','1','2023'],['1','4','2023'],['6','2','2023'],['6','1','2024'],['30','12','2023'],['30','12','2023']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($output[$i],$calendarModel->findDateByDay($input[0],$input[1],$input[2],$input[3]));
            $i=$i+1;
        }
    }

    public function test_generateDays(){
        $calendarModel=new Calendar();
        $inputs=[
            ['2023-05-08','Monday','Monday','1 months','1 weeks'],
            ['2023-05-08','Monday','Tuesday','3 weeks','3 weeks'],
            ['2023-05-09','Tuesday','Monday','1 weeks','1 weeks'],
            ['2023-01-09','Tuesday','Wednesday','3 weeks','2 weeks'],
            ['2023-05-08','Monday','Wednesday','1 months','3 weeks'],
            ['2023-12-28','Monday','Tuesday','2 weeks','1 weeks'],
      
        ];
        $output=[
            ['2023-05-08','2023-05-15','2023-05-22','2023-05-29','2023-06-05'],
            ['2023-05-09'],
            ['2023-05-15'],
            ['2023-01-10','2023-01-24'],
            ['2023-05-10','2023-05-31'],
            ['2023-12-29','2024-01-05']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($output[$i],$calendarModel->generateDays($input[0],$input[1],$input[2],$input[3],$input[4]));
            $i=$i+1;
        }

    }
   

   
}




