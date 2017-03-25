<?php 
namespace App\Models;

class Schedule
{
    private $id;
    private $originalDateTimeStr;
    private $timeZoneStr;

    public function __construct($originalDateTimeStr, $id=null)
    {
        $this->id                  = $id;
        $this->originalDateTimeStr = $originalDateTimeStr;
        $this->timeZoneStr         = null;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTimeZoneStr($timeZoneStr)
    {
        // handle the auto time zone ..
        $this->timeZoneStr = $timeZoneStr;
    }

    public static function buildFromStdClass($stdClass, $timeZoneStr=null)
    {
        $date = is_string($stdClass) ? $stdClass : $stdClass->date;
        $instance = new self($date);
        if (isset($stdClass->schedule))
            $instance->setId($stdClass->schedule);

        $instance->setTimeZoneStr($timeZoneStr);

        return $instance;
    }
}
