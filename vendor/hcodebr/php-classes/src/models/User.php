<?php

namespace Hcode\Models;

use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model {

    const SESSION = 'User';
    //Tabela do usuario no banco de dados
    const tbUser = 'tb_users';

    static public function login($userName, $password) {

        $sql = new Sql();
        $result = $sql->select('SELECT * FROM ' . self::tbUser . ' WHERE user_idname = :idname', array('idname' => $userName));

        if (count($result) === 0):

            throw new \Exception('Usuário inexistente ou senha inválida');

        else:

            $data = $result[0];

            if (password_verify($password, $data['user_pass']) !== true):

                throw new \Exception('Usuário inexistente ou senha inválida');

            else:

                $user = new User;
                /*
                 * Armazena os dados no atributo values da class model
                 * e cria atraves do metodo mágico _call os metodos geters e seters
                 * dos dados informados
                 */
                $user->setData($data);

                $_SESSION[self::SESSION] = $user->getValues();

                return $user;

            endif; //END check password

        endif; //END check result
    }

    /**
     * Verifica se existe um usuário logado e protege areas restritas através do parametro $level
     * @param int $level Nível de permisão do usuário: 1 cliente, 2 editor, 3 administrador
     * @return boolean
     */
    public static function verifyLogin($level = 1) {

        if (!isset($_SESSION[self::SESSION])):
            header('location: ' . HOME . '/admin/login/');
            exit;
        elseif ($_SESSION[self::SESSION]['user_id'] <= 0 || $_SESSION[self::SESSION]['user_level'] < $level):
            header('location: ' . HOME . '/admin/login/?access=denied');
            exit;
        else:
            return true;
        endif;
    }

    /**
     * Verifica se existe um usuário logado com o nivel especificado no parametro $level
     * @param int $level Nivel do usuário logado:  1 cliente, 2 editor, 3 administrador
     */
    public static function loginLevel($level) {        
        if (isset($_SESSION[self::SESSION]) && $_SESSION[self::SESSION]['user_id'] > 0 && $_SESSION[self::SESSION]['user_level'] == $level):            
            return true;
        else:
            return false;
        endif;
    }

    public static function logout() {
        $_SESSION[self::SESSION] = null;
        header('location: ' . HOME . '/admin/login');
        exit;
    }

    public static function listAll() {
        
    }

}
