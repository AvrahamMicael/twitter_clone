<?php

namespace App\Models;

use App\Connection;
use MF\Model\Model;

class Produto extends Model {
    public function getDados() {
        $query = '
            select id, descricao, preco
            from tb_produtos
        ';
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}