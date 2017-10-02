<?php

use Hcode\Page;
use Hcode\Models\User;

$app->get('/profile/change-password', function() {

    if (!User::verifyLogin(1, false)):
        die;
    endif;

    $tpl = new Page();
    $tpl->setTpl('profile-change-password', [
        'changePassError' => '',
        'changePassSuccess' => User::getMsgSucess()
    ]);
});

$app->post('/profile/change-password', function() {

    if (!User::verifyLogin(1, false)):
        die;
    endif;


    $error = array();

    if (empty($_POST['current_pass'])):

        $error['current_pass'] = 'Digite a senha atual!';

    elseif (!password_verify($_POST['current_pass'], $_SESSION[User::SESSION]['user_pass'])):

        $error['current_pass'] = 'Senha não confere!';

    else:

        if (empty($_POST['new_pass'])):

            $error['new_pass'] = 'Digite a nova senha';

        elseif ($_POST['new_pass'] == $_POST['current_pass']):

            $error['new_pass'] = 'Sua nova senha deve ser diferente da atual!';

        elseif (empty($_POST['new_pass_confirm'])):

            $error['new_pass_confirm'] = 'Confirme a nova senha!';

        elseif ($_POST['new_pass_confirm'] != $_POST['new_pass']):

            $error['new_pass_confirm'] = 'A senha não está igual!';

        endif;

    endif;

    if (empty($error)):

        $user = new User();

        $user->setData(User::getUserBySession());

        $user->updatePassword($_POST['new_pass']);

        $user->login($user->getUser_login(), $_POST['new_pass']);

        User::setMsgSucess('Senha Alterada com sucesso!');

        header('location: ' . HOME . '/profile/change-password');

        exit;

    endif;

    $tpl = new Page();
    $tpl->setTpl('profile-change-password', [
        'error' => $error,
        'changePassSuccess' => ''
    ]);
});


