<?php

/*
Put all bootstrapping stuff here 
*/

/* ini*/
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

date_default_timezone_set("UTC");
