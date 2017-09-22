<?php
use \Hcode\Page;
use \Hcode\Models\Products;
$app->get('/', function() {

    $tpl = new Page();
    
    $products = Products::listAll();    
    $tpl->setTpl('index', array('products' => $products ));
    
});
