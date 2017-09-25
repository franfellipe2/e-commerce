<?php

unset($_SESSION);

use Hcode\Page;
use Hcode\Models\Cart;
use Hcode\Models\User;
use Hcode\Models\Products;

$app->get('/carrinho/', function() {

    $tpl = new Page();

    $cart = Cart::getSession();
    $products = $cart->listProducts();

    $tpl->setTpl('carrinho', ['products' => $products]);
});

$app->get('/carrinho/add/:idproduct', function($idproduct) {

    $product = Products::getProductById($idproduct);

    $cart = Cart::getSession();

    $qtd = (!empty($_GET['qtd']) ? (int) $_GET['qtd'] : 1);
    
    for ($i = 0; $i < $qtd; $i++):
        $cart->addProduct($product['idproduct']);
    endfor;
    
    header('location: ' . HOME . '/carrinho');
    exit;
});

$app->get('/carrinho/minus/:idproduct', function($idproduct) {

    $product = Products::getProductById($idproduct);

    $cart = Cart::getSession();
    $cart->removeProduct($product['idproduct']);

    header('location: ' . HOME . '/carrinho');
    exit;
});

$app->get('/carrinho/remove/:idproduct', function($idproduct) {

    $product = Products::getProductById($idproduct);

    $cart = Cart::getSession();
    $cart->removeProduct($product['idproduct'], TRUE);

    header('location: ' . HOME . '/carrinho');
    exit;
});
