<?php

namespace Hcode;

use Rain\Tpl;

class Page {

    /** @var Tpl */
    private $tpl;
    private $config = array(
        "tpl_dir" => TPL_DIR . DIRECTORY_SEPARATOR,
        "cache_dir" => CACHE_DIR . DIRECTORY_SEPARATOR,
        "debug" => false
    );
    private $options;
    private $defaults = [
        'HOME' => HOME,
        'ADMIN_URL' => ADMIN_URL,
        'THEME_URL' => THEME_URL,        
        'header' => true,
        'footer' => true
    ];

    public function __construct($opts = array(), $debug = false) {

        $this->options = array_merge($this->defaults, $opts);

        // config
        $this->setConfig(array('debug' => $debug));
        Tpl::configure($this->config);
        $this->tpl = new Tpl;

        //Datas
        $this->setData($this->options);

        //Show Header
        if($this->options['header'] === true ) $this->tpl->draw('header');
    }

    //======================================
    // PUBLIC METHODS                      
    //======================================
    public function setTpl($tplName, $data = array(), $returnHTML = false) {
        $this->setData($data);
        return $this->tpl->draw($tplName, $returnHTML);
    }

    public function __destruct() {
        if($this->options['footer'] === true ) $this->tpl->draw('footer');
    }

    public function setConfig(array $arrConfig) {
        $this->config = array_merge($this->config, $arrConfig);
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
