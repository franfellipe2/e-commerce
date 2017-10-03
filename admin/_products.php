<?php

use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\User;
use Hcode\Models\Category;
use Hcode\Models\Products;
use Hcode\Models\Pagination;

// -------------------------------------------------------------------
// ADMIN > PRODUTOS
// -------------------------------------------------------------------
// ADMIN > PRODUTOS LISTA
$app->get('/admin/products/', function() {

    User::verifyLogin(3);

    //CONFIGURA A PAGINAÇÃO
    $currentPage = (!empty($_GET['page']) ? $_GET['page'] : 1 );
    $limit = 4;
    $maxLinks = 8;
    $link = ADMIN_URL . '/products/?page=';

    //BUSCA OS RESULTADOS    
    $search = '';

    //por busca
    if (!empty($_GET['search'])):

        $search = $_GET['search'];

        //altera o link da paginação
        $link = ADMIN_URL . '/products/?search=' . $search . '&page=';

        $products = Products::searchWithPage($search, $currentPage, $limit);

    //sem busca    
    else:

        $products = Products::listAllWithPage($currentPage, $limit);

    endif;
    
    //Monta a paginação
    $pagination = new Pagination($link, $products['total'], $currentPage, $limit, $maxLinks);

    $tpl = new PageAdmin();

    $tpl->setTpl('products', [
        'products' => $products['data'],
        'search' => $search,
        'pages' => $pagination->getLinksNavigation(),
        'currentpage' => $currentPage,
        'totalpages' => $pagination->getTotalPages(),
        'firstLink' => $link . '1',
        'endLink' => $link . $pagination->getTotalPages()
    ]);
});

//  ADMIN > PRODUTOS CREATE
$app->get('/admin/products/create', function() {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    $tpl->setTpl('products-create');
});
$app->post('/admin/products/create', function() {

    User::verifyLogin(3);

    $tpl = new PageAdmin();

    $product = new Products();
    $resultSave = $product->save();

    if ($resultSave):
        header('location: ' . ADMIN_URL . '/products/');
        exit;
    endif;

    $tpl->setTpl('products-create', array('post' => $product->getValues(), 'error' => $product->getError()));
});

//  ADMIN > PRODUTOS UPDATE
$app->get('/admin/products/:id', function($id) {

    User::verifyLogin(3);
    $tpl = new PageAdmin();
    $product = Products::getProductById($id);

    $tpl->setTpl('products-update', array('post' => $product));
});

$app->post('/admin/products/:id', function($id) {

    User::verifyLogin(3);

    $tpl = new PageAdmin();

    $product = new Products();
    $product->setIdproduct($id);
    $resultSave = $product->save();

    $tpl->setTpl('products-update', array('post' => $product->getValues(), 'error' => $product->getError()));
});

// ADMIN > PRODUCTS DELETE
$app->get('/admin/products/delete/:id', function($id) {

    User::verifyLogin(3);
    Products::delete($id);

    header('location: ' . ADMIN_URL . '/products/');
    exit;
});
