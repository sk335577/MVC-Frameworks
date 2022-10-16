<?php

require_once BharatPHP_ROOT_PATH . '/BharatPHP/autoloader.php';

// session_start();

$app = new BharatPHP\Application(include BharatPHP_ROOT_PATH . '/app/config/application.php');


//Load routes
include BharatPHP_ROOT_PATH . '/app/routes/web.php';


$app->run();
