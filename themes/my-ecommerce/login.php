<?php

use Hcode\Page;
use Hcode\Models\User;

$app->get('/login', function() {

    $tpl = new Page();
    $tpl->setTpl('login');
});

$app->post('/login', function() {

    try {

        $user = new User();
        $user->login($_POST['login'], $_POST['password']);
        
    } catch (Exception $ex) {
        
        $tpl = new Page();
        $tpl->setTpl('login', array('data' => $_POST, 'error' => $ex->getMessage()));
    }
});
