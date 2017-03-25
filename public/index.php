<?php

require __dir__.'/../vendor/autoload.php';
require __dir__.'/../bootstrap.php';

$app = new \Slim\Slim(
    array('templates.path' => '../templates') );

use App\Apis\EverWebinarApi;
use App\Models\Webinar;
use App\Models\Schedule;

$api = new EverWebinarApi(getenv("baseUrl"), getenv("apiKey"), getenv("AutoTimeZone"));

// test schedule 
// "Every Day 18:30 PM" 
        // "Every Tuesday, 01:00 PM",
        // "Every Thursday, 08:00 PM",
        // "Every Saturday, 01:00 PM" 
$x = new Schedule("Every Day, 10:30 PM"); //the time is wrong !!

$result = $x->getRepetition();
// var_dump($result);

$x = new Schedule("Every Tuesday, 09:00 PM");

$result = $x->getRepetition();
// var_dump($result);

$x = new Schedule("Tuesday, 09:00 PM");
$x->setTimeZoneStr("America/Jamaica");

var_dump($x->getTimestamp());

// $x = new Schedule("Mon, 6 Jul 20:01 PM"); //wrong time wrong date 
// var_dump($x->getTimestamp());

$x = new Schedule("Thursday, 6 Jul 08:30 AM"); //wrong time wrong date 6 jul is Thursday
$x->setTimeZoneStr("America/Jamaica");

var_dump($x->getTimestamp());
var_dump($x->getRepetition());



$x = new Schedule("Every Saturday , 12:30 PM");

$result = $x->getRepetition();
// var_dump($result);


$app->get('/api/webinars/', function () use ($app, $api) {
    $webinars = array();
    $webinarsStd = $api->allWebinars();
    foreach ($webinarsStd as $webinarStd) {
        $webinars [] = Webinar::buildFromStdClass($webinarStd);
    }
    echo json_encode($webinars);
});

$app->get('/api/webinars/:id', function ($id) use ($app, $api){
    $webinar = Webinar::buildFromStdClass($api->webinar($id));
    echo json_encode($webinar);
});

$app->get('/register/:webinar_id', function ($webinar_id) use ($app){
    $id = "this is the id ";
    $app->render('register.php', array('id' => $id));
});

$app->post('/register/:webinar_id', function ($webinar_id) use ($app, $api){
    echo "post register user ... ";
});


if (PHP_SAPI != "cli")
    $app->run();
