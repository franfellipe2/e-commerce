<?php

namespace Hcode\DB;

class Sql {

    const HOSTNAME = HOSTNAME;
    const USERNAME = HOSTUSER;
    const PASSWORD = HOSTPASS;
    const DBNAME = DBNAME;

    /** @var \PDO */
    private static $conn;

    public function __construct() {
        if (!self::$conn):
            self::$conn = new \PDO(
                    "mysql:dbname=" . Sql::DBNAME . ";host=" . Sql::HOSTNAME, Sql::USERNAME, Sql::PASSWORD
            );
        endif;
    }

    private function setParams($statement, $parameters = array()) {

        foreach ($parameters as $key => $value) {

            $this->bindParam($statement, $key, $value);
        }
    }

    private function bindParam($statement, $key, $value) {

        if (is_int($value)):
            $statement->bindParam($key, $value, \PDO::PARAM_INT);
        else:
            $statement->bindParam($key, $value);
        endif;
    }

    /**
     * Executa uma instrucao sql, ex: INSERT INTO...
     * 
     * @param string $rawQuery passar aqui a sua instrução sql
     * @param array $params passe aqui os bindParams
     */
    public function query($rawQuery, $params = array()) {

        $stmt = self::$conn->prepare($rawQuery);

        $this->setParams($stmt, $params);

        $stmt->execute();
    }

    /**
     * Retorna os dados de uma instrução sql, ex: SELECT * FROM...
     * 
     * @param string $rawQuery passar aqui a sua instrução sql
     * @param array $params passe aqui os bindParams
     * @return array
     */
    public function select($rawQuery, $params = array()): array {

        $stmt = self::$conn->prepare($rawQuery);

        $this->setParams($stmt, $params);

        $stmt->execute();


        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}

?>