<?php

require __dir__.'/../vendor/autoload.php';
require __dir__.'/../bootstrap.php';

$app = new \Slim\Slim(
    array(
        'templates.path' => '../templates',
        "debug" => (bool) getenv("debug")
        ) );

$app->jsonError = function () use ($app) {
    $response = $app->response;
    $response->setStatus(400);
    $response->headers->set('Content-Type', 'application/json');
    $response->setBody('something went wrong sorry for inconvenient');
    return $response;
};

use App\Apis\EverWebinarApi;
use App\Models\Webinar;
use App\Models\Schedule;

$api = new EverWebinarApi(getenv("baseUrl"), getenv("apiKey"), getenv("AutoTimeZone"));

$app->get('/api/webinars/', function () use ($app, $api) {
    $response = $app->response();
    $response['Content-Type'] = 'application/json';
    try {
        $webinars = array();
        $webinarsStd = $api->allWebinars();
        foreach ($webinarsStd as $webinarStd) {
            $webinars [] = Webinar::buildFromStdClass($webinarStd);
        }
        $response->body(json_encode($webinars));
    } catch (Exception $e) {
        $app->jsonError;
    }
});

$app->get('/api/webinars/raw/', function () use ($app, $api) {
    $response = $app->response();
    $response['Content-Type'] = 'application/json';
    try {
        $webinars = array();
        $webinarsStd = $api->allWebinars();

        $response->body(json_encode($webinarsStd));
    } catch (Exception $e) {
        $app->jsonError;
    }
});

$app->get('/api/webinars/raw/:id', function ($id) use ($app, $api){
    $response = $app->response();
    $response['Content-Type'] = 'application/json';
    try {
        $apiResult = $api->webinar($id);
        $response->body(json_encode($apiResult));
    } catch (Exception $e) {
        $app->jsonError;
    }
});

$app->get('/api/webinars/:id', function ($id) use ($app, $api){
    $response = $app->response();
    $response['Content-Type'] = 'application/json';
    try {
        $apiResult = $api->webinar($id);
        $webinar = Webinar::buildFromStdClass($apiResult);
        $response->body(json_encode($webinar));
    } catch (Exception $e) {
        $app->jsonError;
    }
});

$app->get('/register/:webinar_id', function ($webinar_id) use ($app, $api){
    try {
        $webinar = Webinar::buildFromStdClass($api->webinar($webinar_id));
        $app->render('register.php', 
            array('webinar_id' => $webinar_id, 
            "webinar" => json_encode($webinar)));
    } catch (Exception $e) {
        $app->error();
    }
});

$app->post('/register/:webinar_id', function ($webinar_id) use ($app, $api){
    $webinar_id  = $webinar_id;
    $schedule_id = $app->request->post('schedule_id');
    $name        = $app->request->post('name');
    $email       = $app->request->post('email');

    $v = new Valitron\Validator($app->request->post());
    $v->rule('required', array('name', 'email', 'schedule_id'));
    $v->rule('email', 'email');
    $v->rule('name', 'lengthMin', 4);

    try {
        if($v->validate()) {
            $user = $api->register($webinar_id, $name, $email, $schedule_id);
            $app->redirect($user->thank_you_url);
        } 
    // $app->render('finishRegister.php', array('user' => $user, 'webinar_id' => $webinar_id));
        echo json_encode($user);
    } catch (Exception $e) {
        $app->error();
    }
});


if (PHP_SAPI != "cli")
    $app->run();
