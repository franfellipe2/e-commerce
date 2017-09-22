<?php
use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\Category;

$app->get('/categoria/:id', function($id) {

    $tpl = new Page();
    $cat = Category::getCategoryById($id);
    $tpl->setTpl('category', array(
        'cat' => $cat,
        'products' => []
            )
    );
});