<?php

namespace App\Models;

use MF\Model\Model;

class Seguidor extends Model {
    private $id;
    private $id_usuario_seguindo;

    public function __get($atr) {
        return $this->$atr;
    }
    public function __set($atr, $valor) {
        $this->$atr = $valor;
    }

    public function seguir() {
        if($this->segue()) return true;

        $query = '
            insert into usuarios_seguidores(id, id_usuario_seguindo)
            values(:id, :id_usuario_seguindo)
        ';
        $this->prepareExecQuery($query);
    }
    
    public function deixarDeSeguir() {
        $query = '
            delete from usuarios_seguidores
            where id = :id and id_usuario_seguindo = :id_usuario_seguindo
        ';
        $this->prepareExecQuery($query);
    }

    private function segue() {//
        $query = '
            select *
            from usuarios_seguidores
            where id = :id and id_usuario_seguindo = :id_usuario_seguindo
        ';

        if(empty($this->prepareExecQuery($query))) return false;
        return true;
    }
    
    private function prepareExecQuery($query) {
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->bindValue(':id_usuario_seguindo', $this->__get('id_usuario_seguindo'));
        $stmt->execute();
        return $stmt->fetch();
    }
}