<?php

namespace Hcode\Models;

use \Hcode\DB\Sql;
use \Hcode\Model;
use Hcode\Models\Cart;

class Order extends Model {

    const ERROR = 'msg_error';
    const SUCESS = 'msg_sucess';

    /**
     * Salva a ordem de pagamento no banco de dados
     * @return type
     */
    public function save() {

        $data = [
            ':idorder' => $this->getIdorder(),
            ':idcart' => $this->getIdcart(),
            ':iduser' => $this->getUser_id(),
            ':idstatus' => $this->getIdstatus(),
            ':idaddress' => $this->getIdaddress(),
            ':vltotal' => $this->getVltotal()
        ];

        
        $sql = new Sql();
        $result = $sql->select('CALL sp_orders_save(:idorder, :idcart, :iduser, :idstatus, :idaddress, :vltotal)', $data);

        if (count($result) > 0):
            $this->setData($result[0]);
            return $result[0];
        endif;
    }

    /**
     * carrega os dados da ordem de pagamento com todos os dados das tabelas relacionadas
     */
    public function get($idorder) {

        $sql = new Sql();

        $result = $sql->select('SELECT * 
                    FROM tb_orders a
                    INNER JOIN tb_ordersstatus b USING(idstatus)
                    INNER JOIN tb_carts c USING(idcart)
                    INNER JOIN tb_users d ON d.user_id = a.iduser
                    INNER JOIN tb_addresses e USING(idaddress)
                    INNER JOIN tb_persons f USING(person_id)
                    WHERE idorder = :idorder', [':idorder' => $idorder]
        );

        if (count($result) > 0):
            $result[0]['vlsubtotal'] = $result[0]['vltotal'] - $result[0]['vlfreight'];
            $this->setData($result[0]);

            return $result[0];

        endif;
    }

    /**
     * Carraga a lista de produtos
     */
    public function getProducts() {

        $cart = new Cart();

        return $cart->listProducts($this->getIdcart());
    }

    /**
     * Lista todas os orders
     */
    public static function listAll() {

        $sql = new Sql();

        $result = $sql->select(
                '
                    SELECT * 
                    FROM tb_orders a
                    INNER JOIN tb_ordersstatus b USING(idstatus)
                    INNER JOIN tb_carts c USING(idcart)
                    INNER JOIN tb_users d ON d.user_id = a.iduser                    
                    INNER JOIN tb_addresses e USING(idaddress)
                    INNER JOIN tb_persons f USING(person_id)
                    ORDER BY a.dtregister DESC
                   '
        );

        if (count($result) > 0):
            return $result;
        endif;
    }

    /**
     * Deleta pedido
     */
    public static function delete($idorder) {

        $sql = new Sql();
        $sql->query('DELETE FROM `tb_orders` WHERE `idorder` = :idorder', [
            'idorder' => $idorder
        ]);
    }

    /**
     * Registra mensagens de sucesso
     * @param string $msg
     */
    public static function setMsgSucess($msg) {
        $_SESSION[Order::SUCESS] = $msg;
    }

    /**
     * Retorna mensagens de sucesso registradas pelo metodo setMsgSucess($msg)
     * @return string
     */
    public static function getMsgSucess() {

        if (!empty($_SESSION[Order::SUCESS])):

            $msg = $_SESSION[Order::SUCESS];
            self::clearMsgSucess();

            return $msg;

        endif;
    }

    public static function clearMsgSucess() {
        $_SESSION[Order::SUCESS] = null;
    }

    /**
     * Registra mensagens de Erro
     * @param string $msg
     */
    public static function setMsgError($msg) {

        $_SESSION[Order::ERROR] = $msg;
    }

    /**
     * Retorna mensagens de Erro registradas pelo metodo setMsgError($msg)
     * @return string
     */
    public static function getMsgError() {

        if (!empty($_SESSION[Order::ERROR])):

            $msg = $_SESSION[Order::ERROR];
            self::clearMsgError();

            return $msg;

        endif;
    }

    public static function clearMsgError() {
        $_SESSION[Order::ERROR] = null;
    }

}
