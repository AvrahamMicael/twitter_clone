<?php

namespace MF\Init;

abstract class Bootstrap {
    // private $url;
    private $routes;

    public function __construct() {
        $this->initRoutes();
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->run($url);
    }

    //get|set
    public function getRoutes() {
        return $this->routes;
    }
    public function setRoutes(array $routes) {
        $this->routes = $routes;
    }

    //run
    public function run($url) {
        foreach($this->getRoutes() as $route) {
            if($url == $route['route']) {
                $class = '\\App\\Controllers\\'.ucfirst($route['controller']);

                $controller = new $class;

                $action = $route['action'];

                $controller->$action();
            }
        }
    }

    //initRoutes
    abstract protected function initRoutes();

}