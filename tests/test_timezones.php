<?php
use PHPUnit\Framework\TestCase;

use App\Models\Schedule;

class timezone_test extends TestCase
{
    public function test_creating_from_another_timezone_case_new_york()
    {
        $sourceTzStr = "America/New_York";
        $sourceDateTimeStr = "2017-12-08 08:00 PM";

        $distinationTzStr = "";
        $distinationDateTimeStr = "";

        $expectedStr = "";

        $this->assertEquals(0, 0);
    }

    public function test_creating_from_another_timezone_case_morovia()
    {
        $sourceTzStr = "Africa/Monrovia";
        $sourceDateTimeStr = "2017-12-08 08:00 PM"; //10:00 PM

        $distinationTzStr = "";
        $distinationDateTimeStr = "";

        $expectedStr = "";

        // $this->assertEquals(0, 0);
    }

    public function test_textualDayDateConvertion()
    {
        // Thursday, 8 Dec 08:00 PM //time zone newyork
        $textDate = 'Thursday, 8 Dec 08:00 PM';// 8 dec is not thursday 
        $tzStr = "America/New_York";
        $dateTimeObj = \DateTime::createFromFormat ( "l, j M h:i A", $textDate ,
             new \DateTimeZone($tzStr));
        $this->assertFalse("2017-12-8" == $dateTimeObj->format("Y-m-d"));
    }

    public function test_withoutTextualDayDateConvertion()
    {
        // this wont fail 
        $textDate = '08 Dec 08:00 PM';
        $tzStr = "America/New_York";
        $dateTimeObj = \DateTime::createFromFormat ("j M h:i A", $textDate ,
             new \DateTimeZone($tzStr));
        $this->assertEquals("2017-12-08", $dateTimeObj->format("Y-m-d"));
    }


    public function test_correctWithTextualDayDateConvertion()
    {
        // this one will success 
        $textDate = 'Thursday, 14 Dec 08:00 PM'; // 8 dec is not Thursday 
        $tzStr = "America/New_York";
        $dateTimeObj = \DateTime::createFromFormat ("l, j M h:i A", $textDate ,
             new \DateTimeZone($tzStr));
        $this->assertEquals("2017-12-14", $dateTimeObj->format("Y-m-d"));
    }
}