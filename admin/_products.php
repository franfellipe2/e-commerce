<?php
use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\User;
use Hcode\Models\Category;
use Hcode\Models\Products;
// -------------------------------------------------------------------
// ADMIN > PRODUTOS
// -------------------------------------------------------------------
// ADMIN > PRODUTOS LISTA
$app->get('/admin/products/', function() {

    User::verifyLogin(3);

    $tpl = new PageAdmin();

    $products = Products::listAll();

    $tpl->setTpl('products', array('products' => $products));
});
//  ADMIN > PRODUTOS CREATE
$app->get('/admin/products/create', function() {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    $tpl->setTpl('products-create');
});
$app->post('/admin/products/create', function() {

    User::verifyLogin(3);

    $tpl = new PageAdmin();

    $product = new Products();
    $resultSave = $product->save();

    if ($resultSave):
        header('location: ' . ADMIN_URL . '/products/');
        exit;
    endif;

    $tpl->setTpl('products-create', array('post' => $product->getValues(), 'error' => $product->getError()));
});

//  ADMIN > PRODUTOS UPDATE
$app->get('/admin/products/:id', function($id) {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    $product = Products::getProductById($id);

    $tpl->setTpl('products-update', array('post' => $product));
});

$app->post('/admin/products/:id', function($id) {

    User::verifyLogin(3);

    $tpl = new PageAdmin();

    $product = new Products();
    $product->setIdproduct($id);
    $resultSave = $product->save();

    $tpl->setTpl('products-update', array('post' => $product->getValues(), 'error' => $product->getError()));
});

// ADMIN > PRODUCTS DELETE
$app->get('/admin/products/delete/:id', function($id) {

    User::verifyLogin(3);
    Products::delete($id);

    header('location: ' . ADMIN_URL . '/products/');
    exit;
});