<?php

use Hcode\Page;
use Hcode\Models\User;
use Hcode\Models\Cart;
use Hcode\Models\Address;

//TELA DE LOGIN
$app->get('/login', function() {

    $tpl = new Page();

    //verifica se veio um usuario criado apartir da pagina de registro de usuario
    if (!empty($_SESSION['registered'])):
        $registered = $_SESSION['registered'];
        unset($_SESSION['registered']);
    else:
        $registered = '';
    endif;

    $tpl->setTpl('login', ['registered' => $registered]);
});

$app->post('/login', function() {

    try {

        $user = new User();
        $user->login($_POST['login'], $_POST['password']);

        header('location: ' . HOME);
        exit;
    } catch (Exception $ex) {

        $tpl = new Page();
        $tpl->setTpl('login', array('data' => $_POST, 'error' => $ex->getMessage()));
    }
});


//DESLOGAR
$app->get('/login/logout', function() {

    $user = new User();
    $user->logout('/');

    header('location: ' . HOME);
});


//REGISTRAR USUARIO
$app->get('/login/register', function() {

    $user = new User();
    $user->save();

    $tpl = new Page();
    $tpl->setTpl('login-register', array('create' => $_POST, 'errorCreate' => ''));
});

$app->post('/login/register', function() {

    $user = new User();
    $user->save();
    if ($user->getError()):
        $tpl = new Page();
        $tpl->setTpl('login-register', array('create' => $_POST, 'errorCreate' => $user->getError()));
    else:
        $_SESSION['registered'] = "Usuário(a) \"{$_POST['person_name']}\" cadastrado(a) com Sucesso! Entre com seu login e senha.";
        header('location: ' . HOME . '/login');
        exit;
    endif;
});