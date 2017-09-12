<?php 

require_once("vendor/autoload.php");
require 'config.php';

$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/', function() {
    
    $tpl = new Hcode\page();
    $tpl->setTpl('index', ['title'=>'hello World!']);    

});

$app->run();

 ?>