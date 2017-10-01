<?php

namespace Hcode\Models;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

class User extends Model {

    const SESSION = 'User';
    const SECRET = '55330009701803289326473643785709';
    //Tabela do usuario no banco de dados
    const tbUser = 'tb_users';

    private $error;
    private static $dataSession;

    /**
     * Responsável por efetuar o login no sistema
     * @param string $userName Nome de login do usuario
     * @param type $password Senha do usuario
     * @return \Hcode\Models\User
     * @throws \Exception
     */
    static public function login($userName, $password) {

        $sql = new Sql();
        $result = $sql->select('SELECT * FROM ' . self::tbUser . ' WHERE user_login = :login', array('login' => $userName));

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
    public static function verifyLogin($level = 1, $inAdmin = true) {

        if (!isset($_SESSION[self::SESSION])):

            if ($inAdmin):
                header('location: ' . HOME . '/admin/login/');
                exit;
            else:
                header('location: ' . HOME . '/login/');
                exit;
            endif;

        elseif ($_SESSION[self::SESSION]['user_id'] <= 0 || $_SESSION[self::SESSION]['user_level'] < $level):

            if ($inAdmin):
                header('location: ' . HOME . '/admin/login/');
                exit;
            else:
                header('location: ' . HOME . '/login/');
                exit;
            endif;

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

    /**
     * Desloga o usuário do sistema
     */
    public static function logout($urlRoute = '/admin/login') {
        $_SESSION[self::SESSION] = null;
        header('location: ' . HOME . $urlRoute);
        exit;
    }

    /**
     * Responsável por cadastrar o usuário
     */
    public function save() {

        if ($this->dataValidate()):

            $bParams = [
                ':name' => $this->getPerson_name(),
                ':login' => $this->getUser_login(),
                ':pass' => password_hash($this->getUser_pass(), PASSWORD_DEFAULT),
                ':mail' => $this->getPerson_mail(),
                ':phone' => $this->getPerson_phone(),
                ':level' => (!empty($this->getUser_level()) ? $this->getUser_level() : 1 )
            ];

            $params = implode(',', array_keys($bParams));

            $sql = new Sql;
            $result = $sql->select("call sp_users_save({$params})", $bParams);
            return $result[0];
        else:
            return false;
        endif;
    }

    public function update($noValidates = '') {

        //Dados que não serão validados
        $noValidates = array('user_pass');
        if ($_POST['user_login'] == $this->getUser_login()):
            array_push($noValidates, 'user_login');
        endif;

        //valida os dados
        if ($this->dataValidate($noValidates)):

            $bParams = [
                ':user_id' => $this->getUser_id(),
                ':name' => $this->getPerson_name(),
                ':login' => $this->getUser_login(),
                ':mail' => $this->getPerson_mail(),
                ':phone' => $this->getPerson_phone(),
                ':level' => (!empty($this->getUser_level()) ? $this->getUser_level() : 1 )
            ];

            $params = implode(',', array_keys($bParams));

            $sql = new Sql;
            $result = $sql->select("call sp_usersupdate_save({$params})", $bParams);
            $this->setData($result[0]);
            return $result[0];
        else:
            return false;
        endif;
    }

    /**
     * Deleta o usuário
     * @param int $user_id chave primaria do usuário no banco de dados
     */
    public static function delete($user_id) {
        if (self::getUserById($user_id) && $_SESSION[self::SESSION]['user_id'] != $user_id):
            $id = (int) $user_id;
            $sql = new Sql;
            $sql->select("call sp_users_delete(:id)", array(':id' => $id));
        endif;
    }

    /**
     * Validação dos dados do usuário
     * @param array $noValid Campos que não serão validados
     * @return boolean
     */
    private function dataValidate($noValid = array()) {

        $data = array_map('trim', array_map('strip_tags', filter_input_array(INPUT_POST, FILTER_DEFAULT)));

        //Valide Name
        if (!in_array('person_name', $noValid)):
            if (empty($data['person_name'])) {
                $this->error['person_name'] = ' Digite o nome!';
            } elseif (strlen($data['person_name']) > 64) {
                $this->error['person_name'] = ' Nome muito extenso, digite no máxio 64 caracteres!';
            }
        endif;

        //Valide Login
        if (!in_array('user_login', $noValid)):
            if (empty($data['user_login'])):
                $this->error['user_login'] = ' Digite um nome de usuário!';
            elseif (self::getUserByLogin($data['user_login'])):
                $this->error['user_login'] = 'Este login já existe no sistema, digite um outro nome de usuário!';
            endif;
        endif;

        //Valide Password
        if (!in_array('user_pass', $noValid)):
            $nPass = strlen($data['user_pass']);
            if (empty($data['user_pass'])):
                $this->error['user_pass'] = ' Digite uma senha!';
            elseif ($nPass < 5 || $nPass > 8):
                $this->error['user_pass'] = ' A senha deve ter entre 5 e 8 caracteres!';
            else:
                if (empty($data['user_rpass'])):
                    $this->error['user_rpass'] = ' Repita a senha!';
                elseif ($data['user_pass'] != $data['user_rpass']):
                    $this->error['user_rpass'] = ' As senhas informadas são diferentes!';
                endif;
            endif;
        endif;

        $this->setData($data);

        if ($this->error):
            return false;
        else:
            return true;
        endif;
    }

    /**
     * Lista todos os usuarios do sistema
     * @return array-or-false
     */
    public static function listAll() {
        $users = new User();
        $sql = new Sql();
        $results = $sql->select('SELECT * FROM `tb_users` a INNER JOIN tb_persons b USING(person_id) ORDER by b.person_id DESC');

        if (count($results) > 0):
            $users->setData($results);
            return $users->getValues();
        else:
            return false;
        endif;
    }

    /**
     * Enviar email para recuperação de senha
     * @param string $email
     */
    public static function sendForgot($email, $inAdmin = true) {

        $user = self::getUserByEmail($email);

        if ($user):
            $sql = new Sql();
            $resultRecovery = $sql->select('CALL sp_userspasswordsrecoveries_create(:user_id, :user_ip)', array(
                ':user_id' => $user['user_id'],
                ':user_ip' => $_SERVER['REMOTE_ADDR']
            ));

            if (count($resultRecovery) === 0):
                throw new Exception('Não foi possível recuperar a senha');
            else:

                $encrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, self::SECRET, $resultRecovery[0]['idrecovery'], MCRYPT_MODE_ECB);
                $code = base64_encode($encrypt);

                if ($inAdmin === true):
                    $link = ADMIN_URL . "/forgot/reset?code=$code";
                else:
                    $link = HOME . "/forgot/reset?code=$code";
                endif;

                $mailer = new Mailer(
                        $user['person_mail'], $user['person_name'], 'forgot', 'Redefinir senha', array(
                    'name' => $user['person_name'],
                    'link' => $link
                        )
                );
                $mailer->send();
                return array_merge($user, $resultRecovery[0]);

            endif;

        endif;
    }

    public static function validForgotDecrypt($code, $inAdmin = true) {

        $idrecovery = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, self::SECRET, base64_decode($code), MCRYPT_MODE_ECB);
        $sql = new Sql;
        $result = $sql->select('SELECT * FROM tb_userspasswordsrecoveries a
                                INNER join tb_users b
                                WHERE a.iduser = b.user_id
                                AND idrecovery = :direcovery
                                AND dtrecovery IS NULL
                                AND DATE_ADD(a.dtregister, INTERVAL 1 HOUR ) > now()', array(':direcovery' => $idrecovery));

        if (count($result) > 0):
            return $result[0];
        else:
            if ($inAdmin === true):
                throw new \Exception('Não foi possível redefiner a senha! O tempo expirou ou a senha já foi alterada. Tente fazer <a href="' . ADMIN_URL . '/login">login</a> ou <a href="' . ADMIN_URL . '/forgot">Redefina a senha novamente!</a>');
            else:
                throw new \Exception('Não foi possível redefiner a senha! O tempo expirou ou a senha já foi alterada. Tente fazer <a href="' . HOME . '/login">login</a> ou <a href="' . HOME . '/forgot">Redefina a senha novamente!</a>');
            endif;

            return false;
        endif;
    }

    public static function setForgotUser($idrecovery) {
        $sql = new Sql;
        $sql->query('UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery', array(':idrecovery' => $idrecovery));
    }

    /**
     * Retorna os dados do usuário
     * @param int $user_id
     * @return false-or-array com os dados do usuário
     */
    public static function getUserById($user_id) {

        $sql = new Sql();
        $results = $sql->select('SELECT * FROM `tb_users` a INNER JOIN tb_persons b USING(person_id) WHERE a.user_id = :id', array(':id' => $user_id));

        if (count($results) > 0):

            $users = new User();
            $users->setData($results);

            return $users->getValues()[0];

        else:
            return false;
        endif;
    }

    /**
     * Retorna os dados do usuário
     * @param int $user_id
     * @return false-or-array com os dados do usuário
     */
    public static function getUserByLogin($login) {

        $login = (string) $login;

        $sql = new Sql();
        $results = $sql->select('SELECT * FROM `tb_users` a INNER JOIN tb_persons b USING(person_id) WHERE a.user_login = :login ORDER by b.person_name', array(':login' => $login));

        if (count($results) > 0):

            $users = new User();
            $users->setData($results);

            return $users->getValues()[0];

        else:
            return false;
        endif;
    }

    /**
     * Retorna os dados do usuário pela sessão de login
     */
    public static function getUserBySession() {

        //verifica se o usuário esta logado       
        if (!User::checkSession()):

            return false;

        else:

            if (self::$dataSession != null):

                return self::$dataSession;

            else:

                $sql = new Sql();
                $results = $sql->select('SELECT * FROM `tb_users` a INNER JOIN tb_persons b USING(person_id) WHERE user_id = :user_id', array(':user_id' => $_SESSION[User::SESSION]['user_id']));

                if (count($results) > 0):

                    $user = new User();
                    $user->setData($results);

                    self::$dataSession = $user->getValues()[0];

                    return self::$dataSession;

                else:
                    return false;
                endif;

            endif;


        endif;
    }

    /**
     * Verifica se existe a sessão de usuário
     */
    public static function checkSession($level = null) {

        //verifica se existe a sessão
        if (!empty($_SESSION[User::SESSION]) && (int) $_SESSION[User::SESSION]['user_id'] > 0):

            if ($level == null || $level >= $_SESSION[User::SESSION]['user_level']):
                return true;
            else:
                return false;
            endif;

        else:

            return false;

        endif;
    }

    /**
     * Retorna os dados do usuário via email
     * @param type $login
     * @return boolean
     */
    public static function getUserByEmail($email) {

        $email = (string) $email;
        $users = new User();

        $sql = new Sql();
        $results = $sql->select(
                'SELECT * FROM tb_persons a
                    INNER JOIN tb_users b
                    USING(person_id)
                    WHERE a.person_mail = :email
                    ', array(':email' => $email
        ));

        if (count($results) > 0):
            $users->setData($results);
            return $users->getValues()[0];
        else:
            return false;
        endif;
    }

    public function getError() {
        return $this->error;
    }

    public function setPassword($newPassword) {

        $password = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = new Sql;

        $result = $sql->query('UPDATE tb_users SET user_pass = :pass WHERE user_id = :user_id', array(
            ':pass' => $password,
            ':user_id' => $this->getUser_id()
        ));
    }

    public static function getUserIdBySession() {

        if (User::checkSession()):
            return $_SESSION[User::SESSION]['user_id'];
        endif;
    }

    /**
     * Lista as orderns de pagamentos (compras realizadas pelo usuário)
     */
    public function getOrders() {
        
        $sql = new Sql();

        $result = $sql->select('SELECT * 
                    FROM tb_orders a
                    INNER JOIN tb_ordersstatus b USING(idstatus)
                    INNER JOIN tb_carts c USING(idcart)
                    INNER JOIN tb_users d ON d.user_id = a.iduser
                    INNER JOIN tb_addresses e USING(idaddress)
                    WHERE d.user_id = :iduser', [':iduser' => User::getUserIdBySession()]
        );

        if (count($result) > 0):            
            return $result;
        endif;
        
    }

}
