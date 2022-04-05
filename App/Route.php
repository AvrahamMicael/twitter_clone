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
        $routes['autenticar'] = [
            'route' => '/autenticar',
            'controller' => 'AuthController',
            'action' => 'autenticar'
        ];
        $routes['timeline'] = [
            'route' => '/timeline',
            'controller' => 'AppController',
            'action' => 'timeline'
        ];
        $routes['sair'] = [
            'route' => '/sair',
            'controller' => 'AuthController',
            'action' => 'sair'
        ];
        $routes['tweet'] = [
            'route' => '/tweet',
            'controller' => 'AppController',
            'action' => 'tweet'
        ];
        $routes['quem_seguir'] = [
            'route' => '/quem_seguir',
            'controller' => 'AppController',
            'action' => 'quemSeguir'
        ];
        $routes['acao'] = [
            'route' => '/acao',
            'controller' => 'AppController',
            'action' => 'acao'
        ];
        $routes['remover_tweet'] = [
            'route' => '/remover_tweet',
            'controller' => 'AppController',
            'action' => 'removerTweet'
        ];
        $this->setRoutes($routes);
    }
}