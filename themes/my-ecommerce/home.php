<?php
use \Hcode\Page;
use \Hcode\Models\Products;
$app->get('/', function() {

    $tpl = new Page();
    
    $products = Products::listAll();
    var_dump($products);
    
    $tpl->setTpl('index', array('products' => $products ));
});
