<?php

use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\User;
use Hcode\Models\Category;
use Hcode\Models\Pagination;

// ---------------------------------------
// ADMIN > CATEGORIAS
// ---------------------------------------
// ADMIN > CATEGORIAIS LISTA
$app->get('/admin/categories/', function() {

    User::verifyLogin(3);

    //configura a paginação        
    $currentPage = (isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);
    $limit = 4;
    $maxLinks = 8;
    $link = ADMIN_URL . '/categories/?page=';

    $search = '';
    if (!empty($_GET['search'])):

        $search = $_GET['search'];

        //altera o link da paginação
        $link = ADMIN_URL . '/categories/?search=' . $search . '&page=';

        $categories = Category::searchWithPage($search, $currentPage, $limit);
        
    else:

        $categories = Category::listAllWithPage($currentPage, $limit);

    endif;

    // Monta a paginação
    $numberRegisters = $categories['total'];
    $pagination = new Pagination($link, $numberRegisters, $currentPage, $limit, $maxLinks);

    $tpl = new PageAdmin;

    $tpl->setTpl('categories', array(
        'categories' => $categories['data'],
        'search' => $search,
        'pages' => $pagination->getLinksNavigation(),
        'currentpage' => $currentPage,
        'totalpages' => $pagination->getTotalPages(),
        'firstLink' => $link . '1',
        'endLink' => $link . $pagination->getTotalPages()
    ));
});

// ADMIN > CATEGORIAIS CREATE
$app->get('/admin/categories/create/', function() {

    User::verifyLogin(3);

    $tpl = new PageAdmin();
    $tpl->setTpl('categories-create');
});

$app->post('/admin/categories/create/', function() {

    User::verifyLogin(3);

    $category = new Category();
    $category->setData($_POST);
    $category->save();
    header('location: ' . ADMIN_URL . '/categories/');
    exit();
});

// ADMIN > CATEGORIAIS DELETE
$app->get('/admin/categories/:id/delete', function($id) {

    User::verifyLogin(3);

    Category::delete($id);
    header('location: ' . ADMIN_URL . '/categories');
    exit();
});

// ADMIN > CATEGORIAIS UPDATE
$app->get('/admin/categories/:id', function($idcategory) {
    User::verifyLogin(3);

    $tpl = new PageAdmin();
    $tpl->setTpl('categories-update', array(
        'category' => Category::getCategoryById($idcategory)
    ));
});

$app->post('/admin/categories/:id/update', function($id) {

    User::verifyLogin(3);

    $category = new Category();
    $category->setIdcategory($id);
    $category->setDescategory($_POST['descategory']);

    $category->save();

    var_dump($category);
    header('location: ' . ADMIN_URL . '/categories/');
    exit();
});
