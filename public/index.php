<?php

//only for dev purpose
error_reporting(E_ALL);
ini_set('display_errors', '1');


session_start();
//requires the composer autoloader
require __DIR__ . '/../bootstrap/autoload.php';
require __DIR__ . '/../config/config.php';
//adds the defined routes

//session_destroy();
require_once __DIR__ . '/../routes/routes.php';
$_SESSION['redirect_params'] = '';

