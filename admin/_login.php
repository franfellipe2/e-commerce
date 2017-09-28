<?php
if(!isset($_SESSION)):
    session_start();
endif;

use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\User;
//LOGIN
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