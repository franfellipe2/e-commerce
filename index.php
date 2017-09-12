<?php

require 'config.php';
require_once("vendor/autoload.php");

use Slim\Slim;
use Hcode\Page;
use Hcode\PageAdmin;

$app = new Slim();

$app->config('debug', true);

//SITE HOME
$app->get('/', function() {

    $tpl = new Page();
    $tpl->setTpl('index');
});

//SITE ADMIN
$app->get('/admin/', function() {
    
    $tpl = new PageAdmin();
    $tpl->setTpl('index');
});
$app->run();
?>