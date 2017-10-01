<?php

use Hcode\Page;
use Hcode\Models\User;
use Hcode\Models\Order;

$app->get('/profile-orders/', function() {

    if (!User::verifyLogin(1, false)):
        die;
    endif;
    
    $user = new User();    

    $tpl = new Page();    
    
    $userOrders = $user->getOrders();    

    $tpl->setTpl('profile-orders', [
        'orders' => $userOrders
    ]);
});

$app->get('/profile-orders/:idorder', function($idorder) {

    if (!User::verifyLogin(1, false)):
        die;
    endif;
    
    $order = new Order(); 
    
    $order->get((int)$idorder);
    
    $tpl = new Page();           

    $tpl->setTpl('profile-orders-detail', [
        'order' => $order->getValues(),
        'products' => $order->getProducts()
    ]);
    
});
