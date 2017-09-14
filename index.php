<?php

if (!session_start()):
    session_start();
endif;

require 'config.php';
require_once("vendor/autoload.php");

use Slim\Slim;
use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\User;

$app = new Slim();

$app->config('debug', true);

// =====================================
// SITE
// =====================================
$app->get('/', function() {

    $tpl = new Page();
    $tpl->setTpl('index');
});

// ====================================
// ADMIN
// ====================================
$app->get('/admin/', function() {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    $tpl->setTpl('index');
});

$app->get('/admin/login/', function() {
    
    if(User::loginLevel(3)):
        header('location: '.ADMIN_URL);
        exit;
    endif;

    $tpl = new PageAdmin(array('footer' => false, 'header' => false));
    $tpl->setTpl('login');
});

$app->post('/admin/login/', function() {

    try {
        $user = User::login($_POST['user'], $_POST['pass']);
        header('location: ' . HOME . '/admin');
        exit;
    } catch (Exception $ex) {

        echo $ex->getMessage();
    }
});

$app->get('/admin/logout', function() {
    User::logout();
});

$app->get('/admin/users/', function() {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    $tpl->setTpl('users');
});

$app->run();
?>