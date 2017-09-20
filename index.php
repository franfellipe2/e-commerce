<?php

if (!session_start()):
    session_start();
endif;

require 'config.php';
require_once("vendor/autoload.php");

use Slim\Slim;
use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\User;
use Hcode\Models\Category;

$app = new Slim();

$app->config('debug', true);

// =====================================
// SITE
// =====================================
$app->get('/', function() {

    $tpl = new Page();
    $tpl->setTpl('index');
});

// ====================================
// ADMIN
// ====================================
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

// -------------------------
// Password Recovery
// -------------------------
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

// --------------------------
// ADMIN > INDEX
// --------------------------
$app->get('/admin/', function() {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    $tpl->setTpl('index');
});

// ----------------------------
// ADMIN > CREATE USER
// ----------------------------
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

//ADMIN > USERS
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

// =======================================
// GERENCIAR CATEGORIAS
// =======================================
// lISTA DE CATEGORIAIS
$app->get('/admin/categories/', function() {
    
    User::verifyLogin(3);
    
    $tpl = new PageAdmin();
    $tpl->setTpl('categories', array(
        'categories' => Category::listAll()
    ));
});

$app->get('/admin/categories/create/', function() {
    
    User::verifyLogin(3);
    
    $tpl = new PageAdmin();
    $tpl->setTpl('categories-create');
});

$app->post('/admin/categories/create/', function() {
    
    User::verifyLogin(3);
    
    $category = new Category();
    $category->setData($_POST);
    $category->save();
    header('location: ' . ADMIN_URL . '/categories/');
    exit();
});

$app->get('/admin/categories/:id/delete', function($id) {
    
    User::verifyLogin(3);
    
    Category::delete($id);
    header('location: '.ADMIN_URL.'/categories'); 
    exit();
    
});

// EDITAR CATEGORIA
$app->get('/admin/categories/:id', function($idcategory) {
    User::verifyLogin(3);
    
    $tpl = new PageAdmin();
    $tpl->setTpl('categories-update', array(
        'category' => Category::getCategoryById($idcategory)
    ));
});

$app->post('/admin/categories/:id/update', function($id){
    
    User::verifyLogin(3);
    
    $category = new Category();
    $category->setIdcategory($id);
    $category->setDescategory($_POST['descategory']);
    
    $category->save();
    
    var_dump($category);
    header('location: ' . ADMIN_URL . '/categories/');
    exit();
    
});

$app->run();
?>