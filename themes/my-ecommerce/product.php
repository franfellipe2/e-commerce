<?php

use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\Category;
use \Hcode\Models\Products;

$app->get('/product/:desurl', function($desurl) {

    $tpl = new Page();

    $produtc = Products::getProductBySlug($desurl);    
    
    if (!$produtc):
        $tpl->setTpl('404');
    else:
        $categories = Products::getCategories($produtc['idproduct']);

        $tpl->setTpl('detalhes-produto', [
            'product' => $produtc,
            'categories' => $categories
                ]
        );
    endif;
});
