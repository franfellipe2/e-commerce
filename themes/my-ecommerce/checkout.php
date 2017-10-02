<?php

use Hcode\Page;
use Hcode\Models\User;
use Hcode\Models\Cart;
use Hcode\Models\Address;
use Hcode\Models\Order;
use Hcode\Models\Ordersstatus;

//CHECA e/ou completa os DADOS PARA O ENVIO DOs PRODUTOs
$app->get('/checkout', function() {

    //verifica se existe um usuário logado
    if (!User::checkSession()):
        header('location: ' . HOME . '/login');
        exit;
    endif;

    $cart = Cart::getSession();
    $address = new Address();

    if (!empty($_GET['zipcode'])):

        $address->get($_GET['zipcode']);

        $cart->setDeszipcode($_GET['zipcode']);
        $cart->save();

    endif;

    $tpl = new Page();

    $tpl->setTpl('checkout', [
        'cart' => $cart->getValues(),
        'address' => $address->getValues(),
        'products' => $cart->listProducts()
    ]);
    
});


$app->post('/checkout', function() {

    //verifica se existe um usuário logado
    if (!User::checkSession()):
        header('location: ' . HOME . '/login');
        exit;
    endif;

    $cart = Cart::getSession();

    $address = new Address();
    $address->setData($_POST);
    $address->save();

    //Se não der erro envia para a pagina de pagamento
    if (!$address->getError()):

        $order = new Order;
        $order->setData([
            'idcart' => $cart->getIdcart(),
            'iduser' => User::getUserIdBySession(),
            'idstatus' => Ordersstatus::EM_ABERTO,
            'idaddress' => $address->getIdaddress(),
            'vltotal' => $cart->getTotal()
        ]);        
        
        $order->save();        
        
        header('location: ' . HOME . '/order/' . $order->getIdorder());
        exit;

    else:
        $tpl = new Page();

        $tpl->setTpl('checkout', [
            'cart' => $cart->getValues(),
            'address' => $address->getValues(),
            'products' => $cart->listProducts(),
            'error' => $address->getError()
        ]);
    endif;
});
