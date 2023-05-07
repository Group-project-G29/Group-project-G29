<?php declare(strict_types=1);


use app\core\Time;
use PHPUnit\Framework\TestCase;

final class DateTest extends TestCase
{
    public function testCanBeCreatedFromValidEmail(): void
    {
        $string = 'user@example.com';

      
        $time=new Time;
        $val=$time->greaterThan('12:30','13:30');

        $this->assertSame(true, $val);
    }

   
}




