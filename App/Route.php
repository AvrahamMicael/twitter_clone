<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {
    //initRoutes
    protected function initRoutes() {
        $routes['home'] = [
            'route' => '/',
            'controller' => 'indexController',
            'action' => 'index'
        ];
        $routes['inscreverse'] = [
            'route' => '/inscreverse',
            'controller' => 'indexController',
            'action' => 'inscreverse'
        ];
        $routes['registrar'] = [
            'route' => '/registrar',
            'controller' => 'indexController',
            'action' => 'registrar'
        ];
        $this->setRoutes($routes);
    }
}