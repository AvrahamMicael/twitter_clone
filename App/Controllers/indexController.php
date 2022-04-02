<?php

namespace App\Controllers;

use App\Connection;
//MF
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {
    public function index() {
        // $this->view->dados = Container::getDadosContainer('');
        $this->render('index');
    }

    public function inscreverse() {
        $this->render('inscreverse');
    }
}