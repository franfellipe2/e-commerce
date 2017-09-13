<?php

namespace Hcode;

class Model {

    private $values;

    public function __call($name, $args) {
        $method = substr($name, 0, 3);
        $fildName = strtolower( substr($name, 3, strlen($name)));

        switch ($method):
            case 'set':
                $this->values[$fildName] = $args[0];
                break;
            case 'get':
                return $this->values[$fildName];
                break;
        endswitch;
    }

    /**
     * Cria os methodos GETERS e SETERS
     * @param array $data dados com os valores
     */
    public function setData(array $data) {
        foreach ($data as $key => $value):
            $this->{'set' . ucfirst($key)}($value);
        endforeach;
    }
    
    public function getValues() {
        return $this->values;
    }

}
