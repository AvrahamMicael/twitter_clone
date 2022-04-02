<?php

namespace App\Models;

use App\Connection;
use MF\Model\Model;

class Info extends Model {
    public function getDados() {
        $query = '
            select titulo, descricao
            from tb_info
        ';
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}