<?php

namespace Hcode\Models;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Products extends Model {

    const DB_TABLE = 'tb_products';

    private $error;

    /**
     * Lista todos os produtos do sistema
     * @return array-or-false
     */
    public static function listAll() {

        $products = new Products();
        $sql = new Sql();
        $results = $sql->select('SELECT * FROM ' . self::DB_TABLE . ' ORDER BY idproduct DESC');

        if (count($results) > 0):

            return Products::cheklist($results);

        else:
            return false;
        endif;
    }

    /**
     * Lista todos os produtos do sistema com paginação
     * @return array/false
     */
    public static function listAllWithPage($currentPage, $limit) {

        $start = (isset($currentPage) && $currentPage > 0 ? (int) $currentPage - 1 : 0);

        $products = new Products();
        $sql = new Sql();
        $results = $sql->select('SELECT sql_calc_found_rows * FROM ' . self::DB_TABLE . '                                  
                                 ORDER BY idproduct DESC LIMIT :limit OFFSET :offset', [
            ':limit' => $limit,
            ':offset' => $start
        ]);

        $total = $sql->select('select found_rows() as nrtotal');

        if (count($results) > 0):
            return [
                'data' => $results,
                'total' => $total[0]['nrtotal']
            ];
        else:
            return false;
        endif;
    }

    /**
     * Buscas produtos do sistema com paginação
     * @return array/false
     */
    public static function searchWithPage($search, $currentPage, $limit) {

        $start = (isset($currentPage) && $currentPage > 0 ? (int) $currentPage - 1 : 0);

        $products = new Products();
        $sql = new Sql();
        $results = $sql->select('SELECT sql_calc_found_rows * FROM ' . self::DB_TABLE . '  
                                 WHERE desproduct LIKE :s
                                 LIMIT :limit OFFSET :offset', [
            ':s' => $search,
            ':limit' => $limit,
            ':offset' => $start
        ]);

        $total = $sql->select('select found_rows() as nrtotal');

        if (count($results) > 0):
            return [
                'data' => $results,
                'total' => $total[0]['nrtotal']
            ];
        else:
            return false;
        endif;
    }

    /**
     * Relaciona e retorna todos os dados do produto de uma lista de produtos
     * @return array
     */
    public static function cheklist($products) {
        foreach ($products as &$rowp):
            $p = new Products();
            $p->setData($rowp);
            $rowp = $p->getValues();
        endforeach;

        return $products;
    }

    function getError() {
        return $this->error;
    }

    /**
     * Retorna os dados da categoria por ID
     */
    public static function getProductById($idproduct) {

        $product = new Products();
        $sql = new Sql();
        $result = $sql->select('SELECT * FROM ' . self::DB_TABLE . ' WHERE idproduct = :idproduct', array(
            ':idproduct' => $idproduct
        ));

        if (count($result) > 0):
            $product->setData($result[0]);
            return $product->getValues();
        else:
            return false;
        endif;
    }

    /**
     * Retorna os dados da categoria por URL amigável do titulo da categoria
     */
    public static function getProductBySlug($slug) {

        $product = new Products();
        $sql = new Sql();
        $result = $sql->select('SELECT * FROM ' . self::DB_TABLE . ' WHERE desurl = :desurl', array(
            ':desurl' => $slug
        ));

        if (count($result) > 0):
            $product->setData($result[0]);
            return $product->getValues();
        else:
            return false;
        endif;
    }

    /**
     * Salva os dados da categoria
     */
    public function save() {

        if (!empty($this->dataValidate())):

            $this->slugCreate($this->getDesproduct());

            $bParams = [
                ':idproduct' => $this->getIdproduct(),
                ':desproduct' => $this->getDesproduct(),
                ':vlprice' => $this->getVlprice(),
                ':vlwidth' => $this->getVlwidth(),
                ':vlheight' => $this->getVlheight(),
                ':vllength' => $this->getVllength(),
                ':vlweight' => $this->getVlweight(),
                ':desurl' => $this->getDesurl()
            ];

            $params = implode(',', array_keys($bParams));


            $sql = new Sql;
            $result = $sql->select("call sp_products_save({$params})", $bParams);

            if (count($result) > 0):
                $this->setPhoto();
                return $result[0];
            else:
                throw new \Exception('Erro ao cadastrar produto!');
            endif;

        else:

            return false;

        endif;
    }

    private function dataValidate() {

        $data = array_map('trim', array_map('strip_tags', filter_input_array(INPUT_POST, FILTER_DEFAULT)));

        //Valide Name
        if (empty($data['desproduct'])) {
            $this->error['desproduct'] = 'Digite o nome do produto!';
        } elseif (strlen($data['desproduct']) > 64) {
            $this->error['desproduct'] = ' Nome muito extenso, digite no máxio 64 caracteres!';
        }

        //Valide Price
        if (empty($data['vlprice'])):
            $this->error['vlprice'] = 'Digite o valor do produto!';
        elseif (!is_float((float) $data['vlprice'])):
            $this->error['vlprice'] = 'Dados inválidos!';
        endif;

        //Valide Width
        if (empty($data['vlwidth'])):
            $this->error['vlwidth'] = 'Digite a largura do produto em centimentros!';
        elseif (!is_float((float) $data['vlwidth'])):
            $this->error['vlwidth'] = 'Dados inválidos!';
        endif;

        //Valide Height
        if (empty($data['vlheight'])):
            $this->error['vlheight'] = 'Digite a altura do produto em centimentros!';
        elseif (!is_float((float) $data['vlheight'])):
            $this->error['vlheight'] = 'Dados inválidos!';
        endif;

        //Valide Length
        if (empty($data['vllength'])):
            $this->error['vllength'] = 'Digite o comprimento do produto em centimentros!';
        elseif (!is_float((float) $data['vllength'])):
            $this->error['vllength'] = 'Dados inválidos!';
        endif;

        //Valide Height
        if (empty($data['vlweight'])):
            $this->error['vlweight'] = 'Digite o Peso do produto em kilos!';
        elseif (!is_float((float) $data['vlweight'])):
            $this->error['vlweight'] = 'Dados inválidos!';
        endif;

        $this->setData($data);

        if ($this->error):
            return false;
        else:
            return true;
        endif;
    }

    /**
     * Deleta a categoria
     */
    public static function delete($idProduct) {

        $sql = new Sql();

        $sql->query('DELETE FROM ' . self::DB_TABLE . ' WHERE idproduct = :idproduct', array(':idproduct' => $idProduct));
    }

    /**
     * Cria uma string no formato de url amigável retirando todos os caracteres especiais e substituido espaço por -
     * @param string $title
     * @return string
     */
    private function slugCreate($title) {

        $format = array();
        $format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
        $format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

        $Data = strtr(utf8_decode($title), utf8_decode($format['a']), utf8_decode($format['b']));
        $Data = strip_tags(trim($Data));
        $Data = str_replace(' ', '-', $Data);
        $Data = str_replace(array('----------', '---------', '--------', '-------', '------', '-----', '----', '---', '--'), '-', $Data);

        $setSlug = strtolower(utf8_encode($Data));

        $sql = new Sql();
        $getSlugs = $sql->select('SELECT * FROM ' . self::DB_TABLE . ' WHERE desproduct = :desproduct', array(':desproduct' => $title));

        $produc = Products::getProductById($this->getIdproduct());
        $currentSlug = (!empty($produc['desurl']) ? $produc['desurl'] : null );

        $slug = $setSlug;

        if (count($getSlugs) > 0):

            //Cria um slug unico
            $increment = 0;
            while (in_array($slug, array_column($getSlugs, 'desurl')) && $slug != $currentSlug):
                $increment ++;
                $slug = $setSlug . '-' . $increment;
            endwhile;

        endif;

        $this->setDesurl($slug);

        return $slug;
    }

    /**
     * Altera a foto de capa do produto
     */
    public function setPhoto() {

        if (!empty($_FILES['file']['name'])):

            $file = $_FILES['file'];

            $ext = explode('.', $file['name']);
            $ext = strtolower(end($ext));

            switch ($ext):
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($file['tmp_name']);
                    break;
                case 'png':
                    $image = imagecreatefrompng($file['tmp_name']);
                    break;
                case 'gif':
                    $image = imagecreatefromgif($file['tmp_name']);
                    break;
                default:
                    throw new \Exception('Formato de arquivo inválido! Envie imagem jpg, png ou gif');
            endswitch;

            $fileName = ROOT_PATH . DIRECTORY_SEPARATOR .
                    'uploads' . DIRECTORY_SEPARATOR .
                    'products' . DIRECTORY_SEPARATOR .
                    'cover' . DIRECTORY_SEPARATOR .
                    $this->getIdproduct() . '.jpg';

            imagejpeg($image, $fileName);
            imagedestroy($image);

        endif;
    }

    /**
     * Verifica se o produto tem uma foto de capa
     * @return string
     */
    public function checkPhoto() {

        $fileName = ROOT_PATH . DIRECTORY_SEPARATOR .
                'uploads' . DIRECTORY_SEPARATOR .
                'products' . DIRECTORY_SEPARATOR .
                'cover' . DIRECTORY_SEPARATOR .
                $this->getIdproduct() . '.jpg';

        if (file_exists($fileName)):
            $image = HOME . '/uploads/products/cover/' . $this->getIdproduct() . '.jpg';
        else:
            $image = HOME . '/img/product-cover.jpg';
        endif;

        $this->setDesphoto($image);

        return $this->getDesphoto();
    }

    /**     * 
     * Retorna os dados dos produtos
     * @return array
     */
    public function getValues() {

        $this->checkPhoto();
        $values = parent::getValues();
        return $values;
    }

    public static function getCategories($idproduct) {
        $sql = new Sql();
        $result = $sql->select(' SELECT * FROM tb_categories a
                        inner join tb_categoriesproducts b 
                        on a.idcategory = b.idcategory
                        where b.idproduct = :idproduct', [':idproduct' => $idproduct]
        );
        return $result;
    }

}
