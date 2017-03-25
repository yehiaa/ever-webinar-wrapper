<?php

require __dir__.'/../vendor/autoload.php';
require __dir__.'/../bootstrap.php';

$app = new \Slim\Slim();

use App\Apis\EverWebinarApi;
use App\Models\Webinar;

$api = new EverWebinarApi(getenv("baseUrl"), getenv("apiKey"), getenv("AutoTimeZone"));

// var_dump($api->webinar("webinar id ") );
// var_dump($api->webinars());
// var_dump($api->allWebinars());

// $webinarsStd = $api->allWebinars();
// var_dump($webinarsStd);

// $webinar = Webinar::buildFromStdClass($api->webinar("993bc1c46d"));
// $webinars = array();
// foreach ($webinarsStd as $webinarStd) {
//     $webinars [] = Webinar::buildFromStdClass($webinarStd);
// }
// var_dump($webinars);


$app->get('/webinars/', function () {
    echo "returns all webinars";
});

$app->get('/webinars/:id', function ($id) {
    echo "return certain webinar {$id}, " ;
});

$app->get('/register/', function () {
    echo "return HTML form";
});

$app->post('/register/', function () {
    echo "post register user ... ";
});


if (PHP_SAPI != "cli")
    $app->run();
