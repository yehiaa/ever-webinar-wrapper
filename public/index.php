<?php

require __dir__.'/../vendor/autoload.php';


require __dir__.'/../bootstrap.php';



$app = new \Slim\Slim();

$app->get('/hello/:name', function ($name) {
    echo "Hello, " . $name;
});


$app->run();
