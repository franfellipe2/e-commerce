<?php

use Hcode\Page;
use Hcode\Models\User;

$app->get('/profile', function() {

    if (!User::checkSession()):

        header('Location: ' . HOME . '/login');
        exit;

    else:

        $tpl = new Page();
        $tpl->setTpl('profile', [
            'profileMsg' => '',
            'profileError' => '',
            'user' => User::getUserBySession()
        ]);

    endif;
});

$app->post('/profile', function() {

    if (!User::checkSession()):

        header('Location: ' . HOME . '/login');
        exit;

    else:

        $user = new User();
        $user->setData(User::getUserBySession());
        $user->update();

        $tpl = new Page();
        $tpl->setTpl('profile', [
            'profileMsg' => (!$user->getError() ? 'Dados alterados com sucesso!' : ''),
            'error' => $user->getError(),
            'user' => User::getUserById(User::getUserBySession()['user_id'])
        ]);

    endif;
});
