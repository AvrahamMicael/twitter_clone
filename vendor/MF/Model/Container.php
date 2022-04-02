<?php

namespace MF\Model;

use App\Connection;

class Container {
    public static function getModel($tipo) {
        $conn = Connection::conectar();
        $tipo = ucfirst($tipo);
        $class = "\\App\\Models\\$tipo";
        return new $class($conn);
    }
}