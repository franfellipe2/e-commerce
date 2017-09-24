<?php
use Hcode\Page;

$app->get('/carrinho/',function(){
    
    $tpl = new Page();
    $tpl->setTpl('carrinho');
    
});
