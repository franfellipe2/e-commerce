<?php

namespace Hcode\Models;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Category extends Model {

    const DB_TABLE = 'tb_categories';

    private $error;

    /**
     * Lista todos os usuarios do sistema
     * @return array-or-false
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
                )", array(':idcategory' => $this->getIdcategory())
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

}
