<?php
use Hcode\Models\User;
use Hcode\Page;
use Hcode\PageAdmin;

// ADMIN > INDEX
$app->get('/admin/', function() {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    $tpl->setTpl('index');
});