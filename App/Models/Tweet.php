<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model {
    private $id;
    private $id_usuario;
    private $tweet;
    private $data;

    public function __get($atr) {
        return $this->$atr;
    }
    public function __set($atr, $valor) {
        $this->$atr = $valor;
    }

    //salvar
    public function salvar() {
        $query = '
            insert into tweets(id_usuario, tweet)
            values(:id_usuario, :tweet)
        ';
        $this->prepareExecQuery(['id_usuario', 'tweet'], $query);
    }

    //recuperar
    public function getAll() {
        $query = '
            select 
                u.nome,
                t.id,
                t.id_usuario,
                t.tweet,
                date_format(t.data, "%d/%m/%Y %H:%i") as data
            from tweets as t
            left join usuarios as u
            on t.id_usuario = u.id
            where 
                t.id_usuario = :id_usuario
                or
                t.id_usuario in(
                    select id_usuario_seguindo
                    from usuarios_seguidores
                    where id = :id_usuario
                )
            order by t.data desc
        ';
        return $this->prepareExecQuery(['id_usuario'], $query, true);
    }

    public function removerTweet() {
        $query = '
            delete from tweets
            where
                id_usuario = :id_usuario
                and
                id = :id
        ';
        $this->prepareExecQuery(['id_usuario', 'id'], $query);
    }
}