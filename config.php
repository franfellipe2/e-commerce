<?php

//DEFINIÇÕES DO BANCO
define('HOSTNAME','localhost');
define('HOSTUSER','root');
define('HOSTPASS','');
define('DBNAME','db_ecommerce');

//DEFINIÇOES DO SITE
define('HOME', 'http://localhost/cursos/PHP-7/2-e-commerce');
define('THEMES_DIR_NAME', 'themes');//Nome da pasta onde estão os temas do site

//DEFINIÇÕES DO TEMA
define('THEME_NAME', 'my-ecommerce');
define('THEME_PATH', __DIR__ . DIRECTORY_SEPARATOR . THEMES_DIR_NAME . DIRECTORY_SEPARATOR . THEME_NAME);
define('THEME_URI', HOME.'/'.THEMES_DIR_NAME.'/'.THEME_NAME);
define('TPL_DIR', THEME_PATH . DIRECTORY_SEPARATOR . 'tpl');
define('CACHE_DIR', THEME_PATH . DIRECTORY_SEPARATOR . 'cache');
