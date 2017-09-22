<?php

use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\Category;

$app->get('/categoria/:id', function($id) {

    $tpl = new Page();
    $category = new Category();
    $category->setData(Category::getCategoryById($id));
    
    $tpl->setTpl('category', array(
        'cat' => $category->getValues(),
        'products' => $category->getProducts()
            )
    );
});
