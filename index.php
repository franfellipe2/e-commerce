<?php 
require 'config.php';
require_once("vendor/autoload.php");

$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/', function() {
    
    $tpl = new Hcode\page();
    $tpl->setTpl('index');    

});

$app->run();
 ?>