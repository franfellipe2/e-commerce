<?php
use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\User;
use Hcode\Models\Category;

// ---------------------------------------
// ADMIN > CATEGORIAS
// ---------------------------------------
// ADMIN > CATEGORIAIS LISTA
$app->get('/admin/categories/', function() {

    User::verifyLogin(3);

    $tpl = new PageAdmin();
    $tpl->setTpl('categories', array(
        'categories' => Category::listAll()
    ));
});

// ADMIN > CATEGORIAIS CREATE
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

// ADMIN > CATEGORIAIS DELETE
$app->get('/admin/categories/:id/delete', function($id) {

    User::verifyLogin(3);

    Category::delete($id);
    header('location: ' . ADMIN_URL . '/categories');
    exit();
});

// ADMIN > CATEGORIAIS UPDATE
$app->get('/admin/categories/:id', function($idcategory) {
    User::verifyLogin(3);

    $tpl = new PageAdmin();
    $tpl->setTpl('categories-update', array(
        'category' => Category::getCategoryById($idcategory)
    ));
});

$app->post('/admin/categories/:id/update', function($id) {

    User::verifyLogin(3);

    $category = new Category();
    $category->setIdcategory($id);
    $category->setDescategory($_POST['descategory']);

    $category->save();

    var_dump($category);
    header('location: ' . ADMIN_URL . '/categories/');
    exit();
});
