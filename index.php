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

    if (User::loginLevel(3)):
        header('location: ' . ADMIN_URL);
        exit;
    endif;

    $tpl = new PageAdmin(array('footer' => false, 'header' => false));
    $tpl->setTpl('login', array('error' => ''));
});

$app->post('/admin/login/', function() {

    try {

        $user = User::login($_POST['user'], $_POST['pass']);
        header('location: ' . HOME . '/admin');
        exit;
    } catch (Exception $ex) {

        $tpl = new PageAdmin(array('footer' => false, 'header' => false));
        $tpl->setTpl('login', array('error' => $ex->getMessage()));
    }
});

$app->get('/admin/logout', function() {
    User::logout();
});

$app->get('/admin/users/create', function() {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    $tpl->setTpl('users-create');
});
$app->post('/admin/users/create', function() {

    User::verifyLogin(3);
    $tpl = new PageAdmin();

    $user = new User();

    if ($user->save()):
        header('location: ' . ADMIN_URL . '/users/');
        exit;
    else:
        $tpl->setTpl('users-create', array('data' => $user->getValues(), 'error' => $user->getError()));
    endif;
});

$app->get('/admin/users/', function() {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    $users = User::listAll();

    $tpl->setTpl('users', array('users' => $users));
});

$app->get('/admin/users/:id/delete', function($id) {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    User::delete($id);
    $users = User::listAll();
    $tpl->setTpl('users', array('users' => $users));
});

$app->get('/admin/users/:id', function($id) {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    $user = new User();
    $user->setData(User::getUserById($id));

    $tpl->setTpl('users-update', array('data' => $user->getValues(), 'error' => ''));
});
$app->post('/admin/users/:id', function($id) {

    User::verifyLogin(3);
    
    $tpl = new PageAdmin();
    
    $user = new User();
    $user->setData(User::getUserById($id));    
    $user->update();    
    
    $tpl->setTpl('users-update', array('data' => $user->getValues(), 'error' => $user->getError()));
});


$app->run();
?>