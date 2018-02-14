<?php

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'testsystem');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('PASSWORD_HASH', 'TJdd99l0WM');
define('ROOT_DIR', dirname(__DIR__));
//this is needed because codecept doesn't have permissions to write in the output folder
//since its aws instance i dont care about giving root pwd to the php script :D
define('SITE_URL', 'localhost');//change to localhost on production
define('TESTS_URL', 'http://' . SITE_URL . '/tests');
define('PATH_TO_TESTS_FOLDER', dirname(__DIR__) . '/public/tests/');
