<?php
use Hcode\Page;
use Hcode\Models\User;
use Hcode\Models\Cart;
use Hcode\Models\Address;

//CHECA e/ou completa os DADOS PARA O ENVIO DOs PRODUTOs
$app->get('/checkout', function() {

    //verifica se existe um usuário logado
    if (!User::checkSession()):
        header('location: ' . HOME . '/login');
        exit;
    endif;

    $cart = Cart::getSession();
    $address = new Address();

    $tpl = new Page();

    $tpl->setTpl('checkout', [
        'cart' => $cart->getValues(),
        'address' => $address->getValues()
    ]);
});
