<?php

namespace App\Controllers;

use App\Connection;
//MF
use MF\Controller\Action;
use MF\Model\Container;
//models
use App\Models\Produto;
use App\Models\Info;

class IndexController extends Action {
    public function index() {
        $this->view->dados = Container::getDadosContainer('produto');
        $this->render('index', 'layout');
    }
}