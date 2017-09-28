<?php

if (!session_start()):
    session_start();
endif;

require 'config.php';
require_once("vendor/autoload.php");
require("functions.php");

use Slim\Slim;

$app = new Slim();

$app->config('debug', true);

$url = explode('/', $_GET['url']);

if (!empty($url[0]) && $url[0] == 'admin'):
    require ADMIN_PATH . DIRECTORY_SEPARATOR . '_index.php';
else:
    require THEME_PATH . DIRECTORY_SEPARATOR . 'index.php';
endif;


$app->run();
?>