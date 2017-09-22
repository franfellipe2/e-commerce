<?php
use Hcode\Models\User;
use Hcode\Page;
use Hcode\PageAdmin;

// ADMIN > USERS
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

//ADMIN > USERS LISTA
$app->get('/admin/users/', function() {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    $users = User::listAll();

    $tpl->setTpl('users', array('users' => $users, 'session' => $_SESSION[User::SESSION]));
});

//ADMIN > USER DELETE
$app->get('/admin/users/:id/delete', function($id) {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    User::delete($id);
    $users = User::listAll();
    $tpl->setTpl('users', array('users' => $users, 'session' => $_SESSION[User::SESSION]));
});

//ADMIN > USER UPDATE
$app->get('/admin/users/:id', function($id) {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    $user = new User();
    $user->setData(User::getUserById($id));

    $tpl->setTpl('users-update', array('data' => $user->getValues(), 'session' => $_SESSION[User::SESSION]));
});

$app->post('/admin/users/:id', function($id) {

    User::verifyLogin(3);

    $tpl = new PageAdmin();

    $user = new User();
    $user->setData(User::getUserById($id));
    $user->update();

    $tpl->setTpl('users-update', array('data' => $user->getValues(), 'error' => $user->getError(), 'session' => $_SESSION[User::SESSION]));
});