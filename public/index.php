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
    $response->setBody(json_encode(
            array('status'=> 'error',
                'message'=>'something went wrong sorry for inconvenience'))
    );
    return $response;
};

use App\Apis\EverWebinarApi;
use App\Models\Webinar;
use App\Models\Schedule;
use Valitron\Validator;

$api = new EverWebinarApi(getenv("baseUrl"), getenv("apiKey"), getenv("AutoTimeZone"));

$GLOBALS["register"] = "global scooop ..."; 
$theglobal = "this is global var ";
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
        $url = $app->urlFor('register_post', array('webinar_id' => $webinar_id));
        $webinar = Webinar::buildFromStdClass($api->webinar($webinar_id));
        $app->render('register.php', 
            array('webinar_id' => $webinar_id,
            'url' => $url, 
            "webinar" => json_encode($webinar)));
    } catch (Exception $e) {
        $app->error();
    }
});


$app->get('/register_ajax/:webinar_id', function ($webinar_id) use ($app, $api){
    try {
        $url = $app->urlFor('register_post', array('webinar_id' => $webinar_id));
        $app->render('register_ajax.php', 
            array('webinar_id' => $webinar_id, 'url' => $url));
    } catch (Exception $e) {
        $app->error();
    }
});

$app->post('/register/:webinar_id', function ($webinar_id) use ($app, $api){
    $webinar_id  = $webinar_id;
    $schedule_id = $app->request->post('schedule_id');
    $name        = $app->request->post('name');
    $email       = $app->request->post('email');

    try {
        
        $v = new Validator(array(
            "name" =>$name,
            "email" => $email,
            "webinar_id" => $webinar_id,
            "schedule_id" => $schedule_id,
            ));
        $v->rule('required', array('name', 'email', 'schedule_id', 'webinar_id'));
        $v->rule('email', 'email');

        if($v->validate()) {
            $user = $api->register($webinar_id, $name, $email, $schedule_id);
            if (isset($user->thank_you_url)) {
                $app->redirect($user->thank_you_url);
            }else{
                $app->error();
            }
        } 
    } catch (Exception $e) {
        $app->error();
    }
})->name("register_post");





if (PHP_SAPI != "cli")
    $app->run();
