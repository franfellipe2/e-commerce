<?php

namespace Hcode\Models;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

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
        
        $sql->query('DELETE FROM '.self::DB_TABLE.' WHERE idcategory = :idcategory', array(':idcategory' => $idcategory));
        
    }

}
