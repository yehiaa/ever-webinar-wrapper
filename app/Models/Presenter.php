<?php 
namespace App\Models;

class Presenter
{
    public $name;
    public $email;
    public $picture;

    public function __construct($name, $email, $picture)
    {
        $this->name = $name;
        $this->email = $email;
        $this->picture = $picture;
    }

    public static function buildFromStdClass($stdClass)
    {
        return new self($stdClass->name, $stdClass->email, $stdClass->picture);
    }
}
