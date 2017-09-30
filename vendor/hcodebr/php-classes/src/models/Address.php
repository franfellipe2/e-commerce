<?php

namespace Hcode\Models;

use \Hcode\DB\Sql;
use \Hcode\Model;
use Hcode\Models\User;

class Address extends Model {

    private $cep;
    private $data;
    private $error;

    /**
     * Recupera os dados do endereço atraves do https://viacep.com.br/
     * @param int $nrcep
     * @return array
     */
    public function get($nrcep) {

        $this->cep = $nrcep;
        $this->loadAddress();

        if (!empty($this->data['logradouro'])):

            $this->setData([
                'idaddress' => $this->getIdaddress(),
                'idperson' => User::getUserIdBySession(),
                'desaddress' => $this->data['logradouro'],
                'descomplement' => $this->data['complemento'],
                'descity' => $this->data['localidade'],
                'desstate' => $this->data['uf'],
                'descountry' => 'brasil',
                'deszipcode' => $this->data['cep'],
                'desdistrict' => $this->data['bairro']
            ]);

            unset($this->data);

            return $this->getValues();

        else:

        endif;
    }

    /**
     * Recupera os dados do endereço atraves do https://viacep.com.br/ por meio do objto cURL do php
     * @param int $nrcep
     * @return array
     */
    private function loadAddress() {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://viacep.com.br/ws/$this->cep/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $this->data = json_decode(curl_exec($ch), true);

        curl_close($ch);
    }

    public function save() {

        $this->dataValidate();

        $data = [
            ':idaddress' => $this->getIdaddress(),
            ':idperson' => User::getUserIdBySession(),
            ':desaddress' => utf8_decode($this->getdesaddress()),
            ':descomplement' => utf8_decode($this->getdescomplement()),
            ':descity' => utf8_decode($this->getdescity()),
            ':desstate' => utf8_decode($this->getdesstate()),
            ':descountry' => utf8_decode($this->getdescountry()),
            ':deszipcode' => $this->getdeszipcode(),
            ':desdistrict' => utf8_decode($this->getdesdistrict())
        ];       


        $sql = new Sql();
        $result = $sql->select('Call sp_addresses_save( :idaddress, :idperson , :desaddress, :descomplement, :descity, :desstate, :descountry, :deszipcode, :desdistrict )', $data);        
    }

    public function getError() {
        return $this->error;
    }

    /**
     * Faz a validação dos dados
     */
    private function dataValidate() {

        if (empty($_POST['desaddress'])):
            $this->error[] = 'Endereço não Informado!';
        endif;
        if (empty($_POST['descity'])):
            $this->error[] = 'Cidade não Informada!';
        endif;
        if (empty($_POST['desstate'])):
            $this->error[] = 'Estado UF não Informado!';
        endif;
        if (empty($_POST['descountry'])):
            $this->error[] = 'Paíz não informado!';
        endif;
        if (empty($_POST['deszipcode'])):
            $this->error[] = 'CEP não informado!';
        endif;
        if (empty($_POST['desdistrict'])):
            $this->error[] = 'Bairro não informado!';
        endif;

        if ($this->error == null):
            true;
        else:
            false;
        endif;
    }

}
