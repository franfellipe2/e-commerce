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

require ADMIN_PATH.DIRECTORY_SEPARATOR.'_index.php';
require THEME_PATH.DIRECTORY_SEPARATOR.'index.php';

$app->run();
?>