<?php

namespace Hcode\Models;

use Hcode\DB\Sql;

class Ordersstatus {

    const EM_ABERTO = 1;
    const AGUARDANDO_PAGAMENTO = 2;
    const PAGO = 3;
    const ENTREGUE = 4;

    public static function listAll() {
        
        $sql = new Sql();
        
        $result = $sql->select('SELECT * FROM tb_ordersstatus ORDER BY desstatus asc');
        
        if (count($result) > 0):
            return $result;
        endif;
    }

}
