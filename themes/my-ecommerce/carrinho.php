<?php
use Hcode\Page;
use Hcode\Models\Cart;
use Hcode\Models\User;

$app->get('/carrinho/',function(){
    
    $tpl = new Page();    
    
    $cart = new Cart();
    $cart->getSession();
    
    $tpl->setTpl('carrinho');
    
});
