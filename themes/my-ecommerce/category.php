<?php

use Hcode\Page;
use Hcode\PageAdmin;
use Hcode\Models\Category;

$app->get('/categoria/:id', function($id) {


    $tpl = new Page();

    if (!is_numeric($id)):

        $tpl->setTpl('404');
    else:

        $categoryData = Category::getCategoryById($id);

        if (!$categoryData):
            $tpl->setTpl('404');
        else:

            $category = new Category();
            $category->setData($categoryData);


            // Configura a paginação
            $page = (isset($_GET['page']) ? $_GET['page'] : 1);
            $limit = 2;
            $maxLinks = 8;

            // pega os produtos
            $pagination = $category->getProductsPage($page, $limit);
            // pega o numero total dos produtos
            $totalPages = ceil($pagination['total'] / $limit);

            //MONTA OS LINKS DA PAGINAÇÃO
            $links = [];

            // links à esquerda
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


            // links à direita
            for ($ir = $page; $ir <= $totalPages && $ir < $page + ($maxLinks / 2); $ir ++):
                if ($ir > $page):
                    array_push($links, [
                        'link' => HOME . '/categoria/' . $id . '?page=' . $ir,
                        'page' => $ir,
                        'active' => ( $page == $ir ? 'active' : '' )
                    ]);
                endif;
            endfor;

            /*
             * MOSTRA O TEMPLATE
             */
            $tpl->setTpl('category', array(
                'cat' => $category->getValues(),
                'products' => $pagination['data'],
                'links' => $links,
                'currentpage' => $page,
                'totalPage' => $totalPages
                    )
            );
        endif; // !categoryData

    endif; //verifica se o $id passado na url é numero
});
