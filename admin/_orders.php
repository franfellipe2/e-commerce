<?php

use \Hcode\PageAdmin;
use Hcode\Models\Order;
use Hcode\Models\User;
use Hcode\Models\Ordersstatus;

$app->get('/admin/orders', function() {

    User::verifyLogin(3);

    $tpl = new PageAdmin();
    $tpl->setTpl('orders', [
        'orders' => Order::listAll()
    ]);
});

//EDITAR STATUS
$app->get('/admin/orders/:idorder/status', function($idorder) {

    User::verifyLogin(3);

    $order = new Order;
    $order->get($idorder);

    $tpl = new PageAdmin();
    $tpl->setTpl('order-status', [
        'order' => $order->getValues(),
        'msgError' => Order::getMsgError(),
        'msgSuccess' => Order::getMsgSucess(),
        'status' => Ordersstatus::listAll()
    ]);
});

$app->post('/admin/orders/:idorder/status', function($idorder) {

    User::verifyLogin(3);

    if (!isset($_POST['idstatus']) || $_POST['idstatus'] <= 0 ):
        
        Order::setMsgError('Status não informado!');
        header('location: ' . ADMIN_URL . '/orders/' . $idorder . '/status');
        exit;

    endif;

    $order = new Order;
    $order->get($idorder);

    $order->setIdstatus($_POST['idstatus']);
    $order->save();   
    
    $tpl = new PageAdmin();
    $tpl->setTpl('order-status', [
        'order' => $order->getValues(),
        'msgError' => Order::getMsgError(),
        'msgSuccess' => '',
        'status' => Ordersstatus::listAll()
    ]);
    
    Order::setMsgSucess('Status atualizado com sucesso!');
    
    header('location: ' . ADMIN_URL . '/orders/' . $idorder . '/status');
    
    exit;
});

//DELETA
$app->get('/admin/orders/:idorder/delete', function($idorder) {

    User::verifyLogin(3);

    Order::delete($idorder);

    header('location: ' . ADMIN_URL . '/orders');

    exit;
});

$app->get('/admin/orders/:idorder', function($idorder) {

    User::verifyLogin(3);

    $order = new Order;
    $order->get($idorder);

    $tpl = new PageAdmin();
    $tpl->setTpl('order', [
        'order' => $order->getValues(),
        'products' => $order->getProducts()
    ]);
});
