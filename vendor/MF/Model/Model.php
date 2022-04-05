<?php

namespace MF\Model;

abstract class Model {
    protected $db;

    public function __construct(\PDO $conn) {
        $this->db = $conn;
    }

    protected function prepareExecQuery(array $bindValue = [], $query, $fetchAll = false) {
        $stmt = $this->db->prepare($query);

        foreach($bindValue as $value) {
            $stmt->bindValue(':'.$value, $this->__get($value));
        }

        $stmt->execute();
        if($fetchAll) return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}