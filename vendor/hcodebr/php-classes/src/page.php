<?php

namespace Hcode;

use Rain\Tpl;

class page {

    /** @var Tpl */
    private $tpl;
    private $options = array();
    private $defaults = array();

    public function __construct($opts = array(), $debug = false) {
        $this->options = array_merge($this->defaults, $opts);
        // config
        $config = array(
            "tpl_dir" => TPL_DIR . DIRECTORY_SEPARATOR,
            "cache_dir" => CACHE_DIR . DIRECTORY_SEPARATOR,
            "debug" => (boolean) $debug
        );
        Tpl::configure($config);

        $this->setData($opts);

        $this->tpl = new Tpl;
        $this->tpl->draw('header');
    }

    //======================================
    // PUBLIC METHODS                      
    //======================================
    public function setTpl($tplName, $data =  array(), $returnHTML = false) {
        $this->setData($data);
        return $this->tpl->draw($tplName, $returnHTML);
    }

    public function __destruct() {
        $this->tpl->draw('footer');
    }

    //======================================
    // PRIVATE METHODS                      
    //======================================
    private function setData($data) {
        foreach ($data as $key => $value):
            $this->tpl->assign($key, $value);
        endforeach;
    }

}
