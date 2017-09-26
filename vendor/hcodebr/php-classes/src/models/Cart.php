<?php

namespace Hcode\Models;

use \Hcode\DB\Sql;
use \Hcode\Model;
use Hcode\Models\User;

class Cart extends Model {

    const DB_TABLE = 'tb_carts';
    const SESSION = 'cart';
    const SESSION_ERROR = 'cartError';
    const CEP = 38200000;

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

            //pega pelo id da sessão
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

        return $cart;
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
            return $this->getValues();
        else:
            return false;
        endif;
    }

    /**
     * Retorna os dados do carrinho por id do carrinho
     */
    public function getById($idcart) {

        $sql = new Sql();
        $result = $sql->select('SELECT * FROM ' . self::DB_TABLE . ' WHERE idcart = :idcart', array(
            ':idcart' => $idcart
        ));

        var_dump($result);

        if (count($result) > 0):
            $this->setData($result[0]);
            return $this->getValues();
        else:
            return false;
        endif;
    }

    /**
     * Salva ou altualiza os dados do carrinho
     */
    public function save() {

        $bParams = [
            ':pidcart' => $this->getIdcart(),
            ':pdessessionid' => session_id(),
            ':piduser' => $this->getIduser(),
            ':pdeszipcode' => $this->getDeszipcode(),
            ':pvlfreight' => $this->getVlfreight(),
            ':pnrdays' => $this->getNrdays()
        ];

        $params = implode(',', array_keys($bParams));


        $sql = new Sql;
        $result = $sql->select("call sp_carts_save({$params})", $bParams);

        if (count($result) > 0):
            $this->setData($result[0]);
            return $this->getValues();
        else:
            throw new \Exception('Erro ao cadastrar cadastrar carrinho!');
        endif;
    }

    /**
     * Adicionar produto ao carrinho
     * @param \Hcode\Models\Products $product
     */
    public function addProduct($idproduct) {

        $sql = new Sql();
        $sql->query('INSERT INTO tb_cartsproducts (idcart, idproduct) Values( :idcart, :idproduct) ', [
            ':idcart' => $this->getIdcart(),
            ':idproduct' => $idproduct
        ]);

        $this->updateFreight();
    }

    /**
     * Adicionar produto ao carrinho
     * @param \Hcode\Models\Products $product
     */
    public function removeProduct($idproduct, $all = false) {

        $delAll = ( $all === true ? '' : 'LIMIT 1' );

        $sql = new Sql();
        $sql->query("
            UPDATE tb_cartsproducts 
            SET dtremoved = NOW() 
            WHERE idcart = :idcart 
            AND idproduct = :idproduct
            AND dtremoved IS NULL
            $delAll", [
            ':idcart' => $this->getIdcart(),
            ':idproduct' => $idproduct
        ]);

        $this->updateFreight();
    }

    /**
     * Lista os produtos do carrinho
     */
    public function listProducts() {
        $sql = new Sql();
        $result = $sql->select('
                      select b.desproduct, b.desurl, b.dtregister, b.idproduct,
                      b.vlheight, b.vllength, b.vlprice, b.vlweight, b.vlwidth, count(*) as nrqtd, SUM(b.vlprice) as vltotal
                      FROM tb_cartsproducts a 
                      inner join tb_products b ON a.idproduct = b.idproduct
                      WHERE a.idcart = :idcart AND a.dtremoved is null
                      GROUP BY b.idproduct
                      ORDER BY b.desproduct', [':idcart' => $this->getIdcart()]);
        if (count($result) > 0):
            return Products::cheklist($result);
        else:
            return false;
        endif;
    }

    /**
     * Pega os totais gerais do carrinho como subtotal de preços, frete e total de preços
     */
    public function getProductsTotals() {

        $sql = new Sql();
        $result = $sql->select('
                                SELECT 
                                sum(b.vlprice) as subtotal,
                                sum(b.vllength) as comprimento,
                                max(b.vlheight) as altura,
                                sum(b.vlweight) as peso,
                                sum(b.vlwidth) as largura,
                                count(*) as nrqtd
                                FROM tb_cartsproducts a 
                                inner join tb_products b 
                                on a.idproduct = b.idproduct
                                WHERE a.idcart = :idcart
                                AND dtremoved IS NULL
            ', array(':idcart' => $this->getIdcart())
        );

        if (!empty($result[0]['nrqtd'])):
            return $result[0];
        else:
            return [];
        endif;
    }

    /**
     * Calcula o frete
     */
    public function setFreight($zipcode) {

        $zipcode = str_replace('-', '', $zipcode);
        $totals = $this->getProductsTotals();

        if ($totals):
            $build_query = http_build_query([
                'nCdEmpresa' => '',
                'sDsSenha' => '',
                'nCdServico' => 40010,
                'sCepOrigem' => self::CEP,
                'sCepDestino' => (int) $zipcode,
                'nCdFormato' => 1,
                'nVlDiametro' => 0,
                'nVlPeso' => $totals['peso'],
                'nVlComprimento' => ($totals['comprimento'] < 16 ? 16 : $totals['comprimento'] ),
                'nVlAltura' => ($totals['altura'] < 2 ? 2 : $totals['altura'] ),
                'nVlLargura' => $totals['largura'],
                'nVlValorDeclarado' => $totals['subtotal'],
                'sCdAvisoRecebimento' => 's',
                'sCdMaoPropria' => 's',
                'sDtCalculo' => '',
            ]);

            $xml = simplexml_load_file("http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo?$build_query");
            $result = (array) $xml->Servicos->cServico;

            $this->setDeszipcode($zipcode);
            $this->setVlfreight(Cart::formatValueToDecimal($result['Valor']));
            $this->setNrdays($result['PrazoEntrega']);


            if ($result['MsgErro'] != ''):
                Cart::setMsgError($result['MsgErro']);
            endif;

            $this->save();

            return $result;

        else:

            return false;

        endif;
    }

    /**
     * Formata um numero para o formato decimal padrão do mysql, ex: 2000.00
     */
    public static function formatValueToDecimal($value) {
        $value = str_replace('.', '', $value);
        return str_replace(',', '.', $value);
    }

    /**
     * Seta mensagens de erros via sessao
     */
    public static function setMsgError($msg) {
        $_SESSION[self::SESSION_ERROR] = $msg;
    }

    /**
     * Mostra mensagens de erro via sessão
     */
    public static function getMsgError() {

        if (isset($_SESSION[self::SESSION_ERROR])):

            $msg = $_SESSION[self::SESSION_ERROR];

            $_SESSION[Cart::SESSION_ERROR] = null;

            return $msg;

        endif;
    }

    /**
     * Atualiza o valor do frete
     */
    public function updateFreight() {

        if ($this->getDeszipcode() != ''):
            $this->setFreight($this->getDeszipcode());        
        endif;
    }

    /**
     * Adiciona o total e subtotal do preço
     * @return type
     */
    public function getValues() {

        $this->getCalculateTotal();

        return parent::getValues();
    }

    /**
     * Pega o calculo dos totais do carrinho
     */
    public function getCalculateTotal() {

        $result = $this->getProductsTotals();
        if (!empty($result['subtotal'])):
            $this->setVlsubtotal($result['subtotal']);
            $this->setTotal($result['subtotal'] + $this->getVlfreight());
        else:
            $this->setVlsubtotal(0);
            $this->setTotal(0);
        endif;
        
         if ($this->getDeszipcode() == ''):
            $this->setVlfreight(0);
            $this->setNrdays(0);
        endif;
    }

}
