<?php declare(strict_types=1);

use app\core\Application;
use app\core\Calendar;
use app\core\Time;
use app\models\Appointment;
use PHPUnit\Framework\TestCase;

final class CalendarModelTest extends TestCase
{
   
      //core function that generate channeling sessions
    public function testgenerateChannelingTest2():void{
        $Calendar=new Calendar();
        $inputs=[['2023-05-06','Saturday','Saturday','1 months','1 weeks'], 
                ['2023-05-06','Saturday','Saturday','1 months','2 weeks'],
                ['2023-05-06','Saturday','Saturday','2 weeks','2 weeks'],
                ['2023-05-06','Saturday','Saturday','7 weeks','2 weeks']];
        $asserts=[['2023-05-06','2023-05-13','2023-05-20','2023-05-27','2023-06-03'],
                ['2023-05-06','2023-05-20','2023-06-03'],
                ['2023-05-06'],
                ['2023-05-06','2023-05-20','2023-06-03','2023-06-17']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($asserts[$i], $Calendar->generateDays($input[0],$input[1],$input[2],$input[3],$input[4]));
            $i=$i+1;
        }
    }
    public function testgenerateChannelingTest23():void{
        $Calendar=new Calendar();
        $inputs=[['2023-05-06','Saturday','Saturday','1 months','1 weeks'], 
                ['2023-05-06','Saturday','Saturday','1 months','2 weeks'],
                ['2023-05-06','Saturday','Saturday','2 weeks','2 weeks'],
                ['2023-05-06','Saturday','Saturday','7 weeks','2 weeks']];
        $asserts=[['2023-05-06','2023-05-13','2023-05-20','2023-05-27','2023-06-03'],
                ['2023-05-06','2023-05-20','2023-06-03'],
                ['2023-05-06'],
                ['2023-05-06','2023-05-20','2023-06-03','2023-06-17']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($asserts[$i], $Calendar->generateDays($input[0],$input[1],$input[2],$input[3],$input[4]));
            $i=$i+1;
        }
    }public function testgenerateChannelingTest24():void{
        $Calendar=new Calendar();
        $inputs=[['2023-05-06','Saturday','Saturday','1 months','1 weeks'], 
                ['2023-05-06','Saturday','Saturday','1 months','2 weeks'],
                ['2023-05-06','Saturday','Saturday','2 weeks','2 weeks'],
                ['2023-05-06','Saturday','Saturday','7 weeks','2 weeks']];
        $asserts=[['2023-05-06','2023-05-13','2023-05-20','2023-05-27','2023-06-03'],
                ['2023-05-06','2023-05-20','2023-06-03'],
                ['2023-05-06'],
                ['2023-05-06','2023-05-20','2023-06-03','2023-06-17']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($asserts[$i], $Calendar->generateDays($input[0],$input[1],$input[2],$input[3],$input[4]));
            $i=$i+1;
        }
    }public function testgenerateChannelingTest25():void{
        $Calendar=new Calendar();
        $inputs=[['2023-05-06','Saturday','Saturday','1 months','1 weeks'], 
                ['2023-05-06','Saturday','Saturday','1 months','2 weeks'],
                ['2023-05-06','Saturday','Saturday','2 weeks','2 weeks'],
                ['2023-05-06','Saturday','Saturday','7 weeks','2 weeks']];
        $asserts=[['2023-05-06','2023-05-13','2023-05-20','2023-05-27','2023-06-03'],
                ['2023-05-06','2023-05-20','2023-06-03'],
                ['2023-05-06'],
                ['2023-05-06','2023-05-20','2023-06-03','2023-06-17']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($asserts[$i], $Calendar->generateDays($input[0],$input[1],$input[2],$input[3],$input[4]));
            $i=$i+1;
        }
    }public function testgenerateChannelingTest26():void{
        $Calendar=new Calendar();
        $inputs=[['2023-05-06','Saturday','Saturday','1 months','1 weeks'], 
                ['2023-05-06','Saturday','Saturday','1 months','2 weeks'],
                ['2023-05-06','Saturday','Saturday','2 weeks','2 weeks'],
                ['2023-05-06','Saturday','Saturday','7 weeks','2 weeks']];
        $asserts=[['2023-05-06','2023-05-13','2023-05-20','2023-05-27','2023-06-03'],
                ['2023-05-06','2023-05-20','2023-06-03'],
                ['2023-05-06'],
                ['2023-05-06','2023-05-20','2023-06-03','2023-06-17']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($asserts[$i], $Calendar->generateDays($input[0],$input[1],$input[2],$input[3],$input[4]));
            $i=$i+1;
        }
    }public function testgenerateChannelingTest27():void{
        $Calendar=new Calendar();
        $inputs=[['2023-05-06','Saturday','Saturday','1 months','1 weeks'], 
                ['2023-05-06','Saturday','Saturday','1 months','2 weeks'],
                ['2023-05-06','Saturday','Saturday','2 weeks','2 weeks'],
                ['2023-05-06','Saturday','Saturday','7 weeks','2 weeks']];
        $asserts=[['2023-05-06','2023-05-13','2023-05-20','2023-05-27','2023-06-03'],
                ['2023-05-06','2023-05-20','2023-06-03'],
                ['2023-05-06'],
                ['2023-05-06','2023-05-20','2023-06-03','2023-06-17']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($asserts[$i], $Calendar->generateDays($input[0],$input[1],$input[2],$input[3],$input[4]));
            $i=$i+1;
        }
    }public function testgenerateChannelingTest28():void{
        $Calendar=new Calendar();
        $inputs=[['2023-05-06','Saturday','Saturday','1 months','1 weeks'], 
                ['2023-05-06','Saturday','Saturday','1 months','2 weeks'],
                ['2023-05-06','Saturday','Saturday','2 weeks','2 weeks'],
                ['2023-05-06','Saturday','Saturday','7 weeks','2 weeks']];
        $asserts=[['2023-05-06','2023-05-13','2023-05-20','2023-05-27','2023-06-03'],
                ['2023-05-06','2023-05-20','2023-06-03'],
                ['2023-05-06'],
                ['2023-05-06','2023-05-20','2023-06-03','2023-06-17']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($asserts[$i], $Calendar->generateDays($input[0],$input[1],$input[2],$input[3],$input[4]));
            $i=$i+1;
        }
    }public function testgenerateChannelingTest29():void{
        $Calendar=new Calendar();
        $inputs=[['2023-05-06','Saturday','Saturday','1 months','1 weeks'], 
                ['2023-05-06','Saturday','Saturday','1 months','2 weeks'],
                ['2023-05-06','Saturday','Saturday','2 weeks','2 weeks'],
                ['2023-05-06','Saturday','Saturday','7 weeks','2 weeks']];
        $asserts=[['2023-05-06','2023-05-13','2023-05-20','2023-05-27','2023-06-03'],
                ['2023-05-06','2023-05-20','2023-06-03'],
                ['2023-05-06'],
                ['2023-05-06','2023-05-20','2023-06-03','2023-06-17']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($asserts[$i], $Calendar->generateDays($input[0],$input[1],$input[2],$input[3],$input[4]));
            $i=$i+1;
        }
    }public function testgenerateChannelingTest21():void{
        $Calendar=new Calendar();
        $inputs=[['2023-05-06','Saturday','Saturday','1 months','1 weeks'], 
                ['2023-05-06','Saturday','Saturday','1 months','2 weeks'],
                ['2023-05-06','Saturday','Saturday','2 weeks','2 weeks'],
                ['2023-05-06','Saturday','Saturday','7 weeks','2 weeks']];
        $asserts=[['2023-05-06','2023-05-13','2023-05-20','2023-05-27','2023-06-03'],
                ['2023-05-06','2023-05-20','2023-06-03'],
                ['2023-05-06'],
                ['2023-05-06','2023-05-20','2023-06-03','2023-06-17']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($asserts[$i], $Calendar->generateDays($input[0],$input[1],$input[2],$input[3],$input[4]));
            $i=$i+1;
        }
    }public function testgenerateChannelingTest22():void{
        $Calendar=new Calendar();
        $inputs=[['2023-05-06','Saturday','Saturday','1 months','1 weeks'], 
                ['2023-05-06','Saturday','Saturday','1 months','2 weeks'],
                ['2023-05-06','Saturday','Saturday','2 weeks','2 weeks'],
                ['2023-05-06','Saturday','Saturday','7 weeks','2 weeks']];
        $asserts=[['2023-05-06','2023-05-13','2023-05-20','2023-05-27','2023-06-03'],
                ['2023-05-06','2023-05-20','2023-06-03'],
                ['2023-05-06'],
                ['2023-05-06','2023-05-20','2023-06-03','2023-06-17']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($asserts[$i], $Calendar->generateDays($input[0],$input[1],$input[2],$input[3],$input[4]));
            $i=$i+1;
        }
    }public function testgenerateChannelingTest93():void{
        $Calendar=new Calendar();
        $inputs=[['2023-05-06','Saturday','Saturday','1 months','1 weeks'], 
                ['2023-05-06','Saturday','Saturday','1 months','2 weeks'],
                ['2023-05-06','Saturday','Saturday','2 weeks','2 weeks'],
                ['2023-05-06','Saturday','Saturday','7 weeks','2 weeks']];
        $asserts=[['2023-05-06','2023-05-13','2023-05-20','2023-05-27','2023-06-03'],
                ['2023-05-06','2023-05-20','2023-06-03'],
                ['2023-05-06'],
                ['2023-05-06','2023-05-20','2023-06-03','2023-06-17']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($asserts[$i], $Calendar->generateDays($input[0],$input[1],$input[2],$input[3],$input[4]));
            $i=$i+1;
        }
    }public function testgenerateChannelingTest31():void{
        $Calendar=new Calendar();
        $inputs=[['2023-05-06','Saturday','Saturday','1 months','1 weeks'], 
                ['2023-05-06','Saturday','Saturday','1 months','2 weeks'],
                ['2023-05-06','Saturday','Saturday','2 weeks','2 weeks'],
                ['2023-05-06','Saturday','Saturday','7 weeks','2 weeks']];
        $asserts=[['2023-05-06','2023-05-13','2023-05-20','2023-05-27','2023-06-03'],
                ['2023-05-06','2023-05-20','2023-06-03'],
                ['2023-05-06'],
                ['2023-05-06','2023-05-20','2023-06-03','2023-06-17']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($asserts[$i], $Calendar->generateDays($input[0],$input[1],$input[2],$input[3],$input[4]));
            $i=$i+1;
        }
    }public function testgenerateChannelingTest34():void{
        $Calendar=new Calendar();
        $inputs=[['2023-05-06','Saturday','Saturday','1 months','1 weeks'], 
                ['2023-05-06','Saturday','Saturday','1 months','2 weeks'],
                ['2023-05-06','Saturday','Saturday','2 weeks','2 weeks'],
                ['2023-05-06','Saturday','Saturday','7 weeks','2 weeks']];
        $asserts=[['2023-05-06','2023-05-13','2023-05-20','2023-05-27','2023-06-03'],
                ['2023-05-06','2023-05-20','2023-06-03'],
                ['2023-05-06'],
                ['2023-05-06','2023-05-20','2023-06-03','2023-06-17']];
        $i=0;
        foreach($inputs as $input){
            $this->assertSame($asserts[$i], $Calendar->generateDays($input[0],$input[1],$input[2],$input[3],$input[4]));
            $i=$i+1;
        }
    }
   
    
   
}




