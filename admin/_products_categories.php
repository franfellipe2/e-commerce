<?php

use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\User;
use Hcode\Models\Category;
use Hcode\Models\Products;

$app->get('/admin/categories/:idcat/products/', function($idcat) {

    User::verifyLogin(3);

    $tpl = new PageAdmin();
    $category = new Category();
    $category->setData(Category::getCategoryById($idcat));

    $tpl->setTpl('categories-products', [
        'category' => $category->getValues(),
        'productsNotRelated' => $category->getProducts(false),
        'productsRelated' => $category->getProducts()
            ]
    );
});

$app->get('/admin/categories/:idcat/products/:idproduct/add', function($idcat, $idproduct) {

    User::verifyLogin(3);

    $category = new Category();
    $category->setData(Category::getCategoryById($idcat));
    $category->addProduct($idproduct);

    header('location: ' . ADMIN_URL . "/categories/$idcat/products/");
    exit;
});

$app->get('/admin/categories/:idcat/products/:idproduct/remove', function($idcat, $idproduct) {

    User::verifyLogin(3);

    $category = new Category();
    $category->setData(Category::getCategoryById($idcat));
    $category->removeProduct($idproduct);

    header('location: ' . ADMIN_URL . "/categories/$idcat/products/");
    exit;
});
