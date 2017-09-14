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

    public static function verifyLogin($level) {

        if (
                !isset($_SESSION[self::SESSION]) || $_SESSION[self::SESSION]['user_id'] <= 0 || $_SESSION[self::SESSION]['user_level'] < $level
        ):
            header('location: ' . HOME . '/admin/login/?access=false');
            exit;
        else:
            return true;
        endif;
    }

    public static function logout() {
        unlink($_SESSION[self::SESSION]);
    }

    public static function listAll() {
        
    }

}
