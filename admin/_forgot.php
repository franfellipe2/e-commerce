<?php
use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\User;

// Password Recovery
$app->post('/admin/forgot/', function() {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $send = User::sendForgot($email);
    header('location: ' . ADMIN_URL . '/forgot/send');
    exit;
});
$app->get('/admin/forgot/send', function() {
    $tpl = new PageAdmin(array('header' => false, 'footer' => false));
    $tpl->setTpl('forgot-sent');
});
$app->get('/admin/forgot/', function() {
    $tpl = new PageAdmin(array('header' => false, 'footer' => false));
    $tpl->setTpl('forgot');
});

$app->get('/admin/forgot/reset', function() {

    try {
        $codeForgot = (!empty($_GET['code']) ? $_GET['code'] : NULL);
        $user = User::validForgotDecrypt($codeForgot);
        $user = User::getUserById($user['user_id']);

        $tpl = new PageAdmin(array('header' => false, 'footer' => false));

        $tpl->setTpl('forgot-reset', array(
            'name' => $user['person_name'],
            'code' => $codeForgot
        ));
    } catch (Exception $e) {
        echo $e->getMessage();
    }
});

$app->post('/admin/forgot/reset', function() {

    try {
        $userForgot = User::validForgotDecrypt($_POST['code']);
        User::setForgotUser($userForgot['idrecovery']);

        $user = new User();
        $user->setUser_id($userForgot['user_id']);
        $user->setPassword($_POST['password']);

        $tpl = new PageAdmin(array('header' => false, 'footer' => false));

        $tpl->setTpl('forgot-reset-success');
    } catch (Exception $e) {
        echo $e->getMessage();
    }
});