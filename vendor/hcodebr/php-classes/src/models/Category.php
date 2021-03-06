<?php

namespace Hcode\Models;

use \Hcode\DB\Sql;
use \Hcode\Model;
use Hcode\Models\Products;

class Category extends Model {

    const DB_TABLE = 'tb_categories';

    private $error;

    /**
     * Lista todas as categorias do sistema
     * @return array/false
     */
    public static function listAll() {

        $categories = new Category();
        $sql = new Sql();
        $results = $sql->select('SELECT * FROM ' . self::DB_TABLE . ' ORDER BY descategory ASC');

        if (count($results) > 0):
            $categories->setData($results);
            return $categories->getValues();
        else:
            return false;
        endif;
    }

    /**
     * Lista todas as categorias do sistema com paginação
     * @return array/false
     */
    public static function listAllWithPage($currentPage, $limit) {

        $start = (isset($currentPage) && $currentPage > 0 ? (int) $currentPage - 1 : 0 );

        $categories = new Category();
        $sql = new Sql();
        $results = $sql->select('SELECT sql_calc_found_rows * FROM ' . self::DB_TABLE . ' ORDER BY descategory ASC LIMIT :limit OFFSET :offset', [
            ':limit' => (int) $limit,
            ':offset' => (int) $start
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
     * Lista todos as categorias do sistema com paginação
     * @return array/false
     */
    public static function searchWithPage($search, $currentPage, $limit) {

        $start = (isset($currentPage) && $currentPage > 0 ? (int) $currentPage - 1 : 0 );

        $categories = new Category();
        $sql = new Sql();
        $results = $sql->select('
                SELECT sql_calc_found_rows * 
                FROM ' . self::DB_TABLE . '
                WHERE descategory LIKE :s 
                LIMIT :limit OFFSET :offset', [
            ':s' => "%$search%",
            ':limit' => (int) $limit,
            ':offset' => (int) $start
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
     * Retorna os dados da categoria por ID
     */
    public static function getCategoryById($idcategory) {

        $categorie = new Category();
        $sql = new Sql();
        $results = $sql->select('SELECT * FROM ' . self::DB_TABLE . ' WHERE idcategory = :idcategory ORDER BY descategory ASC', array(
            ':idcategory' => $idcategory
        ));

        if (count($results) > 0):
            $categorie->setData($results);
            return $categorie->getValues()[0];
        else:
            return false;
        endif;
    }

    /**
     * Salva os dados da categoria
     */
    public function save() {

        if (!empty($this->getDescategory())):

            $bParams = [
                ':idcategory' => $this->getIdcategory(),
                ':descategory' => $this->getDescategory()
            ];

            $params = implode(',', array_keys($bParams));

            $sql = new Sql;
            $result = $sql->select("call sp_categories_save({$params})", $bParams);

            Category::updateMenuFooter();

            return $result[0];

        else:

            $this->error['descategory'] = 'Nome da categoria em branco';
            return false;

        endif;
    }

    /**
     * Deleta a categoria
     */
    public static function delete($idcategory) {

        $sql = new Sql();

        $sql->query('DELETE FROM ' . self::DB_TABLE . ' WHERE idcategory = :idcategory', array(':idcategory' => $idcategory));

        Category::updateMenuFooter();
    }

    /**
     * Atualiza o arquivo do menu do rodape
     */
    public static function updateMenuFooter() {

        $tplName = TPL_DIR . DIRECTORY_SEPARATOR . 'menu-footer-categories.html';
        $cats = Category::listAll();
        $html = [];

        foreach ($cats as $cat):
            array_push($html, "<li><a href='" . HOME . "/categoria/{$cat['idcategory']}'>{$cat['descategory']}</a></li>");
        endforeach;


        file_put_contents($tplName, $html);
    }

    /**
     * Mostra a lista de produtos
     * @param boolean $related se true mostra os pordutos relacionados com a categoria, se false mostro os que não estão relacionados com a categoria     * 
     */
    public function getProducts($related = true) {

        $onRelated = ( $related === true ? '' : 'not' );

        $sql = new Sql();
        $results = $sql->select(
                "SELECT * FROM tb_products WHERE idproduct {$onRelated} in(
                select a.idproduct
                from tb_products a
                inner join tb_categoriesproducts b on a.idproduct = b.idproduct
                WHERE b.idcategory = :idcategory
                ) ORDER BY idproduct DESC", array(':idcategory' => $this->getIdcategory())
        );

        if (count($results) > 0) {

            foreach ($results as &$rowp):
                $p = new Products();
                $p->setData($rowp);
                $rowp = $p->getValues();
            endforeach;

            return $results;
        } else {
            return false;
        }
    }

    /**
     * Relaciona o produto com a categoria
     * @param int $idproduct
     */
    public function addProduct(int $idproduct) {

        $sql = new Sql();
        $sql->query(
                'INSERT INTO tb_categoriesproducts (idcategory, idproduct) values ( :idcategory, :idproduct )', [
            ':idcategory' => $this->getIdcategory(),
            ':idproduct' => $idproduct
                ]
        );
    }

    /**
     * Desfaz o relacionamento do produto com a categoria
     * @param int $idproduct
     */
    public function removeProduct(int $idproduct) {

        $sql = new Sql();
        $sql->query(
                'DELETE FROM tb_categoriesproducts WHERE idcategory = :idcategory AND idproduct =  :idproduct', [
            ':idcategory' => $this->getIdcategory(),
            ':idproduct' => $idproduct
                ]
        );
    }

    /**
     * Mostra os produtos com paginaçao
     */
    public function getProductsPage(int $page, int $limit) {

        $start = (isset($_GET['page']) ? $_GET['page'] - 1 : 0 );

        $sql = new Sql();
        $result = $sql->select(
                'SELECT sql_calc_found_rows * FROM  tb_products a
                INNER JOIN tb_categoriesproducts b ON a.idproduct = b.idproduct
                WHERE b.idcategory = :idcategory
                LIMIT :limit OFFSET :offset
                ', [
            ':idcategory' => $this->getIdcategory(),
            ':limit' => $limit,
            ':offset' => $start
                ]
        );

        $total = $sql->select('select found_rows() as nrtotal');

        if (count($result) > 0):
            return [
                'data' => Products::cheklist($result),
                'total' => $total[0]['nrtotal']
            ];
        else:
            return false;
        endif;
    }

}
