<?php

use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\User;
use Hcode\Models\Pagination;

//ADMIN > USERS LISTA
$app->get('/admin/users/', function() {

    User::verifyLogin(3);


    //configura a paginação        
    $currentPage = (isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);
    $limit = 4;
    $maxLinks = 8;
    $link = ADMIN_URL . '/users/?page=';

    $search = '';

    if (!empty($_GET['search'])):

        $search = $_GET['search'];

        //altera o link da paginação      
        $link = ADMIN_URL . '/users/?search=' . $search . '&page=';

        $users = User::searhcWithPage($search, $currentPage, $limit);

        // Monta a paginação
        $numberRegisters = $users['total'];
        $pagination = new Pagination($link, $numberRegisters, $currentPage, $limit, $maxLinks);

    else:

        $users = User::listAllWithPage($currentPage, $limit);
        
        // Monta a paginação
        $numberRegisters = $users['total'];
        $pagination = new Pagination($link, $numberRegisters, $currentPage, $limit, $maxLinks);

    endif;
    

    $tpl = new PageAdmin();

    $tpl->setTpl('users', array(
        'users' => $users['data'],
        'session' => $_SESSION[User::SESSION],
        'search' => $search,
        'pages' => $pagination->getLinksNavigation(),
        'currentpage' => $currentPage,
        'totalpages' => $pagination->getTotalPages(),
        'firstLink' => $link.'1',
        'endLink' => $link.$pagination->getTotalPages()
    ));
});

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
