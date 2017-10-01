<?php

namespace Hcode\Models;

use \Hcode\DB\Sql;
use \Hcode\Model;
use Hcode\Models\Cart;

class Order extends Model {

    /**
     * Salva a ordem de pagamento no banco de dados
     * @return type
     */
    public function save() {

        $data = [
            ':idorder' => $this->getIdorder(),
            ':idcart' => $this->getIdcart(),
            ':iduser' => $this->getIduser(),
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
                    WHERE idorder = :idorder', [':idorder' => $idorder]
        );

        if (count($result) > 0):
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

}
