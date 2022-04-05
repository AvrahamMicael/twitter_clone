<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model {
    private $id;
    private $nome;
    private $email;
    private $senha;

    public function __get($atr) {
        return $this->$atr;
    }
    public function __set($atr, $valor) {
        $this->$atr = $valor;
    }
    
    //salvar
    public function salvar() {
        $query = '
            insert into usuarios(nome, email, senha)
            values(:nome, :email, :senha)
        ';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha')); //md5() has 32 carac
        $stmt->execute();
        return $this;
    }

    //validar
    public function validarCadastro() {
        $valido = true;

        if(strlen($this->__get('nome')) < 4) $valido = false;
        if(strlen($this->__get('email')) < 4) $valido = false;
        if(strlen($this->__get('senha')) < 4) $valido = false;

        return $valido;
    }

    public function getUsuarioPorEmail() {
        $query = '
            select nome, email
            from usuarios
            where email = :email
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //autenticar
    public function autenticar() {
        $query = '
            select id, nome, email
            from usuarios
            where email = :email and senha = :senha
        ';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if(!empty($usuario['id']) && !empty($usuario['nome'])) {
            $this->__set('id', $usuario['id']);
            $this->__set('nome', $usuario['nome']);
        }
    }

    public function getAll($empty = false) {
        if($empty) {
            $query = '
                select
                    u.id,
                    u.nome,
                    u.email,
                    (
                        select count(*)
                        from usuarios_seguidores as us
                        where us.id_usuario_seguindo = u.id and  us.id = :id
                    ) as segue
                from usuarios as u
                where id != :id
                limit 20
            ';//offset
        } else {
            $query = '
                select
                    u.id,
                    u.nome,
                    u.email,
                    (
                        select count(*)
                        from usuarios_seguidores as us
                        where us.id_usuario_seguindo = u.id and  us.id = :id
                    ) as segue
                from usuarios as u
                where nome like :nome and id != :id
                limit 20
            ';
        }
        $stmt = $this->db->prepare($query);
        if(!$empty) $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    //get infos
    public function getInfoUsuario() {
        $query = '
            select nome
            from usuarios
            where id = :id
        ';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getTotalTweets() {
        $query = '
            select count(*) as total_tweet
            from tweets
            where id_usuario = :id
        ';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getTotalSeguindo() {
        $query = '
            select count(*) as total_seguindo
            from usuarios_seguidores
            where id = :id
        ';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getTotalSeguidores() {
        $query = '
            select count(*) as total_seguidores
            from usuarios_seguidores
            where id_usuario_seguindo = :id
        ';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}