<?php

require __dir__.'/../vendor/autoload.php';
require __dir__.'/../bootstrap.php';

$app = new \Slim\Slim(
    array(
        'templates.path' => '../templates',
        "debug" => (bool) getenv("debug")
        ) );

use App\Apis\EverWebinarApi;
use App\Models\Webinar;
use App\Models\Schedule;

$api = new EverWebinarApi(getenv("baseUrl"), getenv("apiKey"), getenv("AutoTimeZone"));

$app->get('/api/webinars/', function () use ($app, $api) {
    $webinars = array();
    $webinarsStd = $api->allWebinars();
    foreach ($webinarsStd as $webinarStd) {
        $webinars [] = Webinar::buildFromStdClass($webinarStd);
    }
    $response = $app->response();
    $response['Content-Type'] = 'application/json';
    $response->body(json_encode($webinars));
});

$app->get('/api/webinars/:id', function ($id) use ($app, $api){
    $response = $app->response();
    $response['Content-Type'] = 'application/json';
    
    $apiResult = $api->webinar($id);
    $webinar = Webinar::buildFromStdClass($apiResult);
    $response->body(json_encode($webinar));
});

$app->get('/register/:webinar_id', function ($webinar_id) use ($app, $api){
    $webinar = Webinar::buildFromStdClass($api->webinar($webinar_id));
    $app->render('register.php', 
        array('webinar_id' => $webinar_id, 
        "webinar" => json_encode($webinar)));
});

$app->post('/register/:webinar_id', function ($webinar_id) use ($app, $api){
    // validate posted data
    // webinnar_id, schedule id , name, and email .. 
    $user = $api->register($webinar_id, $name, $email, $schedule_id);
    $app->render('finishRegister.php', array('user' => $user, 'webinar_id' => $webinar_id));
});


if (PHP_SAPI != "cli")
    $app->run();
