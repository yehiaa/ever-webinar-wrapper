<?php 
namespace App\Models;

class Webinar implements \JsonSerializable
{
    private $id;
    private $name;
    private $description;
    private $schedules;
    private $presenters;

    public function __construct($name, $description)
    {
        $this->name        = $name;
        $this->description = $description;
        $this->presenters  = array();
        $this->schedules   = array();
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setPresenters(array $presenters)
    {
        $this->presenters = $presenters;
    }

    public function addPresenter(Presenter $presenter)
    {
        $this->presenters [] = $presenter;
    }

    public function setSchedules(array $schedules)
    {
        $this->schedules = $schedules;
    }

    public function getSchedules()
    {
        usort($this->schedules, function ($a, $b)
        {
            return ($a->getTimestamp() < $b->getTimestamp()) ? -1 : 1;
        });
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule)
    {
        $this->schedules [] = $schedule;
    }

    public function jsonSerialize() {
        return array(
            "schedules" => $this->getSchedules(),
            "presenters" => $this->presenters,
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description
            );
    }

    public static function buildFromStdClass($stdClass)
    {
        $instance = new self($stdClass->name, $stdClass->description, $stdClass->timezone);
        $instance->setId($stdClass->webinar_id);

        if (isset($stdClass->presenters) && is_array($stdClass->presenters)) {
            foreach ($stdClass->presenters as $presenter) {
                $instance->addPresenter(Presenter::buildFromStdClass($presenter));
            }
        }
  
        if (isset($stdClass->schedules) && is_array($stdClass->schedules)) {
            foreach ($stdClass->schedules as $schedule) {
                $instance->addSchedule(Schedule::buildFromStdClass($schedule, $stdClass->timezone));
            }
        }

        return $instance;
    }
}
