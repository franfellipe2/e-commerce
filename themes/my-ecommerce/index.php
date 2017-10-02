<?php

$url[0] = (!empty($url[0]) ? $url[0] : '' );
$url[1] = (!empty($url[1]) ? $url[1] : '' );

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
        break;
    case 'checkout':
        require THEME_PATH . DIRECTORY_SEPARATOR . 'checkout.php';
        break;
    case 'forgot':
        require THEME_PATH . DIRECTORY_SEPARATOR . 'forgot.php';
        break;
    case 'profile':

        switch ($url[1]):
            case '':
                require THEME_PATH . DIRECTORY_SEPARATOR . 'profile.php';
                break;
            case 'change-password':
                require THEME_PATH . DIRECTORY_SEPARATOR . 'profile-change-password.php';
                break;
        endswitch;
        break;

    case 'profile-orders':
        require THEME_PATH . DIRECTORY_SEPARATOR . 'profile-orders.php';
        break;
    case 'order':
        require THEME_PATH . DIRECTORY_SEPARATOR . 'order.php';
        break;
endswitch;






