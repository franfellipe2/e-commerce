<?php
use Hcode\Page;
use Hcode\Models\User;

// Password Recovery
$app->post('/forgot/', function() {
    
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $send = User::sendForgot($email, false);
    header('location: ' . HOME . '/forgot/send');
    exit;
    
});
$app->get('/forgot/send', function() {
    
    $tpl = new Page();
    $tpl->setTpl('forgot-sent');
    
});
$app->get('/forgot/', function() {
    $tpl = new Page();
    $tpl->setTpl('forgot');
});

$app->get('/forgot/reset', function() {

    try {
        $codeForgot = (!empty($_GET['code']) ? $_GET['code'] : NULL);
        $user = User::validForgotDecrypt($codeForgot, false);
        $user = User::getUserById($user['user_id']);

        $tpl = new Page();

        $tpl->setTpl('forgot-reset', array(
            'name' => $user['person_name'],
            'code' => $codeForgot
        ));
    } catch (Exception $e) {
        echo $e->getMessage();
    }
});

$app->post('/forgot/reset', function() {

    try {
        $userForgot = User::validForgotDecrypt($_POST['code'], false);
        User::setForgotUser($userForgot['idrecovery']);

        $user = new User();
        $user->setUser_id($userForgot['user_id']);
        $user->setPassword($_POST['password']);

        $tpl = new Page();

        $tpl->setTpl('forgot-reset-success');
    } catch (Exception $e) {
        echo $e->getMessage();
    }
});