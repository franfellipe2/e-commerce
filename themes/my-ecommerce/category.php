<?php

use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\Category;

$app->get('/categoria/:id', function($id) {

    $tpl = new Page();
    $category = new Category();
    $category->setData(Category::getCategoryById($id));


    /*
     * Configura a paginação
     */
    $page = (isset($_GET['page']) ? $_GET['page'] : 1);
    $limit = 2;

    $pagination = $category->getProductsPage($page, $limit);

    $maxLinks = 8;
    $totalPages = ceil($pagination['total'] / $limit);

    $links = [];

    // links a esquerda
    for ($i = $page - ($maxLinks / 2); $i <= $totalPages && $i < $page; $i ++):

        if ($i > 0):
            array_push($links, [
                'link' => HOME . '/categoria/' . $id . '?page=' . $i,
                'page' => $i,
                'active' => ''
            ]);
        endif;

    endfor;

    // pagina atual
    array_push($links, [
        'link' => HOME . '/categoria/' . $id . '?page=' . $page,
        'page' => $page,
        'active' => 'active'
    ]);


    // links a direita
    for ($ir = $page; $ir <= $totalPages && $ir < $page + ($maxLinks / 2); $ir ++):
        if ($ir > $page):
            array_push($links, [
                'link' => HOME . '/categoria/' . $id . '?page=' . $ir,
                'page' => $ir,
                'active' => ( $page == $ir ? 'active' : '' )
            ]);
        endif;
    endfor;


    $tpl->setTpl('category', array(
        'cat' => $category->getValues(),
        'products' => $pagination['data'],
        'links' => $links,
        'currentpage' => $page,
        'totalPage' => $totalPages
            )
    );
});
