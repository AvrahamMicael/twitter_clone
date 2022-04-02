<?php

namespace MF\Model;

use App\Connection;

class Container {
    public static function getDadosContainer($tipo) {
        $conn = Connection::conectar();
        $tipo = ucfirst($tipo);
        $class = "\\App\\Models\\$tipo";
        $model = new $class($conn);
        return $model->getDados();
    }
}