<?php

namespace Hcode;

class PageAdmin extends Page {

    public function __construct($opts = array(), $debug = false) {

        $config = [
            "tpl_dir" => ADMIN_PATH . DIRECTORY_SEPARATOR . '_tpl' . DIRECTORY_SEPARATOR,
            "cache_dir" => ADMIN_PATH . DIRECTORY_SEPARATOR . '_cache' . DIRECTORY_SEPARATOR
        ];
        $this->setConfig($config);
        parent::__construct($opts, $debug);
    }

}
