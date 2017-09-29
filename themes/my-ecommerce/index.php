<?php
$url[0] = (!empty($url[0]) ? $url[0] : '' );

switch ($url[0]):
    case '':
        require THEME_PATH . DIRECTORY_SEPARATOR . 'home.php';
        break;
    case 'categoria':
        require THEME_PATH . DIRECTORY_SEPARATOR . 'category.php';
        break;
    case 'product':
        require THEME_PATH . DIRECTORY_SEPARATOR . 'product.php';
        break;
    case 'carrinho':
        require THEME_PATH . DIRECTORY_SEPARATOR . 'carrinho.php';
        break;
    case 'login':
        require THEME_PATH . DIRECTORY_SEPARATOR . 'login.php';
    case 'checkout':
        require THEME_PATH . DIRECTORY_SEPARATOR . 'checkout.php';
endswitch;






