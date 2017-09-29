<?php
use Hcode\Page;
use Hcode\Models\Cart;
use Hcode\Models\User;
use Hcode\Models\Products;

//MOSTRAR CARRINHO
$app->get('/carrinho/', function() {

    $tpl = new Page();

    $cart = Cart::getSession();
    $products = $cart->listProducts();

    $tpl->setTpl('carrinho', ['cart' => $cart->getValues(), 'products' => $products, 'error' => Cart::getMsgError()]);
});

//ADICIONAR PRODUTO
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

//REMOVER 1 PRODUTO
$app->get('/carrinho/minus/:idproduct', function($idproduct) {

    $product = Products::getProductById($idproduct);

    $cart = Cart::getSession();
    $cart->removeProduct($product['idproduct']);

    header('location: ' . HOME . '/carrinho');
    exit;
});

//REMOVER TODOS OS PRODUTOS
$app->get('/carrinho/remove/:idproduct', function($idproduct) {

    $product = Products::getProductById($idproduct);

    $cart = Cart::getSession();
    $cart->removeProduct($product['idproduct'], TRUE);

    header('location: ' . HOME . '/carrinho');
    exit;
});

//CALCULAR O FRETE
$app->post('/carrinho/freight', function() {
    $cart = Cart::getSession();
    $cart->setFreight($_POST['zipcode']);
    header('location: ' . HOME . '/carrinho');
    exit;
});