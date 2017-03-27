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

    public function test_everySpecificDayUsingScheduleClass()
    {
        $format = "l, h:i A";

        /*
        isoUTC": "2017-03-28T17:00:00+00:00",
        "originalTimezone": "America/New_York",
        "originalTime": "Every Tuesday, 01:00 PM"
        */
        $schedule1 = Schedule::buildFromStdClass("Every Tuesday, 01:00 PM", "America/New_York");
        
        $dt = \DateTime::createFromFormat($format, "Tuesday, 01:00 PM", new \DateTimeZone("America/New_York"));
        $dtUTC = clone $dt;
        $dtUTC->setTimezone(new \DateTimeZone("UTC"));
        $this->assertEquals($dtUTC->format(\DateTime::ATOM), $schedule1->getISOUTCDateTime());

        /*
        isoUTC": "2017-03-31T00:00:00+00:00",
        "originalTimezone": "America/New_York",
        "originalTime": "Every Thursday, 08:00 PM"
        */
        $schedule1 = Schedule::buildFromStdClass("Every Thursday, 08:00 PM", "America/New_York");

        $dt = \DateTime::createFromFormat($format, "Thursday, 08:00 PM", new \DateTimeZone("America/New_York"));
        $dtUTC = clone $dt;
        // var_dump($dt->format(\DateTime::ATOM));
        $dtUTC->setTimezone(new \DateTimeZone("UTC"));
        $this->assertEquals($dtUTC->format(\DateTime::ATOM), $schedule1->getISOUTCDateTime());

        /*
        isoUTC": "2017-04-01T17:00:00+00:00",
        "originalTimezone": "America/New_York",
        "originalTime": "Every Saturday, 01:00 PM"
        */
        $schedule1 = Schedule::buildFromStdClass("Every Saturday, 01:00 PM", "America/New_York");

        $dt = \DateTime::createFromFormat($format, "Saturday, 01:00 PM", new \DateTimeZone("America/New_York"));
        $dtUTC = clone $dt;

        $dtUTC->setTimezone(new \DateTimeZone("UTC"));
        $this->assertEquals($dtUTC->format(\DateTime::ATOM), $schedule1->getISOUTCDateTime());
    }
}