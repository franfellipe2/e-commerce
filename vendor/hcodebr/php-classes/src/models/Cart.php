<?php

namespace Hcode\Models;

use \Hcode\DB\Sql;
use \Hcode\Model;
use Hcode\Models\User;

class Cart extends Model {

    const DB_TABLE = 'tb_carts';
    const SESSION = 'cart';

    private $error;

    /**
     * Lista todos os carrinho do sistema
     * @return array-or-false
     */
    public static function listAll() {

        $carrinho = new Cart();
        $sql = new Sql();
        $results = $sql->select('SELECT * FROM ' . self::DB_TABLE . ' ORDER BY dtregister DESC');

        if (count($results) > 0):
            return $results;
        else:
            return false;
        endif;
    }

    function getError() {
        return $this->error;
    }

    /**
     * Carrega a sessão do carrinho
     */
    public static function getSession() {

        $cart = new Cart();

        //verifica se existe a sessão
        if (!empty($_SESSION[Cart::SESSION]) && (int) $_SESSION[Cart::SESSION]['idcart'] > 0):

            //carrega o carriho pelo id do carrionho
            $cart->getById($_SESSION[Cart::SESSION]['idcart']);

        else:

            //carrega o carrinho pelo id da sessao
            $cart->getBySessionId();

            //cria um novo carrinho
            if ($cart->geIdcart() < 1):

                $user = User::getUserBySession();

                $data = [
                    'dessessionid' => session_id(),
                    'iduser' => (!empty($user['user_id']) ? $user['user_id'] : null)
                ];

                $cart->setData($data);
                $cart->save();                
                $cart->setToSession();
                
            endif;

        endif; //END. verifica se existe a sessão
    }    
    
    public function setToSession() {
        $_SESSION[Cart::SESSION] = $this->getValues();
    }

    //Carre o carrinho pelo id da sessão
    public function getBySessionId() {

        $sql = new Sql();

        $result = $sql->select('SELECT * FROM ' . self::DB_TABLE . ' WHERE dessessionid = :dessessionid', array(
            ':dessessionid' => session_id()
        ));

        if (count($result) > 0):
            $this->setData($result[0]);
        else:
            return false;
        endif;
    }

    /**
     * Retorna os dados do carrinho por id do carrinho
     */
    public function getById($idcart) {

        $cart = new Cart();
        $sql = new Sql();
        $result = $sql->select('SELECT * FROM ' . self::DB_TABLE . ' WHERE idcart = :idcart', array(
            ':idcart' => $idcart
        ));

        if (count($result) > 0):
            $cart->setData($result[0]);
        else:
            return false;
        endif;
    }

    /**
     * Salva ou altualiza os dados do carrinho
     */
    public function save() {

        //if (!empty($this->dataValidate())):

            $this->slugCreate($this->getDesproduct());

            $bParams = [
                ':pidcart' => $this->getIdcart(),
                ':pdessessionid' => session_id(),
                ':piduser' => $this->getiduser(),
                ':pdeszipcode' => $this->getdeszipcode(),
                ':pvlfreight' => $this->getlfreight(),
                ':pnrdays' => $this->getnrdays()
            ];

            $params = implode(',', array_keys($bParams));


            $sql = new Sql;
            $result = $sql->select("call sp_carts_save({$params})", $bParams);

            if (count($result) > 0):
                $this->setData($result[0]);
                return $result[0];
            else:
                throw new \Exception('Erro ao cadastrar cadastrar carrinho!');
            endif;

//        else:
//
//            return false;
//
//        endif;
    }

    private function dataValidate() {

        $data = array_map('trim', array_map('strip_tags', filter_input_array(INPUT_POST, FILTER_DEFAULT)));

        //Valide Name
        if (empty($data['desproduct'])) {
            $this->error['desproduct'] = 'Digite o nome do produto!';
        } elseif (strlen($data['desproduct']) > 64) {
            $this->error['desproduct'] = ' Nome muito extenso, digite no máxio 64 caracteres!';
        }

        $this->setData($data);

        if ($this->error):
            return false;
        else:
            return true;
        endif;
    }

}
